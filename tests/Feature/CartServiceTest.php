<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Services\CartService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    private $cartService;
    private $user;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = app(CartService::class);
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create([
            'stock' => 10,
            'price' => 100
        ]);
    }

    public function test_can_add_item()
    {
        $cart = $this->cartService->addItem($this->user->id, $this->product->id, 2);

        $this->assertCount(1, $cart['items']);
        $this->assertEquals(200, $cart['total']);
        $this->assertEquals(2, $cart['items'][0]['quantity']);
    }

    public function test_can_update_quantity()
    {
        $this->cartService->addItem($this->user->id, $this->product->id, 1);
        $cart = $this->cartService->updateQuantity($this->user->id, $this->product->id, 3);

        $this->assertEquals(3, $cart['items'][0]['quantity']);
        $this->assertEquals(300, $cart['total']);
    }

    public function test_can_remove_item()
    {
        $this->cartService->addItem($this->user->id, $this->product->id, 1);
        $cart = $this->cartService->removeItem($this->user->id, $this->product->id);

        $this->assertEmpty($cart['items']);
        $this->assertEquals(0, $cart['total']);
    }

    public function test_can_clear_cart()
    {
        $this->cartService->addItem($this->user->id, $this->product->id, 1);
        $this->cartService->clearCart($this->user->id);
        $cart = $this->cartService->getCart($this->user->id);

        $this->assertEmpty($cart['items']);
        $this->assertEquals(0, $cart['total']);
    }
}
