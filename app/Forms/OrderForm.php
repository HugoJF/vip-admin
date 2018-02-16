<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class OrderForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('duration', 'number', [
                'label'      => __('messages.duration'),
                'rules'      => ['required'],
                'post-addon' => trans_choice('messages.time.days', 2),
                'help_block' => [
                    'text' => __('messages.form-order-duration-help'),
                ],
            ])
            ->add('extra_tokens', 'number', [
                'label'      => __('messages.extra-tokens'),
                'rules'      => ['required'],
                'post-addon' => trans_choice('messages.token', 2),
                'help_block' => [
                    'text' => __('messages.form-order-extra-tokens-help'),
                ],
            ]);
    }
}
