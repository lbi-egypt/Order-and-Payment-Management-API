<?php

namespace App\Services\PaymentGateways;

use App\Models\Order;

class PaypalGateway implements PaymentGatewayInterface
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function process(Order $order, array $payload): array
    {
        // Simulated processing: 90% success rate
        $ok = random_int(1, 100) <= 90;
        $status = $ok ? 'successful' : 'failed';
        $response = [
            'gateway' => 'paypal',
            'transaction_id' => 'pp_' . uniqid(),
            'payload' => $payload,
            'config' => $this->config,
        ];
        return ['status' => $status, 'response' => $response];
    }
}
