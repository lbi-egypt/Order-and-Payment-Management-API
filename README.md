# Order-and-Payment-Management-API
Extendable Order and Payment Management API

1 - Introduction

2 - Authentication

3 - Orders

4 - Payments

5 - Business Rules

6 - Error Responses

7 - Environment Variables

8 - How to Add a New Payment Gateway

## Introduction
- Manage customer orders
- Process payments through multiple gateways
- Extend the system easily by adding new gateway classes
- Enforce consistent business logic for orders/payments
- Authentication uses JWT (JSON Web Tokens).
- All protected endpoints must include: ==> Authorization: Bearer <token>

## Authentication
Register, Login, Logout ..... You can find in ==> Payment.postman_collection.json

## Orders
Create Order, List Orders, View Single Order, Update Order, Delete Order ..... You can find in ==> Payment.postman_collection.json

## Payments
List Payments, List Payments for Order, View Payment ..... You can find in ==> Payment.postman_collection.json

## Business Rules
Orders:

Orders can be created in pending, confirmed, or cancelled status.

Orders cannot be deleted if they have payments.

Payments:

Payments can only be processed for confirmed orders.

Payments use pluggable gateway classes via the Strategy Pattern.

## Error Responses
HTTP Code 400: Bad Request

HTTP Code 401: Unauthorized

HTTP Code 403: Forbidden

HTTP Code 409: Conflict

HTTP Code 422: Unprocessable Entity

## Environment Variables

```
PAYMENT_GATEWAYS=credit_card,paypal

CREDIT_CARD_API_KEY=sk_test_credit

PAYPAL_CLIENT_ID=paypal-user

PAYPAL_SECRET=paypal-secret
```

## How to Add a New Payment Gateway

1 - Create a new gateway class:

/app/Services/PaymentGateways/MyNewGateway.php

2 - Implement the interface:
```
class MyNewGateway implements PaymentGatewayInterface {

    public function process(Order $order, array $payload): array {
    
        // ...
        
    }
    
}
```
3 - Register gateway in config/payment_gateways.php:
```
'map' => [
    'MyNew' => App\Services\PaymentGateways\MyNewGateway::class,
]
```
4 - Add credentials to .env:

```
MyNewGateway_KEY=xxx
MyNewGateway_SECRET=yyy

```
