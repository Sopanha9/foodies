<?php
// hero.php - Reusable hero section component
// Variables can be passed from individual pages via $hero_title, $hero_subtitle, etc.
$hero_title    = $hero_title    ?? t('hero_title', 'Budget bites for<br>busy student days');
$hero_subtitle = $hero_subtitle ?? t('hero_subtitle', 'Stack your cart in seconds, grab campus-ready combos, and checkout fast when you are hungry between classes.');
$hero_cta_href = $hero_cta_href ?? localized_url('menu.php');
$hero_menu_href = $hero_menu_href ?? localized_url('menu.php');
?>

<section class="hero" id="hero">

    <!-- Left food image panel -->
    <?php
    $left_img  = file_exists(__DIR__ . '/../assets/images/hero-left.jpg')
        ? 'assets/images/hero-left.jpg'
        : 'https://images.unsplash.com/photo-1498579150354-977475b7ea0b?w=600&q=80';
    $right_img = file_exists(__DIR__ . '/../assets/images/hero-right.jpg')
        ? 'assets/images/hero-right.jpg'
        : 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=600&q=80';
    ?>
    <div class="hero__panel hero__panel--left">
        <img src="<?= htmlspecialchars($left_img) ?>"
             alt="Fresh vegetables on a cutting board"
             loading="eager">
    </div>

    <!-- Right food image panel -->
    <div class="hero__panel hero__panel--right">
        <img src="<?= htmlspecialchars($right_img) ?>"
             alt="Loaded fries in a bowl"
             loading="eager">
    </div>

    <!-- Central content -->
    <div class="hero__content">
        <span class="hero__eyebrow"><?= htmlspecialchars(t('hero_eyebrow', 'Built for quick orders and low prices')) ?></span>
        <h1 class="hero__title"><?= $hero_title ?></h1>
        <p class="hero__subtitle"><?= $hero_subtitle ?></p>
        <div class="hero__actions">
            <a href="<?= htmlspecialchars($hero_cta_href) ?>" class="btn btn--primary">
                <?= htmlspecialchars(t('hero_cta_start', 'Start Ordering')) ?>
            </a>
            <a href="<?= htmlspecialchars($hero_menu_href) ?>" class="btn btn--outline-white">
                <?= htmlspecialchars(t('hero_cta_menu', 'View Full Menu')) ?>
            </a>
        </div>

        <ul class="hero__meta" aria-label="Quick highlights">
            <li>
                <strong><?= htmlspecialchars(t('hero_meta_price_title', 'From $4.90')) ?></strong>
                <?= htmlspecialchars(t('hero_meta_price_desc', 'Student meal bundles')) ?>
            </li>
            <li>
                <strong><?= htmlspecialchars(t('hero_meta_time_title', '~30 min')) ?></strong>
                <?= htmlspecialchars(t('hero_meta_time_desc', 'Typical city delivery window')) ?>
            </li>
            <li>
                <strong><?= htmlspecialchars(t('hero_meta_rating_title', '4.8/5')) ?></strong>
                <?= htmlspecialchars(t('hero_meta_rating_desc', 'Loved by campus regulars')) ?>
            </li>
        </ul>
    </div>

</section>
