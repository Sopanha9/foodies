@extends('layouts.app')

@section('content')
<h1>Menu</h1>
@forelse($categories as $category)
    <section style="margin-bottom: 20px;">
        <h2>{{ $category->name }}</h2>
        <ul>
            @forelse($category->menuItems as $item)
                <li>{{ $item->name }} - ${{ number_format((float) $item->price, 2) }}</li>
            @empty
                <li>No available items in this category.</li>
            @endforelse
        </ul>
    </section>
@empty
    <p>No categories yet.</p>
@endforelse
@endsection
