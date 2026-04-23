<?php
// index.php - Homepage
require_once 'includes/i18n.php';
$page_title = 'Foodie Lab - ' . t('hero_eyebrow', 'Fast Budget Meals');
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Affordable and fast meals for students. Browse menu, add to cart, and checkout in seconds.">
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

    <!-- ==========================================
         HEADER (Top Bar + Navbar)
         ========================================== -->
    <header>
        <?php include 'includes/navbar.php'; ?>
    </header>

    <!-- ==========================================
         MAIN CONTENT
         ========================================== -->
    <main>

        <!-- Hero Section -->
        <?php include 'includes/hero.php'; ?>

        <!-- Browse Menu Section -->
        <?php include 'includes/browse_menu.php'; ?>

        <!-- About Section -->
        <?php include 'includes/about.php'; ?>
        <?php include 'includes/menu_list.php'; ?>

        <!-- Delivery Section -->
        <?php include 'includes/delivery.php'?>


        <!-- Testimonials Section -->
        <?php include 'includes/testimonials.php'; ?>

        <!--
            ==========================================
            MORE SECTIONS WILL BE ADDED HERE LATER
            (e.g. About, Testimonials, CTA, Footer)
            ==========================================
        -->

    </main>

    <!-- ==========================================
         FOOTER
         ========================================== -->
    <?php include 'includes/footer.php'; ?>

    <!-- ==========================================
         SCRIPTS
         ========================================== -->
    <script>
    (function () {
        'use strict';

        /* ---- Sticky navbar shadow on scroll ---- */
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('navbar--scrolled', window.scrollY > 10);
        }, { passive: true });

        /* ---- Mobile hamburger toggle ---- */
        const hamburger = document.getElementById('hamburger');
        const navLinks  = document.getElementById('nav-links');

        hamburger.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('is-open');
            hamburger.classList.toggle('is-open', isOpen);
            hamburger.setAttribute('aria-expanded', isOpen);
        });

        /* ---- Close mobile menu on link click ---- */
        navLinks.querySelectorAll('.navbar__link').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('is-open');
                hamburger.classList.remove('is-open');
                hamburger.setAttribute('aria-expanded', 'false');
            });
        });

        /* ---- Close mobile menu on outside click ---- */
        document.addEventListener('click', (e) => {
            if (!navbar.contains(e.target)) {
                navLinks.classList.remove('is-open');
                hamburger.classList.remove('is-open');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        });
    }());
    </script>

</body>
</html>
