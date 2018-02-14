<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ConfirmationForm extends Form
{
	public function buildForm()
	{
		$this
			->add('start_period', 'datetimepicker', [
				'label'      => 'Starting period',
				'rules'      => ['required'],
				'help_block' => [
					'text' => 'When the confirmation starts to be valid.',
				],
			])
			->add('end_period', 'datetimepicker', [
				'label'      => 'Ending period',
				'rules'      => ['required'],
				'help_block' => [
					'text' => 'When the confirmation stops to be valid.',
				],
			]);
	}
}
