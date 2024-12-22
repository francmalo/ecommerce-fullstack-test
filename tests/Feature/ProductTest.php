<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;



class ProductTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_products()
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_view_products_page()
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get('/');

        $response->assertStatus(200)
            ->assertViewHas('products');
    }
}
