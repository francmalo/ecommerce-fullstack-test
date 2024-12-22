<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;


class OrderTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create([
            'stock' => 10,
            'price' => 100
        ]);
        Redis::flushall();
    }

    public function test_can_view_checkout_page()
    {
        // Add item to cart first
        $this->actingAs($this->user)
            ->postJson('/api/cart/add', [
                'product_id' => $this->product->id,
                'quantity' => 2
            ]);

        $response = $this->get('/checkout');

        $response->assertStatus(200)
            ->assertViewIs('checkout.index');
    }

    public function test_can_process_checkout()
    {
        // Add item to cart first
        $this->actingAs($this->user)
            ->postJson('/api/cart/add', [
                'product_id' => $this->product->id,
                'quantity' => 2
            ]);

        $response = $this->post('/checkout', [
            'shipping_name' => 'John Doe',
            'shipping_address' => '123 Main St',
            'shipping_city' => 'Test City',
            'shipping_state' => 'Test State',
            'shipping_zip' => '12345',
            'shipping_phone' => '123-456-7890'
        ]);

        $order = Order::first();

        $response->assertRedirect(route('order.confirmation', ['order' => $order->id]));
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'total_amount' => 200, // 2 * $100
            'shipping_name' => 'John Doe'
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);

        // Verify stock was reduced
        $this->assertEquals(8, Product::find($this->product->id)->stock);

        // Verify cart was cleared
        $response = $this->getJson('/api/cart');
        $response->assertJson(['items' => [], 'total' => 0]);
    }

    public function test_cannot_checkout_with_empty_cart()
    {
        $response = $this->actingAs($this->user)
            ->get('/checkout');

        $response->assertRedirect('/');
    }

    public function test_can_view_order_confirmation()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('order.confirmation', ['order' => $order->id]));

        $response->assertStatus(200)
            ->assertViewIs('checkout.confirmation');
    }

    public function test_cannot_view_other_users_order()
    {
        $otherUser = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $otherUser->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('order.confirmation', ['order' => $order->id]));

        $response->assertStatus(403);
    }
}
