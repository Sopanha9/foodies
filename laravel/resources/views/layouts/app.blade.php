<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Affordable and fast meals for students. Browse menu, add to cart, and checkout in seconds.">
    <title>{{ $title ?? 'Foodie Lab' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('head')
</head>
<body>
<header>
    <div class="top-bar">
        <div class="top-bar__left">
            <a href="tel:088654083" class="top-bar__contact">
                <i class="fas fa-phone"></i>
                +855 88 64 404 83
            </a>
            <a href="mailto:foodiepoppy@gmail.com" class="top-bar__contact">
                <i class="fas fa-envelope"></i>
                foodiepoppy@gmail.com
            </a>
        </div>
        <div class="top-bar__right">
            <a href="#" class="top-bar__social" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="top-bar__social" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="top-bar__social" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="top-bar__social" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
        </div>
    </div>

    <nav class="navbar" id="navbar">
        <div class="navbar__container">
            <a href="{{ route('home') }}" class="navbar__logo">
                <span class="navbar__logo-icon"><i class="fas fa-bolt"></i></span>
                <span class="navbar__logo-text">Foodie Lab</span>
            </a>

            <ul class="navbar__links" id="nav-links">
                <li class="navbar__item"><a href="{{ route('home') }}" class="navbar__link {{ request()->routeIs('home') ? 'navbar__link--active' : '' }}">Home</a></li>
                <li class="navbar__item"><a href="{{ route('menu.index') }}" class="navbar__link {{ request()->routeIs('menu.*') ? 'navbar__link--active' : '' }}">Menu</a></li>
                <li class="navbar__item"><a href="{{ route('cart.index') }}" class="navbar__link {{ request()->routeIs('cart.*') ? 'navbar__link--active' : '' }}">Cart</a></li>
                <li class="navbar__item"><a href="{{ route('checkout.show') }}" class="navbar__link {{ request()->routeIs('checkout.*') ? 'navbar__link--active' : '' }}">Checkout</a></li>
            </ul>

            <div class="navbar__lang" aria-label="Customer account links">
                <a href="{{ route('auth.login') }}" class="navbar__lang-link">Login</a>
                <a href="{{ route('auth.register') }}" class="navbar__lang-link">Register</a>
            </div>

            <a href="{{ route('menu.index') }}" class="btn btn--primary navbar__cta">Order Now</a>

            <button class="navbar__hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>
</header>

<main>
    @yield('content')
</main>

<footer class="footer">
    <div class="footer__container">
        <div class="footer__col footer__brand">
            <a href="{{ route('home') }}" class="footer__logo">
                <i class="fa-solid fa-bolt"></i> Foodie Lab
            </a>
            <p class="footer__desc">
                Fast meals for busy student life. Affordable combos, smooth checkout, and reliable city delivery.
            </p>
            <div class="footer__socials">
                <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
            </div>
        </div>

        <div class="footer__col">
            <h4 class="footer__title">Order Flow</h4>
            <ul class="footer__links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('menu.index') }}">Menu</a></li>
                <li><a href="{{ route('cart.index') }}">Cart</a></li>
                <li><a href="{{ route('checkout.show') }}">Checkout</a></li>
                <li><a href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
            </ul>
        </div>

        <div class="footer__col footer__instagram">
            <h4 class="footer__title">Food Mood Board</h4>
            <div class="footer__instagram-grid">
                <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Food 1" class="footer__inst-img">
                <img src="https://images.unsplash.com/photo-1585647347384-2593bc35786b?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Food 2" class="footer__inst-img">
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Food 3" class="footer__inst-img">
                <img src="https://images.unsplash.com/photo-1565299507177-b0ac66763828?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Food 4" class="footer__inst-img">
            </div>
        </div>
    </div>
</footer>

<script>
(function () {
    'use strict';

    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', function () {
        if (navbar) {
            navbar.classList.toggle('navbar--scrolled', window.scrollY > 10);
        }
    }, { passive: true });

    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', function () {
            const isOpen = navLinks.classList.toggle('is-open');
            hamburger.classList.toggle('is-open', isOpen);
            hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (e) {
            if (navbar && !navbar.contains(e.target)) {
                navLinks.classList.remove('is-open');
                hamburger.classList.remove('is-open');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        });
    }
}());
</script>
@stack('scripts')
</body>
</html>
