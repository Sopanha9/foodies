<?php
// cart.php - Shopping Cart Page
require_once 'includes/i18n.php';
$page_title = t('nav_cart', 'Cart') . ' - Foodie Lab';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Review your selected dishes and proceed to checkout quickly.">
    <title><?= htmlspecialchars($page_title) ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome (icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
</head>
<body>

    <header>
        <?php include 'includes/navbar.php'; ?>
    </header>

    <main class="page-main">
        <section class="cart-section">
            <div class="cart-section__container">
                <h1 class="cart-section__title"><?= htmlspecialchars(t('cart_title', 'Your Cart')) ?></h1>

                <div class="cart-content">
                    <div class="cart-items" id="cart-items-container">
                        <!-- JS injects items here -->
                    </div>

                    <div class="cart-summary" id="cart-summary">
                        <h3 class="cart-summary__title"><?= htmlspecialchars(t('cart_summary_title', 'Order Summary')) ?></h3>
                        <div class="cart-summary__row">
                            <span><?= htmlspecialchars(t('cart_subtotal', 'Subtotal')) ?></span>
                            <span id="cart-subtotal">$0.00</span>
                        </div>
                        <div class="cart-summary__row">
                            <span><?= htmlspecialchars(t('cart_tax', 'Tax (10%)')) ?></span>
                            <span id="cart-tax">$0.00</span>
                        </div>
                        <div class="cart-summary__row cart-summary__total">
                            <span><?= htmlspecialchars(t('cart_total', 'Total')) ?></span>
                            <span id="cart-total">$0.00</span>
                        </div>
                        <a href="<?= htmlspecialchars(localized_url('checkout.php')) ?>" class="btn btn--primary cart-summary__btn"><?= htmlspecialchars(t('cart_proceed', 'Proceed to checkout')) ?></a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Empty state template -->
    <template id="cart-empty-template">
        <div class="cart-empty">
            <i class="fas fa-shopping-basket cart-empty__icon"></i>
            <h2><?= htmlspecialchars(t('cart_empty_title', 'Your cart is empty')) ?></h2>
            <p><?= htmlspecialchars(t('cart_empty_text', "Looks like you haven't added any dishes yet.")) ?></p>
            <a href="<?= htmlspecialchars(localized_url('menu.php')) ?>" class="btn btn--primary"><?= htmlspecialchars(t('cart_empty_cta', 'Browse Menu')) ?></a>
        </div>
    </template>

    <script>
    (function () {
        'use strict';

        let cart = JSON.parse(sessionStorage.getItem('foodie_cart') || '[]');

        const container = document.getElementById('cart-items-container');
        const summary = document.getElementById('cart-summary');
        const emptyTemplate = document.getElementById('cart-empty-template');

        function saveCart() {
            sessionStorage.setItem('foodie_cart', JSON.stringify(cart));
        }

        function renderCart() {
            container.innerHTML = '';

            if (cart.length === 0) {
                container.appendChild(emptyTemplate.content.cloneNode(true));
                summary.style.display = 'none';
                return;
            }

            summary.style.display = 'block';
            let subtotal = 0;

            cart.forEach((item, index) => {
                subtotal += item.price * item.qty;

                const itemEl = document.createElement('div');
                itemEl.className = 'cart-item';
                itemEl.innerHTML = `
                    <div class="cart-item__info">
                        <h4 class="cart-item__name">${item.name}</h4>
                        <div class="cart-item__price">$${item.price.toFixed(2)}</div>
                    </div>
                    <div class="cart-item__actions">
                        <div class="cart-item__qty">
                            <button class="cart-qty-btn cart-qty-minus" data-index="${index}"><i class="fas fa-minus"></i></button>
                            <span>${item.qty}</span>
                            <button class="cart-qty-btn cart-qty-plus" data-index="${index}"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="cart-item__subtotal">$${(item.price * item.qty).toFixed(2)}</div>
                        <button class="cart-item__remove" data-index="${index}" aria-label="<?= htmlspecialchars(t('cart_remove_item', 'Remove item')) ?>"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                container.appendChild(itemEl);
            });

            // Attach Events
            document.querySelectorAll('.cart-qty-plus').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const idx = e.currentTarget.dataset.index;
                    cart[idx].qty += 1;
                    saveCart();
                    renderCart();
                });
            });
            document.querySelectorAll('.cart-qty-minus').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const idx = e.currentTarget.dataset.index;
                    if(cart[idx].qty > 1) {
                        cart[idx].qty -= 1;
                    } else {
                        cart.splice(idx, 1);
                    }
                    saveCart();
                    renderCart();
                });
            });
            document.querySelectorAll('.cart-item__remove').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const idx = e.currentTarget.dataset.index;
                    cart.splice(idx, 1);
                    saveCart();
                    renderCart();
                });
            });

            // Update Summary
            const tax = subtotal * 0.10;
            const total = subtotal + tax;
            document.getElementById('cart-subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('cart-tax').textContent = '$' + tax.toFixed(2);
            document.getElementById('cart-total').textContent = '$' + total.toFixed(2);
        }

        renderCart();

        /* ---- Sticky navbar & mobile toggle ---- */
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('navbar--scrolled', window.scrollY > 10);
        }, { passive: true });

        const hamburger = document.getElementById('hamburger');
        const navLinks  = document.getElementById('nav-links');
        if (hamburger && navLinks) {
            hamburger.addEventListener('click', () => {
                const isOpen = navLinks.classList.toggle('is-open');
                hamburger.classList.toggle('is-open', isOpen);
                hamburger.setAttribute('aria-expanded', isOpen);
            });
        }
    }());
    </script>
</body>
</html>
