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
                'help_block' => [
                    'text' => 'How many characters Public IDs should have.',
                ],
            ])
            ->add('extra-token-expiration', 'number', [
                'label'      => 'Extra token expiration',
                'rules'      => ['required'],
                'post-addon' => 'hours',
                'help_block' => [
                    'text' => 'How long should Extra Tokens be valid for.',
                ],
            ])
            ->add('extra-token-duration', 'number', [
                'label'      => 'Extra token duration',
                'rules'      => ['required'],
                'post-addon' => 'days',
                'help_block' => [
                    'text' => 'How many days should an Extra Token give.',
                ],
            ])
            ->add('order-duration-per-extra-token', 'number', [
                'label'      => 'Order duration per extra token',
                'rules'      => ['required'],
                'post-addon' => 'days',
                'help_block' => [
                    'text' => 'How many days an Order should be to give the Owner an Extra Token.',
                ],
            ])
            ->add('token-size', 'number', [
                'label'      => 'Token Size',
                'rules'      => ['required'],
                'post-addon' => 'characters',
                'help_block' => [
                    'text' => 'How many characters Tokens should have.',
                ],
            ])
            ->add('max-order-price', 'number', [
                'label'      => 'Maximum Order Price',
                'rules'      => ['required'],
                'pre-addon'  => '$',
                'post-addon' => 'dollar cents',
                'help_block' => [
                    'text' => 'What\'s the maximum Order price allowed.',
                ],
            ])
            ->add('max-order-duration', 'number', [
                'label'      => 'Maximum Order Duration',
                'post-addon' => 'days',
                'help_block' => [
                    'text' => 'What\'s the maximum Order duration allowed.',
                ],
            ])
            ->add('min-order-duration', 'number', [
                'label'      => 'Minimum Order Duration',
                'post-addon' => 'days',
                'help_block' => [
                    'text' => 'What\'s the minimum Order duration allowed.',
                ],
            ])
            ->add('max-order-date', 'datetimepicker', [
                'label'      => 'Maximum Order Date',
                'help_block' => [
                    'text' => 'What\'s the date limit allowed.',
                ],
            ])
            ->add('expiration-time-min', 'number', [
                'label'      => 'Order Expiration Time',
                'post-addon' => 'minutes',
                'help_block' => [
                    'text' => 'How long should we wait Trade Offers to be accepted.',
                ],
            ])
            ->add('cost-per-day', 'number', [
                'label'      => 'Cost per day',
                'pre-addon'  => '$',
                'post-addon' => 'dollar cents',
                'help_block' => [
                    'text' => 'The cost per day using Steam Items.',
                ],
            ])
            ->add('global-home', 'summernote', [
                'label' => 'Global Home',
            ])
            ->add('not-accepted-home', 'summernote', [
                'label' => 'Not Accepted Home',
            ])
            ->add('accepted-home', 'summernote', [
                'label' => 'Accepted Home',
            ]);
    }
}
