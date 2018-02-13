<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ServerForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('server-name', 'text', [
                'label'      => 'Server name',
                'rules'      => ['required'],
                'help_block' => [
                    'text' => 'A way to identify the server.',
                ],
            ])
            ->add('server-ip', 'text', [
                'label' => 'IP Address',
                'help_block' => [
                    'text' => 'IP Address of the server',
                ],
            ])
            ->add('server-port', 'number', [
                'label' => 'Server Port',
                'help_block' => [
                    'text' => 'Port number used to connect to the server',
                ],
            ])
            ->add('server-password', 'password', [
                'label' => 'RCON Password',
                'help_block' => [
                    'text' => 'RCON Password',
                ],
            ]);
    }
}
