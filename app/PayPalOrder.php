<?php

namespace App;

use App\Http\Controllers\PayPalController;
use App\Interfaces\IOrder;
use Illuminate\Database\Eloquent\Model;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalOrder extends Model implements IOrder
{
	protected $table = 'paypal_orders';

	public function getRouteKeyName()
	{
		return 'public_id';
	}

	public function baseOrder()
	{
		return $this->morphOne('App\Order', 'orderable')->withTrashed();
	}

	/**
	 * Return array with current status of the order
	 * ['class'] = CSS Class representing the status
	 * ['text']  = Text explaining the status.
	 *
	 * @return array
	 */
	public function status()
	{
		$status = $this->status;

		if (!$status)
			$status = 'waiting';

		return [
			'text'  => $this->statusText($status),
			'class' => $this->statusClass($status),
		];
	}

	protected function statusText($status)
	{
		switch ($status) {
			case 'waiting':
				return 'Waiting payment';
			default:
				return $status;
		}
	}

	protected function statusClass($status)
	{
		switch ($status) {
			case 'waiting':
				return 'warning';
			case 'Completed':
			case 'Processed':
				return 'success';
		}

		return 'danger';
	}

	/**
	 * Refreshes the order.
	 *
	 * @return mixed
	 */
	public function recheck()
	{
		$provider = new ExpressCheckout();

		// Check if PayPal has checkout details
		$response = $provider->getExpressCheckoutDetails($this->token);

		// Check if response was successful
		if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
			flash()->error('Error while communicating with PayPal.');
		}

		// Check if checkout has a payer
		if (!array_key_exists('PAYERID', $response)) {
			flash()->error('Order does not have a PayerID associated with it!');

			return;
		}

		// Check if order has Billing Agreement Status field
		//		if (!array_key_exists('BILLINGAGREEMENTACCEPTEDSTATUS', $response)) {
		//			flash()->error('Billing agreement status is missing.');
		//
		//			return;
		//		}

		// Check if user has accepted Billing Agreement
		//		if ($response['BILLINGAGREEMENTACCEPTEDSTATUS'] != 1) {
		//			flash()->error('Payer has not accepted billing agreement.');
		//
		//			return;
		//		}

		// If there is no transaction, and this code is reached, execute transaction
		if (!array_key_exists('TRANSACTIONID', $response)) {
			$response = $provider->doExpressCheckoutPayment(PayPalController::getCheckoutCart($this->baseOrder), $this->token, $response['PAYERID']);

			\Log::info('DoExpressCheckoutPayment response', ['response' => $response]);

			$response = $provider->getExpressCheckoutDetails($this->token);
		}

		// Update order transaction
		$this->transaction_id = $response['TRANSACTIONID'];

		// Retrieve payment details
		$paymentDetails = $provider->getTransactionDetails($this->transaction_id);
		$status = $paymentDetails['PAYMENTSTATUS'];

		// Update database
		$this->status = $status;
		$this->save();
	}

	/**
	 * Returns the current step of the order.
	 *
	 * @return int - Current step
	 */
	public function step()
	{
		// Created
		$step = 1;

		// Paid
		if ($this->paid()) {
			$step++;
		}

		// Confirmed
		if ($this->baseOrder && $this->confirmed()) {
			$step++;
		}

		// Synced
		if ($this->baseOrder->server_uploaded) {
			$step++;
		}

		return $step;
	}

	public function confirmed()
	{
		return $this->baseOrder->confirmation;
	}

	/**
	 * Checks if Order is of type $type.
	 *
	 * @param $type - The name of the type
	 *
	 * @return bool - Is of type $type
	 */
	public function type($type)
	{
		$types = ['App\PayPalOrder', 'PP', 'PPOrder', 'PayPal'];

		return in_array($type, $types);
	}

	/**
	 * Checks if Order is in a state that allows confirmation generation.
	 *
	 * @param $flashError - Should the message error be flashed?
	 *
	 * @return bool - If should generate confirmation
	 */
	public function canGenerateConfirmation($flashError = false)
	{
		$can = $this->paid();

		if (!$can && $flashError) {
			flash()->error(__('messages.model-mp-orders-cannot-generate-confirmation'));
		}

		return $can;
	}

	public function paid()
	{
		return (!strcasecmp($this->status, 'Completed') || !strcasecmp($this->status, 'Processed'));
	}
}