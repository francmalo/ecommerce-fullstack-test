<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class CartService
{
    private $prefix = 'cart:';

    private function getKey($userId = null)
    {
        if ($userId) {
            return $this->prefix . $userId;
        }

        if (!session()->has('cart_id')) {
            session()->put('cart_id', Str::random(40));
        }

        return $this->prefix . 'guest:' . session('cart_id');
    }

    public function addItem($userId = null, $productId, $quantity)
    {
        $product = Product::findOrFail($productId);
        $key = $this->getKey($userId);

        $existingItem = Redis::hget($key, $productId);
        if ($existingItem) {
            $existingItem = json_decode($existingItem, true);
            $newQuantity = $existingItem['quantity'] + $quantity;
        } else {
            $newQuantity = $quantity;
        }

        if ($product->stock < $newQuantity) {
            throw new \Exception("Only {$product->stock} items available in stock");
        }

        $cartItem = [
            'product_id' => $productId,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $newQuantity
        ];

        Redis::hset($key, $productId, json_encode($cartItem));
        Redis::expire($key, 86400);

        return $this->getCart($userId);
    }

    public function updateQuantity($userId = null, $productId, $quantity)
    {
        $product = Product::findOrFail($productId);
        $key = $this->getKey($userId);

        if ($product->stock < $quantity) {
            throw new \Exception("Only {$product->stock} items available in stock");
        }

        $existingItem = Redis::hget($key, $productId);
        if (!$existingItem) {
            throw new \Exception('Item not found in cart');
        }

        $cartItem = json_decode($existingItem, true);
        $cartItem['quantity'] = $quantity;

        Redis::hset($key, $productId, json_encode($cartItem));
        Redis::expire($key, 86400);

        return $this->getCart($userId);
    }

    public function removeItem($userId = null, $productId)
    {
        $key = $this->getKey($userId);
        Redis::hdel($key, $productId);
        return $this->getCart($userId);
    }

    public function getCart($userId = null)
    {
        $key = $this->getKey($userId);
        $items = Redis::hgetall($key);

        $cart = [
            'items' => [],
            'total' => 0
        ];

        foreach ($items as $item) {
            $decodedItem = json_decode($item, true);
            $cart['items'][] = $decodedItem;
            $cart['total'] += $decodedItem['price'] * $decodedItem['quantity'];
        }

        return $cart;
    }

    public function clearCart($userId = null)
    {
        $key = $this->getKey($userId);
        Redis::del($key);
    }

    public function migrateGuestCart($userId)
    {
        $guestKey = $this->getKey();
        $userKey = $this->getKey($userId);

        $items = Redis::hgetall($guestKey);

        foreach ($items as $productId => $item) {
            Redis::hset($userKey, $productId, $item);
        }

        Redis::del($guestKey);
        session()->forget('cart_id');
    }
}
