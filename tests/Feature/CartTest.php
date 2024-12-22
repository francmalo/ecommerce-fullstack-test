<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;





class CartTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create([
            'stock' => 10
        ]);
        Redis::flushall();
    }

    public function test_can_add_item_to_cart()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/cart/add', [
                'product_id' => $this->product->id,
                'quantity' => 2
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity' => 2
                    ]
                ]
            ]);
    }

    public function test_cannot_add_more_than_stock()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/cart/add', [
                'product_id' => $this->product->id,
                'quantity' => 11
            ]);

        $response->assertStatus(400);
    }

    public function test_can_update_cart_quantity()
    {
        // First add item to cart
        $this->actingAs($this->user)
            ->postJson('/api/cart/add', [
                'product_id' => $this->product->id,
                'quantity' => 1
            ]);

        $response = $this->putJson("/api/cart/update-quantity/{$this->product->id}", [
            'quantity' => 3
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'items' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity' => 3
                    ]
                ]
            ]);
    }

    public function test_can_remove_item_from_cart()
    {
        // First add item to cart
        $this->actingAs($this->user)
            ->postJson('/api/cart/add', [
                'product_id' => $this->product->id,
                'quantity' => 1
            ]);

        $response = $this->deleteJson("/api/cart/remove/{$this->product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'items' => [],
                'total' => 0
            ]);
    }
}
