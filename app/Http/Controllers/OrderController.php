<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index(Request $req)
    {
        $perPage = intval($req->get('per_page', 15));
        $query = Order::query()->where('user_id', auth()->id());
        //$query = Order::query()->where('user_id', $id);
        if ($status = $req->get('status')) {
            $query->where('status', $status);
        }
        return response()->json($query->with('items', 'payments')->paginate($perPage));
    }

    public function store(StoreOrderRequest $req)
    {
        return DB::transaction(function () use ($req) {
            $user = auth()->user();
            $items = $req->input('items', []);
            $total = 0;
            foreach ($items as $it) {
                $total += ($it['quantity'] * $it['price']);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'status' => $req->input('status', 'pending'),
                'total' => $total,
                'meta' => $req->input('meta', []),
            ]);

            foreach ($items as $it) {
                $order->items()->create([
                    'product_name' => $it['product_name'],
                    'quantity' => $it['quantity'],
                    'price' => $it['price'],
                    'line_total' => $it['quantity'] * $it['price'],
                ]);
            }

            return response()->json($order->load('items'), 201);
        });
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return response()->json($order->load('items', 'payments'));
    }

    public function update(UpdateOrderRequest $req, Order $order)
    {
        $this->authorize('update', $order);

        $data = $req->only(['status', 'meta']);
        $items = $req->input('items', null);

        if ($items !== null) {
            // Replace items and recalc total (simple approach)
            $order->items()->delete();
            $total = 0;
            foreach ($items as $it) {
                $order->items()->create([
                    'product_name' => $it['product_name'],
                    'quantity' => $it['quantity'],
                    'price' => $it['price'],
                    'line_total' => $it['quantity'] * $it['price'],
                ]);
                $total += $it['quantity'] * $it['price'];
            }
            $data['total'] = $total;
        }

        $order->update($data);
        return response()->json($order->load('items', 'payments'));
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        if ($order->payments()->exists()) {
            return response()->json(['error' => 'Order cannot be deleted because payments exist'], 409);
        }
        $order->delete();
        return response()->json(null, 204);
    }
}
