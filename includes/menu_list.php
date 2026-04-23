<?php
// includes/menu_list.php
require_once 'includes/db.php';

// Fetch Categories for Tabs
$categories_query = $pdo->query("SELECT * FROM categories ORDER BY name");
$filter_tabs = ['all' => t('menu_tab_all', 'All')];
while ($row = $categories_query->fetch()) {
    $filter_tabs[$row['slug']] = $row['name'];
}

// Fetch Menu Items
$menu_query = $pdo->query("
    SELECT m.*, c.slug as category_slug
    FROM menu_items m
    JOIN categories c ON m.category_id = c.id
    WHERE m.is_available = 1
    ORDER BY c.id, m.name
");
$menu_items = $menu_query->fetchAll();

// Active filter from URL (for PHP-side filtering if needed)
$active_filter = isset($_GET['category']) ? $_GET['category'] : 'all';
?>

<section class="menu-section" id="menu-section">
    <div class="menu-section__container">

        <!-- Heading -->
        <div class="menu-section__header">
            <h1 class="menu-section__title"><?= htmlspecialchars(t('menu_section_title', 'Popular picks right now')) ?></h1>
            <p class="menu-section__subtitle">
                <?= htmlspecialchars(t('menu_section_subtitle', 'Filter by category, add items quickly, and build your order in under a minute.')) ?>
            </p>
        </div>

        <!-- Filter Tabs -->
        <div class="menu-filter" role="tablist" aria-label="<?= htmlspecialchars(t('menu_categories_aria', 'Menu categories')) ?>">
            <?php foreach ($filter_tabs as $key => $label): ?>
            <button
                class="menu-filter__btn <?= $key === $active_filter ? 'menu-filter__btn--active' : '' ?>"
                data-filter="<?= htmlspecialchars($key) ?>"
                role="tab"
                aria-selected="<?= $key === $active_filter ? 'true' : 'false' ?>"
            >
                <?= htmlspecialchars($label) ?>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Cards Grid -->
        <div class="menu-grid" id="menu-grid">
            <?php foreach ($menu_items as $item): ?>
            <div class="food-card" data-category="<?= htmlspecialchars($item['category_slug']) ?>">

                <div class="food-card__img-wrap">
                    <img
                        src="<?= htmlspecialchars(strpos($item['image_url'], 'http') === 0 ? $item['image_url'] : ltrim($item['image_url'], '/')) ?>"
                        alt="<?= htmlspecialchars($item['name']) ?>"
                        class="food-card__img"
                        loading="lazy"
                    >
                </div>

                <div class="food-card__body">
                    <span class="food-card__price">$ <?= number_format($item['price'], 2) ?></span>
                    <h3 class="food-card__name"><?= htmlspecialchars($item['name']) ?></h3>
                    <p class="food-card__desc"><?= htmlspecialchars($item['description']) ?></p>

                    <div class="food-card__actions">
                        <button
                            class="btn btn--primary food-card__btn-order"
                            data-id="<?= (int)$item['id'] ?>"
                            data-name="<?= htmlspecialchars($item['name']) ?>"
                            data-price="<?= (float)$item['price'] ?>"
                        >
                            <i class="fas fa-bolt"></i> <?= htmlspecialchars(t('menu_order_now', 'Order Now')) ?>
                        </button>
                        <button
                            class="btn btn--cart food-card__btn-cart"
                            data-id="<?= (int)$item['id'] ?>"
                            data-name="<?= htmlspecialchars($item['name']) ?>"
                            data-price="<?= (float)$item['price'] ?>"
                            aria-label="<?= htmlspecialchars(t('menu_add_to_cart_aria_prefix', 'Add')) ?> <?= htmlspecialchars($item['name']) ?> <?= htmlspecialchars(t('menu_add_to_cart_aria_suffix', 'to cart')) ?>"
                        >
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty state -->
        <div class="menu-empty" id="menu-empty" style="display:none;">
            <i class="fas fa-plate-wheat"></i>
            <p><?= htmlspecialchars(t('menu_empty', 'No items found in this category.')) ?></p>
        </div>

    </div>
</section>

<!-- Cart Toast Notification -->
<div class="cart-toast" id="cart-toast" role="alert" aria-live="polite">
    <i class="fas fa-check-circle"></i>
    <span id="cart-toast-msg"><?= htmlspecialchars(t('menu_toast_added_full', 'Item added to cart!')) ?></span>
</div>

<!-- Cart Counter Badge (top-right, always visible) -->
<div class="cart-badge" id="cart-badge" style="display:none;" onclick="window.location.href='<?= htmlspecialchars(localized_url('cart.php')) ?>'">
    <i class="fas fa-shopping-cart"></i>
    <span id="cart-count">0</span>
</div>

<script>
(function () {
    'use strict';

    /* =============================================
       CART STATE
       ============================================= */
    let cart = JSON.parse(sessionStorage.getItem('foodie_cart') || '[]');

    function saveCart() {
        sessionStorage.setItem('foodie_cart', JSON.stringify(cart));
    }

    function getTotalQty() {
        return cart.reduce(function (sum, i) { return sum + i.qty; }, 0);
    }

    function updateBadge() {
        const badge = document.getElementById('cart-badge');
        const count = document.getElementById('cart-count');
        const qty   = getTotalQty();
        count.textContent = qty;
        badge.style.display = qty > 0 ? 'flex' : 'none';
    }

    function showToast(msg) {
        const toast = document.getElementById('cart-toast');
        document.getElementById('cart-toast-msg').textContent = msg;
        toast.classList.add('cart-toast--show');
        clearTimeout(toast._timer);
        toast._timer = setTimeout(function () {
            toast.classList.remove('cart-toast--show');
        }, 2800);
    }

    function addToCart(id, name, price) {
        const existing = cart.find(function (i) { return i.id === id; });
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({ id: id, name: name, price: price, qty: 1 });
        }
        saveCart();
        updateBadge();
        showToast('"' + name + '" <?= htmlspecialchars(t('menu_toast_added_tail', 'added to cart')) ?>!');
    }

    /* =============================================
       FILTER TABS
       ============================================= */
    const filterBtns = document.querySelectorAll('.menu-filter__btn');
    const cards      = document.querySelectorAll('.food-card');
    const emptyState = document.getElementById('menu-empty');

    function applyFilter(filter) {
        let visible = 0;
        cards.forEach(function (card) {
            const match = filter === 'all' || card.dataset.category === filter;
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        emptyState.style.display = visible === 0 ? 'block' : 'none';
    }

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterBtns.forEach(function (b) {
                b.classList.remove('menu-filter__btn--active');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('menu-filter__btn--active');
            btn.setAttribute('aria-selected', 'true');
            applyFilter(btn.dataset.filter);
        });
    });

    /* =============================================
       ADD TO CART BUTTONS
       ============================================= */
    document.querySelectorAll('.food-card__btn-cart').forEach(function (btn) {
        btn.addEventListener('click', function () {
            addToCart(
                parseInt(btn.dataset.id),
                btn.dataset.name,
                parseFloat(btn.dataset.price)
            );
            // Brief pulse animation
            btn.classList.add('btn--cart-pulse');
            setTimeout(function () { btn.classList.remove('btn--cart-pulse'); }, 400);
        });
    });

    /* =============================================
       ORDER NOW BUTTONS
       ============================================= */
    document.querySelectorAll('.food-card__btn-order').forEach(function (btn) {
        btn.addEventListener('click', function () {
            addToCart(
                parseInt(btn.dataset.id),
                btn.dataset.name,
                parseFloat(btn.dataset.price)
            );
            // Redirect to checkout
             window.location.href = '<?= htmlspecialchars(localized_url('checkout.php')) ?>';
        });
    });

    /* Init */
    updateBadge();
    applyFilter('<?= htmlspecialchars($active_filter) ?>');
}());
</script>
