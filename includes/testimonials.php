<section class="testimonials">
    <div class="testimonials__container">
        <h2 class="testimonials__title"><?= htmlspecialchars(t('testimonials_title', 'What students keep saying')) ?></h2>

        <div class="testimonials__grid">
            <!-- Review 1 -->
            <div class="testimonial-card">
                <h3 class="testimonial-card__title"><?= htmlspecialchars(t('testimonial_1_title', '"Best value near campus"')) ?></h3>
                <p class="testimonial-card__text">
                    <?= htmlspecialchars(t('testimonial_1_text', 'I can place an order between classes and pick it up fast. The combo pricing is honestly the reason my friends and I keep coming back.')) ?>
                </p>
                <div class="testimonial-card__author">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Sophire Robson" class="testimonial-card__avatar">
                    <div class="testimonial-card__info">
                        <span class="testimonial-card__name">Sophire Robson</span>
                        <span class="testimonial-card__location">Los Angeles, CA</span>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="testimonial-card">
                <h3 class="testimonial-card__title"><?= htmlspecialchars(t('testimonial_2_title', '"Checkout is super quick"')) ?></h3>
                <p class="testimonial-card__text">
                    <?= htmlspecialchars(t('testimonial_2_text', 'The site is easy to use on my phone and the delivery window is reliable. Great option when our project group needs dinner during late study nights.')) ?>
                </p>
                <div class="testimonial-card__author">
                    <img src="https://images.unsplash.com/photo-1543610892-0b1f7e6d8ac1?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Matt Cannon" class="testimonial-card__avatar">
                    <div class="testimonial-card__info">
                        <span class="testimonial-card__name">Matt Cannon</span>
                        <span class="testimonial-card__location">San Diego, CA</span>
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="testimonial-card">
                <h3 class="testimonial-card__title"><?= htmlspecialchars(t('testimonial_3_title', '"Budget friendly and tasty"')) ?></h3>
                <p class="testimonial-card__text">
                    <?= htmlspecialchars(t('testimonial_3_text', 'Portions are good, flavors are consistent, and prices make sense for students. I like that I can reorder my favorites without extra steps.')) ?>
                </p>
                <div class="testimonial-card__author">
                    <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Andy Smith" class="testimonial-card__avatar">
                    <div class="testimonial-card__info">
                        <span class="testimonial-card__name">Andy Smith</span>
                        <span class="testimonial-card__location">San Francisco, CA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
