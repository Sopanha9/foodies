<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Foodies Laravel' }}</title>
</head>
<body style="font-family: ui-sans-serif, system-ui; margin: 0; background: #f7f5ef; color: #1e1b16;">
<header style="padding: 16px 20px; border-bottom: 1px solid #ddd3c5; background: #efe6d8;">
    <nav style="display: flex; gap: 14px; flex-wrap: wrap;">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('menu.index') }}">Menu</a>
        <a href="{{ route('cart.index') }}">Cart</a>
        <a href="{{ route('checkout.show') }}">Checkout</a>
        <a href="{{ route('auth.login') }}">Login</a>
        <a href="{{ route('auth.register') }}">Register</a>
        <a href="{{ route('admin.dashboard') }}">Admin</a>
    </nav>
</header>
<main style="max-width: 1080px; margin: 24px auto; padding: 0 16px;">
    @yield('content')
</main>
</body>
</html>
