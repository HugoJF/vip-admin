<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ConfirmationForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('start_period', 'datetimepicker', [
                'label'      => __('messages.confirmation-starting-period'),
                'rules'      => ['required'],
                'help_block' => [
                    'text' => __('messages.form-confirmation-starting-period-help'),
                ],
            ])
            ->add('end_period', 'datetimepicker', [
                'label'      => __('messages.confirmation-ending-period'),
                'rules'      => ['required'],
                'help_block' => [
                    'text' => __('messages.form-confirmation-ending-period-help'),
                ],
            ]);
    }
}
