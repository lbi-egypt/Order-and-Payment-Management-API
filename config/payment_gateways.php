<?php
return [
    // map name => class
    'map' => [
        'credit_card' => App\Services\PaymentGateways\CreditCardGateway::class,
        'paypal' => App\Services\PaymentGateways\PaypalGateway::class,
    ],

    // per-gateway config (pull from env)
    'config' => [
        'credit_card' => [
            'api_key' => env('CREDIT_CARD_API_KEY'),
        ],
        'paypal' => [
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
        ],
    ],
];
