<?php

namespace App\Http\Controllers;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = collect(session('cart', []));

        $total = $cartItems
            ->map(fn (array $item): float => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0))
            ->sum();

        return view('pages.cart', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }
}
