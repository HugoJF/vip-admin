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

			$this->paid_amount = collect($order['response']['payments'])->reduce(function ($acc, $payment) {
				if ($payment['status'] === 'approved') {
					return $acc + $payment['transaction_amount'] * 100;
				} else {
					return $acc;
				}
			}, 0);

			$this->mp_order_status = $order['response']['status'];
			if ($this->mp_order_status === 'paid' && !$this->paid())
				$this->mp_order_status = 'closed';

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

		if ($this->confirmed()) {
			$status = 'confirmed';
		}

		return [
			'status' => $status,
			'text'   => $this->statusText($status),
			'class'  => $this->statusClass($status),
		];
	}

	public function statusText($status)
	{
		switch ($status) {
			case 'confirmed':
				return 'Confirmed';
			case 'paid':
				return 'Paid';
			case 'closed':
				return 'Pending';
			case '':
			case 'pending':
				return 'Waiting payment';
			case 'opened':
				return 'Open';
			default:
				return $status;
				break;
		}
	}

	public function statusClass($status)
	{
		switch ($status) {
			case 'confirmed':
				return 'success';
			case 'paid':
				return 'info';
			case 'closed':
			case '':
			case 'pending':
			case 'opened':
				return 'warning';
			default:
				return 'danger';
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
