<?php

namespace App;

use App\Classes\MP2;
use App\Interfaces\IOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class MPOrder extends Model implements IOrder
{
	protected $table = 'mp_orders';

	public function baseOrder()
	{
		return $this->morphOne('App\Order', 'orderable')->withTrashed();
	}

	public function type($type)
	{
		$types = ['App\MPOrder', 'MP', 'MPOrder', 'MercadoPago'];

		return in_array($type, $types);
	}

	public function getRouteKeyName()
	{
		return 'public_id';
	}

	public function recheck()
	{
		if (!empty($this->mp_order_id)) {
			// Query merchant_orders information
			$order = MP2::get('merchant_orders', $this->mp_order_id);

			// Check if response was valid
			if (!array_key_exists('status', $order)) {
				throw new \Exception('MercadoPago API returned empty response without status.');
			}

			if ($order['status'] != 200) {
				throw new \Exception('MercadoPago API returned with status: ' . $order['status']);
			}

			// Update empty fields
			if (empty($this->mp_preference_id)) {
				$this->mp_preference_id = $order['response']['preference_id'];
			}

			$this->paid_amount = $order['response']['paid_amount'] * 100;

			$this->mp_order_status = $order['response']['status'];

			$this->touch();

			$this->save();
			Log::info('Order rechecked to: ' . $this->mp_order_status);
			Log::info('Order rechecked with total paid amount: R$ ' . $order['response']['paid_amount']);
		}
	}

	public function status()
	{
		$status = $this->mp_order_status;

		if ($this->paid()) {
			$status = 'paid';
		}

		return [
			'text'  => $this->statusText($status),
			'class' => $this->statusClass($status),
		];
	}

	public function statusText($status)
	{
		if ($this->confirmed()) {
			return 'Confirmed';
		}
		
		switch ($status) {
			case 'paid':
				return 'Paid';
				break;
			case 'closed':
				return 'Closed';
				break;
			case'':
			case 'pending':
				return 'Waiting payment';
				break;
			case 'opened':
				return 'Opened for payment';
				break;
			default:
				return $status;
				break;
		}
	}

	public function statusClass($status)
	{
		if ($this->confirmed()) {
			return 'success';
		}

		switch ($status) {
			case 'paid':
				return 'success';
				break;
			case 'closed':
			case '':
			case 'pending':
			case 'opened':
				return 'warning';
				break;
			default:
				return 'danger';
				break;
		}
	}

	public function opened()
	{
		return $this->mp_order_status == 'opened';
	}

	public function closed()
	{
		return $this->mp_order_status == 'closed';
	}

	public function paid()
	{
		return $this->paid_amount >= $this->amount;
	}

	public function confirmed()
	{
		return $this->baseOrder->confirmation;
	}

	public function canGenerateConfirmation($flashError = false)
	{
		$can = $this->paid();

		if (!$can && $flashError) {
			flash()->error(__('messages.model-mp-orders-cannot-generate-confirmation'));
		}

		return $can;
	}

	public function step()
	{
		$step = 1;

		if ($this->paid()) {
			$step++;
		}

		if ($this->baseOrder && $this->confirmed()) {
			$step++;
		}

		if ($this->baseOrder->server_uploaded) {
			$step++;
		}

		return $step;
	}
}
