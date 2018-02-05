<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class LaravelSettingsForm extends Form
{
	public function buildForm()
	{
		$this
			->add('public-id-size', 'number', [
				'label'      => 'Public ID Size',
				'rules'      => ['required'],
				'post-addon' => 'characters',
			])
			->add('extra-token-expiration', 'number', [
				'label'      => 'Extra token expiration',
				'rules'      => ['required'],
				'post-addon' => 'hours',
			])
			->add('extra-token-duration', 'number', [
				'label'      => 'Extra token duration',
				'rules'      => ['required'],
				'post-addon' => 'days',
			])
			->add('order-duration-per-extra-token', 'number', [
				'label'      => 'Order duration per extra token',
				'rules'      => ['required'],
				'post-addon' => 'days',
			])
			->add('token-size', 'number', [
				'label'      => 'Token Size',
				'rules'      => ['required'],
				'post-addon' => 'characters',
			])
			->add('max-order-price', 'number', [
				'label'      => 'Maximum Order Price',
				'rules'      => ['required'],
				'pre-addon'  => '$',
				'post-addon' => 'dollar cents',
			])
			->add('max-order-duration', 'number', [
				'label'      => 'Maximum Order Duration',
				'post-addon' => 'days',
			])
			->add('max-order-date', 'datetimepicker', [
				'label' => 'Maximum Order Date',
			])
			->add('expiration-time-min', 'number', [
				'label'      => 'Order Expiration Time',
				'post-addon' => 'minutes',
			])
			->add('cost-per-day', 'number', [
				'label'      => 'Cost per day',
				'pre-addon'  => '$',
				'post-addon' => 'dollar cents',
			]);
	}
}
