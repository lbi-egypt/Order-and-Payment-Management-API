<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();

        $payload = [
            'items' => [
                ['product_name' => 'A', 'quantity' => 2, 'price' => 10],
                ['product_name' => 'B', 'quantity' => 1, 'price' => 5],
            ],
            'status' => 'confirmed'
        ];

        $this->actingAs($user, 'api')
            ->postJson('/api/orders', $payload)
            ->assertStatus(201)
            ->assertJsonPath('total', '25.00');
    }
}
