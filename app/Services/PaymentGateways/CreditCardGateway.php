<?php
// app/Services/PaymentGateways/CreditCardGateway.php
namespace App\Services\PaymentGateways;

use App\Models\Order;

class CreditCardGateway implements PaymentGatewayInterface
{
    protected $config;
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function process(Order $order, array $payload): array
    {
        // Simulate logic: e.g. call external SDK or simulate success/fail
        // Here we simulate success 80% of the time
        $ok = random_int(1, 100) <= 80;
        $status = $ok ? 'successful' : 'failed';
        $response = [
            'gateway' => 'credit_card',
            'transaction_id' => 'cc_' . uniqid(),
            'payload' => $payload
        ];
        return ['status' => $status, 'response' => $response];
    }
}
