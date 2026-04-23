<section class="about">
    <div class="about__container">
        <div class="about__image-area">
            <img src="https://images.unsplash.com/photo-1626844131082-256783844137?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Healthy wraps and food" class="about__img">

            <div class="about__contact-card">
                <h3 class="about__contact-title"><?= htmlspecialchars(t('about_visit_title', 'Come and visit us')) ?></h3>

                <ul class="about__contact-list">
                    <li>
                        <i class="fa-solid fa-phone-alt"></i>
                        <span>+855 88 64 404 83</span>
                    </li>
                    <li>
                        <i class="fa-regular fa-envelope"></i>
                        <span>foodiepoppy@gmail.com</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Phnom Penh<br>Kob srov</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="about__content">
            <h2 class="about__title"><?= t('about_title', 'Designed for students,<br>made by food lovers.') ?></h2>

            <p class="about__text">
                <?= htmlspecialchars(t('about_text_1', 'Foodie Lab started with one mission: make daily meals affordable, tasty, and fast enough for packed schedules. We build menus around comfort food favorites and balanced plates that do not break the budget.')) ?>
            </p>
            <p class="about__text">
                <?= htmlspecialchars(t('about_text_2', 'Every recipe is tested for flavor, value, and delivery performance, so your order still arrives hot and satisfying. Whether you are grabbing lunch between lectures or ordering dinner for your team, we keep the process simple.')) ?>
            </p>

            <a href="<?= htmlspecialchars(localized_url('menu.php')) ?>" class="btn btn--outline-dark about__btn"><?= htmlspecialchars(t('about_btn', 'Explore affordable meals')) ?></a>
        </div>
    </div>
</section>
