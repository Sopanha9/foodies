<?php
// menu.php - Full menu page
require_once 'includes/i18n.php';
$page_title = t('nav_menu', 'Menu') . ' - Foodie Lab';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse affordable breakfast, mains, drinks, and desserts built for fast student ordering.">
    <title><?= htmlspecialchars($page_title) ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
</head>
<body>

    <header>
        <?php include 'includes/navbar.php'; ?>
    </header>

    <main>
        <?php include 'includes/menu_list.php'; ?>
    </main>

    <!-- Navbar scroll script -->
    <script>
    (function () {
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function () {
            navbar.classList.toggle('navbar--scrolled', window.scrollY > 10);
        }, { passive: true });

        const hamburger = document.getElementById('hamburger');
        const navLinks  = document.getElementById('nav-links');
        hamburger.addEventListener('click', function () {
            const isOpen = navLinks.classList.toggle('is-open');
            hamburger.classList.toggle('is-open', isOpen);
            hamburger.setAttribute('aria-expanded', isOpen);
        });
        document.addEventListener('click', function (e) {
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
