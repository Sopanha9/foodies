<?php
require_once 'includes/i18n.php';
require_once 'includes/db.php';
require_once 'includes/customer_auth.php';

if (customer_is_logged_in()) {
    header('Location: ' . localized_url('checkout.php'));
    exit;
}

$page_title = t('customer_register_page_title', 'Customer Register') . ' - Foodie Lab';
$error = '';

$next_path = trim((string)($_GET['next'] ?? $_POST['next'] ?? 'checkout.php'));
$next_path = basename($next_path);
$allowed_next = ['checkout.php', 'cart.php', 'index.php', 'menu.php'];
if (!in_array($next_path, $allowed_next, true)) {
    $next_path = 'checkout.php';
}

$full_name = trim((string)($_POST['full_name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$default_address = trim((string)($_POST['default_address'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string)($_POST['password'] ?? '');
    $confirm_password = (string)($_POST['confirm_password'] ?? '');

    if ($full_name === '' || $email === '' || $password === '') {
        $error = t('customer_auth_required_fields', 'Please fill all required fields.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = t('customer_auth_invalid_email', 'Please enter a valid email address.');
    } elseif (strlen($password) < 8) {
        $error = t('customer_auth_password_length', 'Password must be at least 8 characters long.');
    } elseif ($password !== $confirm_password) {
        $error = t('customer_auth_password_mismatch', 'Passwords do not match.');
    } else {
        $exists = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $exists->execute([$email]);

        if ($exists->fetch()) {
            $error = t('customer_auth_email_taken', 'This email is already registered.');
        } else {
            $insert = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, phone, default_address) VALUES (?, ?, ?, ?, ?)');
            $insert->execute([
                $full_name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $phone !== '' ? $phone : null,
                $default_address !== '' ? $default_address : null,
            ]);

            $user_id = (int)$pdo->lastInsertId();
            customer_login_user([
                'id' => $user_id,
                'full_name' => $full_name,
                'email' => $email,
                'phone' => $phone,
                'default_address' => $default_address,
            ]);

            $update = $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?');
            $update->execute([$user_id]);

            header('Location: ' . localized_url($next_path));
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
</head>
<body>
    <header>
        <?php include 'includes/navbar.php'; ?>
    </header>

    <main class="checkout-section" style="max-width:760px; margin-inline:auto;">
        <div class="checkout-form" style="flex:1;">
            <h1 class="checkout-form__title"><?= htmlspecialchars(t('customer_register_title', 'Create your account')) ?></h1>
            <p class="checkout-item__meta" style="margin-bottom:14px;"><?= htmlspecialchars(t('customer_register_subtitle', 'Register once to checkout faster next time.')) ?></p>

            <?php if ($error): ?>
                <div class="checkout-alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars(localized_url('customer_register.php')) ?>">
                <input type="hidden" name="next" value="<?= htmlspecialchars($next_path) ?>">

                <div class="form-group">
                    <label for="full_name"><?= htmlspecialchars(t('checkout_full_name', 'Full Name *')) ?></label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="<?= htmlspecialchars($full_name) ?>" required>
                </div>

                <div class="checkout-grid">
                    <div class="form-group">
                        <label for="email"><?= htmlspecialchars(t('checkout_email', 'Email Address')) ?> *</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone"><?= htmlspecialchars(t('checkout_phone', 'Phone Number *')) ?></label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="default_address"><?= htmlspecialchars(t('checkout_address', 'Delivery Address *')) ?></label>
                    <textarea id="default_address" name="default_address" class="form-control" rows="3"><?= htmlspecialchars($default_address) ?></textarea>
                </div>

                <div class="checkout-grid">
                    <div class="form-group">
                        <label for="password"><?= htmlspecialchars(t('customer_password', 'Password')) ?></label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password"><?= htmlspecialchars(t('customer_confirm_password', 'Confirm Password')) ?></label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="8">
                    </div>
                </div>

                <button type="submit" class="btn btn--primary checkout-form__submit"><?= htmlspecialchars(t('customer_register_submit', 'Create Account')) ?></button>
            </form>

            <p class="checkout-item__meta" style="margin-top:14px;">
                <?= htmlspecialchars(t('customer_have_account', 'Already have an account?')) ?>
                <a href="<?= htmlspecialchars(localized_url('customer_login.php', ['next' => $next_path])) ?>"><?= htmlspecialchars(t('customer_login_link', 'Sign in')) ?></a>
            </p>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
