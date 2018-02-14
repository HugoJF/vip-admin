<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class OrderForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('duration', 'number', [
                'label'      => 'Duration',
                'rules'      => ['required'],
                'post-addon' => 'days',
                'help_block' => [
                    'text' => 'How many days this order is for.',
                ],
            ])
            ->add('extra_tokens', 'number', [
                'label'      => 'Extra tokens',
                'rules'      => ['required'],
                'post-addon' => 'tokens',
                'help_block' => [
                    'text' => 'How many tokens the User will be rewarded.',
                ],
            ]);
    }
}
