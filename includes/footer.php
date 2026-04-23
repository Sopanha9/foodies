<footer class="footer">
    <div class="footer__container">
        <div class="footer__col footer__brand">
            <a href="<?= htmlspecialchars(localized_url('index.php')) ?>" class="footer__logo">
                <i class="fa-solid fa-bolt"></i> Foodie Lab
            </a>
            <p class="footer__desc">
                <?= htmlspecialchars(t('footer_desc', 'Fast meals for busy student life. Affordable combos, smooth checkout, and reliable city delivery.')) ?>
            </p>
            <div class="footer__socials">
                <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
            </div>
        </div>

        <div class="footer__col">
            <h4 class="footer__title"><?= htmlspecialchars(t('footer_order_flow', 'Order Flow')) ?></h4>
            <ul class="footer__links">
                <li><a href="<?= htmlspecialchars(localized_url('index.php')) ?>"><?= htmlspecialchars(t('nav_home', 'Home')) ?></a></li>
                <li><a href="<?= htmlspecialchars(localized_url('menu.php')) ?>"><?= htmlspecialchars(t('nav_menu', 'Menu')) ?></a></li>
                <li><a href="<?= htmlspecialchars(localized_url('cart.php')) ?>"><?= htmlspecialchars(t('nav_cart', 'Cart')) ?></a></li>
                <li><a href="<?= htmlspecialchars(localized_url('checkout.php')) ?>"><?= htmlspecialchars(t('nav_checkout', 'Checkout')) ?></a></li>
                <li><a href="<?= htmlspecialchars(localized_url('index.php#delivery')) ?>">Delivery</a></li>
            </ul>
        </div>

        <div class="footer__col">
            <h4 class="footer__title"><?= htmlspecialchars(t('footer_explore', 'Explore')) ?></h4>
            <ul class="footer__links">
                <li><a href="<?= htmlspecialchars(localized_url('index.php#browse-menu')) ?>"><?= htmlspecialchars(t('footer_browse_categories', 'Browse Categories')) ?></a></li>
                <li><a href="<?= htmlspecialchars(localized_url('index.php#menu-section')) ?>"><?= htmlspecialchars(t('footer_popular_dishes', 'Popular Dishes')) ?></a></li>
                <li><a href="<?= htmlspecialchars(localized_url('index.php#testimonials')) ?>"><?= htmlspecialchars(t('footer_student_reviews', 'Student Reviews')) ?></a></li>
                <li><a href="admin/login.php">Admin Panel</a></li>
            </ul>
        </div>

        <div class="footer__col footer__instagram">
            <h4 class="footer__title"><?= htmlspecialchars(t('footer_mood_board', 'Food Mood Board')) ?></h4>
            <div class="footer__instagram-grid">
                <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Instagram 1" class="footer__inst-img">
                <img src="https://images.unsplash.com/photo-1585647347384-2593bc35786b?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Instagram 2" class="footer__inst-img">
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Instagram 3" class="footer__inst-img">
                <img src="https://images.unsplash.com/photo-1565299507177-b0ac66763828?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Instagram 4" class="footer__inst-img">
            </div>
        </div>
    </div>
</footer>
