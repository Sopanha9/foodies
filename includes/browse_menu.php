<?php
// includes/browse_menu.php - Browse Our Menu section

$menu_categories = [
    [
        'icon'        => 'fa-mug-hot',
        'title'       => t('browse_breakfast_title', 'Breakfast'),
        'description' => t('browse_breakfast_desc', 'Quick morning wraps, egg bowls, and coffee combos for early classes.'),
        'href'        => localized_url('menu.php', ['category' => 'breakfast']),
    ],
    [
        'icon'        => 'fa-bowl-food',
        'title'       => t('browse_main_title', 'Main Dishes'),
        'description' => t('browse_main_desc', 'Filling rice, noodle, and grilled plates tuned for high-value portions.'),
        'href'        => localized_url('menu.php', ['category' => 'main-dishes']),
    ],
    [
        'icon'        => 'fa-glass-water',
        'title'       => t('browse_drinks_title', 'Drinks'),
        'description' => t('browse_drinks_desc', 'Iced teas, fruit coolers, and energy drinks for long study sessions.'),
        'href'        => localized_url('menu.php', ['category' => 'drinks']),
    ],
    [
        'icon'        => 'fa-cake-candles',
        'title'       => t('browse_desserts_title', 'Desserts'),
        'description' => t('browse_desserts_desc', 'Affordable sweet picks to finish your meal without overpaying.'),
        'href'        => localized_url('menu.php', ['category' => 'desserts']),
    ],
];
?>

<section class="browse-menu" id="browse-menu">
    <div class="browse-menu__container">

        <h2 class="browse-menu__heading"><?= htmlspecialchars(t('browse_heading', 'Browse by craving')) ?></h2>
        <p class="browse-menu__lead"><?= htmlspecialchars(t('browse_lead', 'Start with your mood, then build the fastest order flow from menu to checkout.')) ?></p>

        <div class="browse-menu__grid">
            <?php foreach ($menu_categories as $cat): ?>
            <div class="menu-card">
                <div class="menu-card__icon-wrap">
                    <i class="fas <?= htmlspecialchars($cat['icon']) ?> menu-card__icon"></i>
                </div>
                <h3 class="menu-card__title"><?= htmlspecialchars($cat['title']) ?></h3>
                <p class="menu-card__desc"><?= htmlspecialchars($cat['description']) ?></p>
                <a href="<?= htmlspecialchars($cat['href']) ?>" class="menu-card__link">
                    <?= htmlspecialchars(t('browse_see_picks', 'See picks')) ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
