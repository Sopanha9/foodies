@extends('layouts.app')

@section('content')
<section class="menu-section" id="menu-section">
    <div class="menu-section__container">
        <div class="menu-section__header">
            <h1 class="menu-section__title">Popular picks right now</h1>
            <p class="menu-section__subtitle">Filter by category, add items quickly, and build your order in under a minute.</p>
        </div>

        <div class="menu-filter" role="tablist" aria-label="Menu categories">
            <button class="menu-filter__btn menu-filter__btn--active" data-filter="all" role="tab" aria-selected="true">All</button>
            @foreach($categories as $category)
                <button class="menu-filter__btn" data-filter="{{ $category->slug }}" role="tab" aria-selected="false">{{ $category->name }}</button>
            @endforeach
        </div>

        <div class="menu-grid" id="menu-grid">
            @forelse($categories as $category)
                @foreach($category->menuItems as $item)
                    <div class="food-card" data-category="{{ $category->slug }}">
                        <div class="food-card__img-wrap">
                            <img
                                src="{{ str_starts_with((string) $item->image_url, 'http') ? $item->image_url : asset(ltrim((string) $item->image_url, '/')) }}"
                                alt="{{ $item->name }}"
                                class="food-card__img"
                                loading="lazy"
                            >
                        </div>
                        <div class="food-card__body">
                            <span class="food-card__price">$ {{ number_format((float) $item->price, 2) }}</span>
                            <h3 class="food-card__name">{{ $item->name }}</h3>
                            <p class="food-card__desc">{{ $item->description }}</p>
                            <div class="food-card__actions">
                                <button class="btn btn--primary food-card__btn-order" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ (float) $item->price }}">
                                    <i class="fas fa-bolt"></i> Order Now
                                </button>
                                <button class="btn btn--cart food-card__btn-cart" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ (float) $item->price }}" aria-label="Add {{ $item->name }} to cart">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @empty
                <p>No categories yet.</p>
            @endforelse
        </div>

        <div class="menu-empty" id="menu-empty" style="display:none;">
            <i class="fas fa-plate-wheat"></i>
            <p>No items found in this category.</p>
        </div>
    </div>
</section>

<div class="cart-toast" id="cart-toast" role="alert" aria-live="polite">
    <i class="fas fa-check-circle"></i>
    <span id="cart-toast-msg">Item added to cart!</span>
</div>

<div class="cart-badge" id="cart-badge" style="display:none;" onclick="window.location.href='{{ route('cart.index') }}'">
    <i class="fas fa-shopping-cart"></i>
    <span id="cart-count">0</span>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const urlParams = new URLSearchParams(window.location.search);
    const initialFilter = urlParams.get('category') || 'all';
    let cart = JSON.parse(sessionStorage.getItem('foodie_cart') || '[]');

    function saveCart() {
        sessionStorage.setItem('foodie_cart', JSON.stringify(cart));
    }

    function getTotalQty() {
        return cart.reduce(function (sum, item) { return sum + item.qty; }, 0);
    }

    function updateBadge() {
        const badge = document.getElementById('cart-badge');
        const count = document.getElementById('cart-count');
        const qty = getTotalQty();
        if (!badge || !count) return;
        count.textContent = qty;
        badge.style.display = qty > 0 ? 'flex' : 'none';
    }

    function showToast(msg) {
        const toast = document.getElementById('cart-toast');
        const toastMsg = document.getElementById('cart-toast-msg');
        if (!toast || !toastMsg) return;
        toastMsg.textContent = msg;
        toast.classList.add('cart-toast--show');
        clearTimeout(toast._timer);
        toast._timer = setTimeout(function () {
            toast.classList.remove('cart-toast--show');
        }, 2800);
    }

    function addToCart(id, name, price) {
        const existing = cart.find(function (item) { return item.id === id; });
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({ id: id, name: name, price: price, qty: 1 });
        }
        saveCart();
        updateBadge();
        showToast('"' + name + '" added to cart');
    }

    const filterBtns = document.querySelectorAll('.menu-filter__btn');
    const cards = document.querySelectorAll('.food-card');
    const emptyState = document.getElementById('menu-empty');

    function applyFilter(filter) {
        let visible = 0;
        cards.forEach(function (card) {
            const match = filter === 'all' || card.dataset.category === filter;
            card.style.display = match ? '' : 'none';
            if (match) visible += 1;
        });
        if (emptyState) {
            emptyState.style.display = visible === 0 ? 'block' : 'none';
        }
    }

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterBtns.forEach(function (item) {
                item.classList.remove('menu-filter__btn--active');
                item.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('menu-filter__btn--active');
            btn.setAttribute('aria-selected', 'true');
            applyFilter(btn.dataset.filter);
        });
    });

    document.querySelectorAll('.food-card__btn-cart').forEach(function (btn) {
        btn.addEventListener('click', function () {
            addToCart(parseInt(btn.dataset.id, 10), btn.dataset.name, parseFloat(btn.dataset.price));
            btn.classList.add('btn--cart-pulse');
            setTimeout(function () { btn.classList.remove('btn--cart-pulse'); }, 400);
        });
    });

    document.querySelectorAll('.food-card__btn-order').forEach(function (btn) {
        btn.addEventListener('click', function () {
            addToCart(parseInt(btn.dataset.id, 10), btn.dataset.name, parseFloat(btn.dataset.price));
            window.location.href = '{{ route('checkout.show') }}';
        });
    });

    const initialBtn = Array.from(filterBtns).find(function (btn) { return btn.dataset.filter === initialFilter; });
    if (initialBtn) {
        initialBtn.click();
    } else {
        applyFilter('all');
    }
    updateBadge();
}());
</script>
@endpush
