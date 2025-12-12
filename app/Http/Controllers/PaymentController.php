<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $req)
    {
        $perPage = intval($req->get('per_page', 15));
        $query = Payment::query()->whereHas('order', function ($q) {
            $q->where('user_id', auth()->id());
        });
        return response()->json($query->with('order')->paginate($perPage));
    }

    public function indexForOrder(Order $order)
    {
        $this->authorize('view', $order);
        return response()->json($order->payments()->paginate(15));
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment->order);
        return response()->json($payment);
    }

    public function process(Order $order, ProcessPaymentRequest $req, PaymentService $service)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'confirmed') {
            return response()->json(['error' => 'Order must be confirmed to process payment'], 422);
        }

        $payload = $req->input('payload', []);
        $method = $req->input('method');

        $payment = Payment::create([
            'payment_id' => (string) Str::uuid(),
            'order_id' => $order->id,
            'status' => 'pending',
            'method' => $method,
            'amount' => $order->total,
        ]);

        $result = $service->process($method, $order, $payload);

        $payment->update([
            'status' => $result['status'] ?? 'failed',
            'response' => $result['response'] ?? null,
        ]);

        return response()->json(['payment' => $payment, 'gateway' => $result], $result['status'] === 'successful' ? 200 : 400);
    }
}
