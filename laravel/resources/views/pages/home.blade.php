@extends('layouts.app')

@section('content')
<section class="hero" id="hero">
    <div class="hero__panel hero__panel--left">
        <img src="https://images.unsplash.com/photo-1498579150354-977475b7ea0b?w=600&q=80" alt="Fresh vegetables on a cutting board" loading="eager">
    </div>

    <div class="hero__panel hero__panel--right">
        <img src="https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=600&q=80" alt="Loaded fries in a bowl" loading="eager">
    </div>

    <div class="hero__content">
        <span class="hero__eyebrow">Built for quick orders and low prices</span>
        <h1 class="hero__title">Budget bites for<br>busy student days</h1>
        <p class="hero__subtitle">Stack your cart in seconds, grab campus-ready combos, and checkout fast when you are hungry between classes.</p>
        <div class="hero__actions">
            <a href="{{ route('menu.index') }}" class="btn btn--primary">Start Ordering</a>
            <a href="{{ route('menu.index') }}" class="btn btn--outline-white">View Full Menu</a>
        </div>

        <ul class="hero__meta" aria-label="Quick highlights">
            <li><strong>From $4.90</strong> Student meal bundles</li>
            <li><strong>~30 min</strong> Typical city delivery window</li>
            <li><strong>4.8/5</strong> Loved by campus regulars</li>
        </ul>
    </div>
</section>

<section class="browse-menu" id="browse-menu">
    <div class="browse-menu__container">
        <h2 class="browse-menu__heading">Browse by craving</h2>
        <p class="browse-menu__lead">Start with your mood, then build the fastest order flow from menu to checkout.</p>

        <div class="browse-menu__grid">
            @foreach($categories as $category)
                <div class="menu-card">
                    <div class="menu-card__icon-wrap"><i class="fas fa-bowl-food menu-card__icon"></i></div>
                    <h3 class="menu-card__title">{{ $category->name }}</h3>
                    <p class="menu-card__desc">Explore top picks in {{ strtolower($category->name) }}.</p>
                    <a href="{{ route('menu.index') }}?category={{ urlencode($category->slug) }}" class="menu-card__link">See picks</a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="about">
    <div class="about__container">
        <div class="about__image-area">
            <img src="https://images.unsplash.com/photo-1626844131082-256783844137?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Healthy wraps and food" class="about__img">
            <div class="about__contact-card">
                <h3 class="about__contact-title">Come and visit us</h3>
                <ul class="about__contact-list">
                    <li><i class="fa-solid fa-phone-alt"></i><span>+855 88 64 404 83</span></li>
                    <li><i class="fa-regular fa-envelope"></i><span>foodiepoppy@gmail.com</span></li>
                    <li><i class="fa-solid fa-location-dot"></i><span>Phnom Penh<br>Kob srov</span></li>
                </ul>
            </div>
        </div>
        <div class="about__content">
            <h2 class="about__title">Designed for students,<br>made by food lovers.</h2>
            <p class="about__text">Foodie Lab started with one mission: make daily meals affordable, tasty, and fast enough for packed schedules.</p>
            <p class="about__text">Every recipe is tested for flavor, value, and delivery performance, so your order still arrives hot and satisfying.</p>
            <a href="{{ route('menu.index') }}" class="btn btn--outline-dark about__btn">Explore affordable meals</a>
        </div>
    </div>
</section>

<section class="menu-section" id="menu-section">
    <div class="menu-section__container">
        <div class="menu-section__header">
            <h1 class="menu-section__title">Popular picks right now</h1>
            <p class="menu-section__subtitle">Filter by category, add items quickly, and build your order in under a minute.</p>
        </div>

        <div class="menu-grid" id="menu-grid">
            @forelse($featuredItems as $item)
                <div class="food-card" data-category="{{ $item->category->slug ?? '' }}">
                    <div class="food-card__img-wrap">
                        <img src="{{ str_starts_with((string) $item->image_url, 'http') ? $item->image_url : asset(ltrim((string) $item->image_url, '/')) }}" alt="{{ $item->name }}" class="food-card__img" loading="lazy">
                    </div>
                    <div class="food-card__body">
                        <span class="food-card__price">$ {{ number_format((float) $item->price, 2) }}</span>
                        <h3 class="food-card__name">{{ $item->name }}</h3>
                        <p class="food-card__desc">{{ $item->description }}</p>
                        <div class="food-card__actions">
                            <button class="btn btn--primary food-card__btn-order" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ (float) $item->price }}"><i class="fas fa-bolt"></i> Order Now</button>
                            <button class="btn btn--cart food-card__btn-cart" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ (float) $item->price }}" aria-label="Add {{ $item->name }} to cart"><i class="fas fa-cart-plus"></i></button>
                        </div>
                    </div>
                </div>
            @empty
                <p>No menu items found yet. Run migrations and seeders to load dishes.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="delivery" id="delivery">
    <div class="delivery__container">
        <div class="delivery__images">
            <div class="delivery__img-large-wrapper">
                <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Chef preparing food" class="delivery__img-large">
            </div>
            <div class="delivery__img-small-wrapper">
                <img src="https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Food dish 1" class="delivery__img-small">
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Food dish 2" class="delivery__img-small">
            </div>
        </div>
        <div class="delivery__content">
            <h2 class="delivery__title">Fast delivery,<br>zero ordering stress</h2>
            <p class="delivery__text">Pick dishes, confirm your details, and place your order in a few taps.</p>
            <ul class="delivery__features">
                <li><span class="delivery__icon"><i class="fa-regular fa-clock"></i></span><span>Typical delivery time around 30 minutes</span></li>
                <li><span class="delivery__icon"><i class="fa-solid fa-receipt"></i></span><span>Student-friendly pricing and daily promotions</span></li>
                <li><span class="delivery__icon"><i class="fa-solid fa-cart-shopping"></i></span><span>Smooth online ordering from menu to checkout</span></li>
            </ul>
        </div>
    </div>
</section>

<section class="testimonials" id="testimonials">
    <div class="testimonials__container">
        <h2 class="testimonials__title">What students keep saying</h2>
        <div class="testimonials__grid">
            <div class="testimonial-card">
                <h3 class="testimonial-card__title">"Best value near campus"</h3>
                <p class="testimonial-card__text">I can place an order between classes and pick it up fast. The combo pricing is why my friends and I keep coming back.</p>
            </div>
            <div class="testimonial-card">
                <h3 class="testimonial-card__title">"Checkout is super quick"</h3>
                <p class="testimonial-card__text">The site is easy to use on my phone and the delivery window is reliable for late study nights.</p>
            </div>
            <div class="testimonial-card">
                <h3 class="testimonial-card__title">"Budget friendly and tasty"</h3>
                <p class="testimonial-card__text">Portions are good, flavors are consistent, and prices make sense for students.</p>
            </div>
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
        }, 2500);
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

    updateBadge();
}());
</script>
@endpush
