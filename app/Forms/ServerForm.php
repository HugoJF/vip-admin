<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ServerForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', [
                'label'      => __('messages.server-name'),
                'rules'      => ['required'],
                'help_block' => [
                    'text' => __('messages.form-server-name-help'),
                ],
            ])
            ->add('ip', 'text', [
                'label'      => __('messages.server-ip'),
                'help_block' => [
                    'text' => __('messages.form-server-ip-help'),
                ],
            ])
            ->add('port', 'number', [
                'label'      => __('messages.server-port'),
                'help_block' => [
                    'text' => __('messages.form-server-port-help'),
                ],
            ])
            ->add('password', 'text', [
                'label'      => __('messages.server-password'),
                'help_block' => [
                    'text' => __('messages.form-server-password-help'),
                ],
            ])
            ->add('ftp_host', 'text', [
                'label'      => __('messages.server-ftp-host'),
                'help_block' => [
                    'text' => __('messages.form-server-ftp-host-help'),
                ],
            ])
            ->add('ftp_user', 'text', [
                'label'      => __('messages.server-ftp-user'),
                'help_block' => [
                    'text' => __('messages.form-server-ftp-user-help'),
                ],
            ])
            ->add('ftp_password', 'text', [
                'label'      => __('messages.server-ftp-password'),
                'help_block' => [
                    'text' => __('messages.form-server-ftp-password-help'),
                ],
            ])
            ->add('ftp_root', 'text', [
                'label'      => __('messages.server-ftp-root'),
                'help_block' => [
                    'text' => __('messages.form-server-ftp-root-help'),
                ],
            ]);
    }
}
