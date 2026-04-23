@extends('layouts.app')

@section('content')
<h1>Cart</h1>
<ul>
    @forelse($cartItems as $item)
        <li>{{ $item['name'] ?? 'Item' }} x {{ $item['quantity'] ?? 0 }} - ${{ number_format(((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0)), 2) }}</li>
    @empty
        <li>Your cart is empty.</li>
    @endforelse
</ul>
<p><strong>Total:</strong> ${{ number_format($total, 2) }}</p>
@endsection
