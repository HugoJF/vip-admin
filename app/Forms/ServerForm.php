<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ServerForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', [
                'label'      => 'Server name',
                'rules'      => ['required'],
                'help_block' => [
                    'text' => 'A way to identify the server.',
                ],
            ])
            ->add('ip', 'text', [
                'label'      => 'IP Address',
                'help_block' => [
                    'text' => 'IP Address of the server.',
                ],
            ])
            ->add('port', 'number', [
                'label'      => 'Server Port',
                'help_block' => [
                    'text' => 'Port number used to connect to the server.',
                ],
            ])
            ->add('password', 'text', [
                'label'      => 'RCON Password',
                'help_block' => [
                    'text' => 'RCON Password.',
                ],
            ])
            ->add('ftp_host', 'text', [
                'label'      => 'FTP Host',
                'help_block' => [
                    'text' => 'FTP Hostname or IP to connect.',
                ],
            ])
            ->add('ftp_user', 'text', [
                'label'      => 'FTP Username',
                'help_block' => [
                    'text' => 'FTP Username used to sync server files',
                ],
            ])
            ->add('ftp_password', 'text', [
                'label'      => 'FTP Password',
                'help_block' => [
                    'text' => 'Password to be used',
                ],
            ])
            ->add('ftp_root', 'text', [
                'label'      => 'FTP Root',
                'help_block' => [
                    'text' => 'What folder in relation to the base folder should we sync the files?',
                ],
            ]);
    }
}
