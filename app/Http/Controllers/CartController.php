<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        return response()->json($this->cartService->getCart(auth()->id()));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]); 

        try {
            $cart = $this->cartService->addItem(
                auth()->id(),
                $validated['product_id'],
                $validated['quantity']
            );
            return response()->json($cart);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateQuantity(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $cart = $this->cartService->updateQuantity(
                auth()->id(),
                $productId,
                $validated['quantity']
            );
            return response()->json($cart);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function remove(Request $request, $productId)
    {
        $cart = $this->cartService->removeItem(auth()->id(), $productId);
        return response()->json($cart);
    }
}
