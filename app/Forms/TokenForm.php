<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class TokenForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('token', 'text', [
                'label'      => 'Token Value',
                'rules'      => ['required'],
                'help_block' => [
                    'text' => 'The characters that make the token.',
                ],
            ])
            ->add('duration', 'number', [
                'label'      => 'Duration',
                'rules'      => ['required'],
                'post-addon' => 'days',
                'help_block' => [
                    'text' => 'The duration that the token will give.',
                ],
            ])
            ->add('expiration', 'number', [
                'label'      => 'Expiration',
                'rules'      => ['required'],
                'post-addon' => 'hours',
                'help_block' => [
                    'text' => 'How long should the token be valid for.',
                ],
            ])
            ->add('note', 'textarea', [
                'label'      => 'Note',
                'rules'      => ['required'],
                'help_block' => [
                    'text' => 'A friendly note.',
                ],
            ]);
    }
}
