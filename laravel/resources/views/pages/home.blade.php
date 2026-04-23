@extends('layouts.app')

@section('content')
<h1 style="margin-bottom: 8px;">Foodies Laravel Migration</h1>
<p style="margin-top: 0; color: #5b5247;">This is the new Laravel backend shell for your restaurant website.</p>

<h2>Categories</h2>
<ul>
    @forelse($categories as $category)
        <li>{{ $category->name }}</li>
    @empty
        <li>No categories found yet.</li>
    @endforelse
</ul>

<h2>Featured Items</h2>
<ul>
    @forelse($featuredItems as $item)
        <li>{{ $item->name }} - ${{ number_format((float) $item->price, 2) }}</li>
    @empty
        <li>No menu items found yet.</li>
    @endforelse
</ul>
@endsection
