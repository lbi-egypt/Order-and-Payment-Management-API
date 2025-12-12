<?php

namespace App\Services\PaymentGateways;

use App\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Process payment for an order.
     * Returns array: ['status'=>'successful'|'failed'|'pending', 'response'=>[...]]
     */
    public function process(Order $order, array $payload): array;
}
