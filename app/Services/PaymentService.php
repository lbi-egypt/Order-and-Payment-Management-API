<?php

namespace App\Services;

use App\Services\PaymentGateways\PaymentGatewayInterface;
use Illuminate\Contracts\Container\Container as AppContainer;

class PaymentService
{
    protected $app;
    protected $map; // method -> binding
    public function __construct(AppContainer $app)
    {
        $this->app = $app;
        $this->map = config('payment_gateways.map', [
            'credit_card' => \App\Services\PaymentGateways\CreditCardGateway::class,
            'paypal' => \App\Services\PaymentGateways\PaypalGateway::class,
        ]);
    }

    public function process(string $method, $order, array $payload): array
    {
        if (!isset($this->map[$method])) throw new \Exception("Unknown payment method: {$method}");
        $class = $this->map[$method];

        // Resolve and pass gateway-specific config
        $gatewayConfig = config("payment_gateways.config.{$method}", []);
        $gateway = $this->app->make($class, ['config' => $gatewayConfig]);
        if (!($gateway instanceof PaymentGatewayInterface)) {
            throw new \Exception("Gateway {$method} must implement PaymentGatewayInterface");
        }

        return $gateway->process($order, $payload);
    }
}
