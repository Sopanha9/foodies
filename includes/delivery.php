<section class="delivery">
    <div class="delivery__container">
        <div class="delivery__images">
            <div class="delivery__img-large-wrapper">
                <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Chef preparing food" class="delivery__img-large">
            </div>
            <div class="delivery__img-small-wrapper">
                <img src="https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Food dish 1" class="delivery__img-small">
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Food dish 2" class="delivery__img-small">
            </div>
        </div>

        <div class="delivery__content">
            <h2 class="delivery__title"><?= t('delivery_title', 'Fast delivery,<br>zero ordering stress') ?></h2>

            <p class="delivery__text">
                <?= htmlspecialchars(t('delivery_text', 'Pick dishes, confirm your details, and place your order in a few taps. Our kitchen and riders are optimized for peak student hours so your meal lands while it is still fresh.')) ?>
            </p>

            <ul class="delivery__features">
                <li>
                    <span class="delivery__icon"><i class="fa-regular fa-clock"></i></span>
                    <span><?= htmlspecialchars(t('delivery_feature_1', 'Typical delivery time around 30 minutes')) ?></span>
                </li>
                <li>
                    <span class="delivery__icon"><i class="fa-solid fa-receipt"></i></span>
                    <span><?= htmlspecialchars(t('delivery_feature_2', 'Student-friendly pricing and daily promotions')) ?></span>
                </li>
                <li>
                    <span class="delivery__icon"><i class="fa-solid fa-cart-shopping"></i></span>
                    <span><?= htmlspecialchars(t('delivery_feature_3', 'Smooth online ordering from menu to checkout')) ?></span>
                </li>
            </ul>
        </div>
    </div>
</section>
