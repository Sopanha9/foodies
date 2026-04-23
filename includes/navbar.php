<?php
// navbar.php - Reusable navbar component
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_lang = get_current_lang();
require_once __DIR__ . '/customer_auth.php';

$customer = customer_current_user();

$nav_links = [
    ['href' => localized_url('index.php'), 'label' => t('nav_home', 'Home')],
    ['href' => localized_url('menu.php'), 'label' => t('nav_menu', 'Menu')],
    ['href' => localized_url('cart.php'), 'label' => t('nav_cart', 'Cart')],
    ['href' => localized_url('checkout.php'), 'label' => t('nav_checkout', 'Checkout')],
];
?>

<!-- Top Bar -->
<div class="top-bar">
    <div class="top-bar__left">
        <a href="tel:088654083" class="top-bar__contact">
            <i class="fas fa-phone"></i>
            +855 88 64 404 83
        </a>
        <a href="mailto:Panhabestfood@gmail.com" class="top-bar__contact">
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

<!-- Main Navbar -->
<nav class="navbar" id="navbar">
    <div class="navbar__container">

        <!-- Logo -->
        <a href="<?= htmlspecialchars(localized_url('index.php')) ?>" class="navbar__logo">
            <span class="navbar__logo-icon">
                <i class="fas fa-bolt"></i>
            </span>
            <span class="navbar__logo-text">Foodie Lab</span>
        </a>

        <!-- Nav Links -->
        <ul class="navbar__links" id="nav-links">
            <?php foreach ($nav_links as $link):
                $link_path = parse_url($link['href'], PHP_URL_PATH) ?: '';
                $page_name = basename($link_path, '.php');
                $is_active = ($current_page === $page_name);
            ?>
            <li class="navbar__item">
                <a href="<?= htmlspecialchars($link['href']) ?>"
                   class="navbar__link <?= $is_active ? 'navbar__link--active' : '' ?>">
                    <?= htmlspecialchars($link['label']) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="navbar__lang" aria-label="<?= htmlspecialchars(t('nav_language_aria', 'Switch language')) ?>">
            <?php foreach (get_supported_languages() as $code => $label): ?>
                <a
                    href="<?= htmlspecialchars(lang_switch_url($code)) ?>"
                    class="navbar__lang-link <?= $current_lang === $code ? 'navbar__lang-link--active' : '' ?>"
                    title="<?= htmlspecialchars($label) ?>"
                >
                    <?= htmlspecialchars(strtoupper($code)) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="navbar__lang" aria-label="<?= htmlspecialchars(t('customer_account_aria', 'Customer account links')) ?>">
            <?php if ($customer): ?>
                <a href="<?= htmlspecialchars(localized_url('checkout.php')) ?>" class="navbar__lang-link" title="<?= htmlspecialchars($customer['full_name'] ?: $customer['email']) ?>">
                    <?= htmlspecialchars(t('customer_account_nav', 'My Account')) ?>
                </a>
                <a href="<?= htmlspecialchars(localized_url('customer_logout.php')) ?>" class="navbar__lang-link">
                    <?= htmlspecialchars(t('customer_logout_nav', 'Logout')) ?>
                </a>
            <?php else: ?>
                <a href="<?= htmlspecialchars(localized_url('customer_login.php')) ?>" class="navbar__lang-link">
                    <?= htmlspecialchars(t('customer_login_nav', 'Login')) ?>
                </a>
                <a href="<?= htmlspecialchars(localized_url('customer_register.php')) ?>" class="navbar__lang-link">
                    <?= htmlspecialchars(t('customer_register_nav', 'Register')) ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- CTA Button -->
        <a href="<?= htmlspecialchars(localized_url('menu.php')) ?>" class="btn btn--primary navbar__cta"><?= htmlspecialchars(t('nav_order_now', 'Order Now')) ?></a>

        <!-- Mobile Hamburger -->
        <button class="navbar__hamburger" id="hamburger" aria-label="<?= htmlspecialchars(t('nav_toggle_menu', 'Toggle menu')) ?>" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>
</nav>
