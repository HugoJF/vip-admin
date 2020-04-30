<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class LaravelSettingsForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('public-id-size', 'number', [
                'label'      => __('messages.form-app-public-id-size'),
                'rules'      => ['required'],
                'post-addon' => trans_choice('messages.characters', 2),
                'help_block' => [
                    'text' => __('messages.form-app-public-id-size-help'),
                ],
            ])
            ->add('extra-token-expiration', 'number', [
                'label'      => __('messages.form-app-extra-tokens-expiration'),
                'rules'      => ['required'],
                'post-addon' => trans_choice('messages.time.hours', 2),
                'help_block' => [
                    'text' => __('messages.form-app-extra-tokens-expiration-help'),
                ],
            ])
            ->add('extra-token-duration', 'number', [
                'label'      => __('messages.form-app-extra-tokens-duration'),
                'rules'      => ['required'],
                'post-addon' => trans_choice('messages.time.days', 2),
                'help_block' => [
                    'text' => __('messages.form-app-extra-tokens-duration-help'),
                ],
            ])
            ->add('order-duration-per-extra-token', 'number', [
                'label'      => __('messages.form-app-duration-per-token'),
                'rules'      => ['required'],
                'post-addon' => trans_choice('messages.time.days', 2),
                'help_block' => [
                    'text' => __('messages.form-app-duration-per-token-help'),
                ],
            ])
            ->add('token-size', 'number', [
                'label'      => __('messages.form-app-token-size'),
                'rules'      => ['required'],
                'post-addon' => trans_choice('messages.characters', 2),
                'help_block' => [
                    'text' => __('messages.form-app-token-size-help'),
                ],
            ])
            ->add('max-order-price', 'number', [
                'label'      => __('messages.form-app-max-order-price'),
                'rules'      => ['required'],
                'pre-addon'  => '$',
                'post-addon' => trans_choice('messages.currency-dollar-cents', 2),
                'help_block' => [
                    'text' => __('messages.form-app-max-order-price-help'),
                ],
            ])
            ->add('max-order-duration', 'number', [
                'label'      => __('messages.form-app-max-order-duration'),
                'post-addon' => trans_choice('messages.time.days', 2),
                'help_block' => [
                    'text' => __('messages.form-app-max-order-duration-help'),
                ],
            ])
            ->add('min-order-duration', 'number', [
                'label'      => __('messages.form-app-min-order-duration'),
                'post-addon' => trans_choice('messages.time.days', 2),
                'help_block' => [
                    'text' => __('messages.form-app-min-order-duration-help'),
                ],
            ])
            ->add('max-order-date', 'datetimepicker', [
                'label'      => __('messages.form-app-max-order-date'),
                'help_block' => [
                    'text' => __('messages.form-app-max-order-date-help'),
                ],
            ])
            ->add('expiration-time-min', 'number', [
                'label'      => __('messages.form-app-order-expiration-time'),
                'post-addon' => trans_choice('messages.time.minutes', 2),
                'help_block' => [
                    'text' => __('messages.form-app-order-expiration-time-help'),
                ],
            ])
            ->add('cost-per-day', 'number', [
                'label'      => __('messages.form-app-cost-per-day'),
                'pre-addon'  => '$',
                'post-addon' => trans_choice('messages.currency-dollar-cents', 2),
                'help_block' => [
                    'text' => __('messages.form-app-cost-per-day-help'),
                ],
            ])
            ->add('mp-cost-per-month', 'number', [
                'label'      => __('messages.form-app-mpcost-per-month'),
                'pre-addon'  => 'R$',
                'post-addon' => trans_choice('messages.currency-real-cents', 2),
                'help_block' => [
                    'text' => __('messages.form-app-cost-per-month-help'),
                ],
            ])
            ->add('global-home', 'summernote', [
                'label' => __('messages.form-app-global-home'),
            ])
            ->add('not-accepted-home', 'summernote', [
                'label' => __('messages.form-app-not-accepted-home'),
            ])
            ->add('accepted-home', 'summernote', [
                'label' => __('messages.form-app-accepted-home'),
            ]);
    }
}
