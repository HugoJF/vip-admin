<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class TokenForm extends Form
{
	public function buildForm()
	{
		$this
			->add('token', 'text', [
				'label'      => __('messages.form-token-value'),
				'rules'      => ['required'],
				'help_block' => [
					'text' => __('messages.form-token-value-help'),
				],
			])
			->add('duration', 'number', [
				'label'      => __('messages.form-token-duration'),
				'rules'      => ['required'],
				'post-addon' => __('messages.time.days'),
				'help_block' => [
					'text' => __('messages.form-token-duration-help'),
				],
			])
			->add('expiration', 'number', [
				'label'      => __('messages.form-token-expiration'),
				'rules'      => ['required'],
				'post-addon' => __('messages.time.hours'),
				'help_block' => [
					'text' => __('messages.form-token-expiration-help'),
				],
			])
			->add('note', 'textarea', [
				'label'      => __('messages.form-token-note'),
				'rules'      => ['required'],
				'help_block' => [
					'text' => __('messages.form-token-note-help'),
				],
			]);
	}
}
