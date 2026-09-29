<?php

return [
    'default' => env('PAYMENT_PROVIDER', 'paystack'),

    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
        'base_url' => 'https://api.paystack.co',
    ],
];
