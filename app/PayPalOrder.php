<?php

namespace App;

use App\Interfaces\IOrder;
use Illuminate\Database\Eloquent\Model;

class PayPalOrder extends Model implements IOrder
{

	/**
	 * Return array with current status of the order
	 * ['class'] = CSS Class representing the status
	 * ['text']  = Text explaining the status.
	 *
	 * @return array
	 */
	public function status()
	{
		// TODO: Implement status() method.
	}

	/**
	 * Refreshes the order.
	 *
	 * @return mixed
	 */
	public function recheck()
	{
		// TODO: Implement recheck() method.
	}

	/**
	 * Returns the current step of the order.
	 *
	 * @return int - Current step
	 */
	public function step()
	{
		// TODO: Implement step() method.
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
		// TODO: Implement type() method.
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
		// TODO: Implement canGenerateConfirmation() method.
	}
}