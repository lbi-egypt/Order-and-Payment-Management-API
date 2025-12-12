<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_process_payment_on_unconfirmed_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'total' => 10.00]);

        $this->actingAs($user, 'api')
            ->postJson("/api/orders/{$order->id}/payments", ['method' => 'credit_card'])
            ->assertStatus(422);
    }
}
