<?php
require_once 'includes/i18n.php';
require_once 'includes/db.php';
require_once 'includes/customer_auth.php';

if (customer_is_logged_in()) {
    header('Location: ' . localized_url('checkout.php'));
    exit;
}

$page_title = t('customer_login_page_title', 'Customer Login') . ' - Foodie Lab';
$error = '';

$next_path = trim((string)($_GET['next'] ?? $_POST['next'] ?? 'checkout.php'));
$next_path = basename($next_path);
$allowed_next = ['checkout.php', 'cart.php', 'index.php', 'menu.php'];
if (!in_array($next_path, $allowed_next, true)) {
    $next_path = 'checkout.php';
}

$email = trim((string)($_POST['email'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string)($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = t('customer_auth_fill_required', 'Please fill in email and password.');
    } else {
        $stmt = $pdo->prepare('SELECT id, full_name, email, password_hash, phone, default_address, is_active FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = t('customer_auth_invalid_login', 'Invalid email or password.');
        } elseif (!(int)$user['is_active']) {
            $error = t('customer_auth_disabled', 'Your account is disabled. Please contact support.');
        } else {
            customer_login_user($user);
            $update = $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?');
            $update->execute([(int)$user['id']]);
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
            <h1 class="checkout-form__title"><?= htmlspecialchars(t('customer_login_title', 'Sign in to checkout')) ?></h1>
            <p class="checkout-item__meta" style="margin-bottom:14px;"><?= htmlspecialchars(t('customer_login_subtitle', 'Use your account to place and track orders.')) ?></p>

            <?php if ($error): ?>
                <div class="checkout-alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars(localized_url('customer_login.php')) ?>">
                <input type="hidden" name="next" value="<?= htmlspecialchars($next_path) ?>">

                <div class="form-group">
                    <label for="email"><?= htmlspecialchars(t('checkout_email', 'Email Address')) ?></label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="form-group">
                    <label for="password"><?= htmlspecialchars(t('customer_password', 'Password')) ?></label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn--primary checkout-form__submit"><?= htmlspecialchars(t('customer_login_submit', 'Sign In')) ?></button>
            </form>

            <p class="checkout-item__meta" style="margin-top:14px;">
                <?= htmlspecialchars(t('customer_need_account', 'New customer?')) ?>
                <a href="<?= htmlspecialchars(localized_url('customer_register.php', ['next' => $next_path])) ?>"><?= htmlspecialchars(t('customer_register_link', 'Create an account')) ?></a>
            </p>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
