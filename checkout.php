<?php
// checkout.php
require_once 'includes/i18n.php';
$page_title = t('checkout_title', 'Checkout') . ' - Foodie Lab';
require_once 'includes/db.php';
require_once 'includes/customer_auth.php';

$next_login_url = localized_url('customer_login.php', ['next' => 'checkout.php']);
customer_require_login($next_login_url);

$customer = customer_current_user();
$customer_id = (int)($customer['id'] ?? 0);

$error = '';
$success = false;
$wallet_payment_method = 'Mobile Wallet / QR Pay';

$has_transaction_reference = false;
try {
    $column_check = $pdo->query("SHOW COLUMNS FROM orders LIKE 'transaction_reference'");
    $has_transaction_reference = (bool)$column_check->fetch();
} catch (Exception $e) {
    $has_transaction_reference = false;
}

$has_user_id_column = false;
try {
    $column_check = $pdo->query("SHOW COLUMNS FROM orders LIKE 'user_id'");
    $has_user_id_column = (bool)$column_check->fetch();
} catch (Exception $e) {
    $has_user_id_column = false;
}

$payment_methods = ['Cash on Delivery', 'Card on Delivery'];
if ($has_transaction_reference) {
    $payment_methods[] = $wallet_payment_method;
}

$name = trim((string)($customer['full_name'] ?? ''));
$email = trim((string)($customer['email'] ?? ''));
$phone = trim((string)($customer['phone'] ?? ''));
$address = trim((string)($customer['default_address'] ?? ''));
$payment = $payment_methods[0];
$transaction_reference = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_data = json_decode($_POST['cart_data'] ?? '[]', true);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $payment = trim($_POST['payment'] ?? $payment_methods[0]);
    $transaction_reference = trim($_POST['transaction_reference'] ?? '');

    if (empty($cart_data)) {
        $error = t('error_empty_cart', 'Your cart is empty.');
    } elseif (!in_array($payment, $payment_methods, true)) {
        $error = t('error_invalid_payment', 'Please select a valid payment method.');
    } elseif (empty($name) || empty($phone) || empty($address)) {
        $error = t('error_required_fields', 'Please fill all required fields (Name, Phone, Address).');
    } elseif ($payment === $wallet_payment_method && !$has_transaction_reference) {
        $error = t('error_wallet_not_configured', 'Wallet payment is not configured yet. Please update your database or choose another method.');
    } elseif ($payment === $wallet_payment_method && $transaction_reference === '') {
        $error = t('error_wallet_reference_required', 'Please provide your wallet transaction reference.');
    } elseif (strlen($transaction_reference) > 100) {
        $error = t('error_wallet_reference_too_long', 'Transaction reference is too long.');
    } else {
        try {
            $pdo->beginTransaction();

            // Calculate total first and verify items exist
            $total_amount = 0;
            $verified_items = [];

            foreach ($cart_data as $item) {
                $stmt = $pdo->prepare("SELECT id, price FROM menu_items WHERE id = ? AND is_available = 1");
                $stmt->execute([$item['id']]);
                $db_item = $stmt->fetch();

                if ($db_item) {
                    $qty = (int)$item['qty'];
                    $price = (float)$db_item['price'];
                    $total_amount += ($qty * $price);
                    $verified_items[] = [
                        'id' => $db_item['id'],
                        'qty' => $qty,
                        'price' => $price
                    ];
                }
            }

            if (empty($verified_items)) {
                throw new Exception("No valid items found in the cart.");
            }

            $initial_payment_status = 'pending';
            $stored_reference = $payment === $wallet_payment_method ? $transaction_reference : null;

            $profile_update = $pdo->prepare('UPDATE users SET full_name = ?, phone = ?, default_address = ? WHERE id = ?');
            $profile_update->execute([
                $name,
                $phone !== '' ? $phone : null,
                $address !== '' ? $address : null,
                $customer_id,
            ]);

            $_SESSION['customer_user']['full_name'] = $name;
            $_SESSION['customer_user']['phone'] = $phone;
            $_SESSION['customer_user']['default_address'] = $address;

            // Insert Order
            if ($has_transaction_reference && $has_user_id_column) {
                $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, transaction_reference, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$customer_id, $name, $email, $phone, $address, $total_amount, $payment, $stored_reference, $initial_payment_status]);
            } elseif ($has_transaction_reference) {
                $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, transaction_reference, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $address, $total_amount, $payment, $stored_reference, $initial_payment_status]);
            } elseif ($has_user_id_column) {
                $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$customer_id, $name, $email, $phone, $address, $total_amount, $payment, $initial_payment_status]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $address, $total_amount, $payment, $initial_payment_status]);
            }
            $order_id = $pdo->lastInsertId();

            // Insert Order Items
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price_at_time) VALUES (?, ?, ?, ?)");
            foreach ($verified_items as $vi) {
                $stmt->execute([$order_id, $vi['id'], $vi['qty'], $vi['price']]);
            }

            $pdo->commit();
            $success = true;

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = t('error_processing_order', 'An error occurred while processing your order:') . ' ' . $e->getMessage();
        }
    }
}

$payment_method_labels = [
    'Cash on Delivery' => t('payment_cash_on_delivery', 'Cash on Delivery'),
    'Card on Delivery' => t('payment_card_on_delivery', 'Card on Delivery'),
    'Mobile Wallet / QR Pay' => t('payment_wallet_qr', 'Mobile Wallet / QR Pay'),
];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Enter delivery details and place your order with a streamlined checkout experience.">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
</head>
<body>
    <header>
        <?php include 'includes/navbar.php'; ?>
    </header>

    <main class="checkout-section">
        <div class="checkout-form">
            <h1 class="checkout-form__title"><?= htmlspecialchars(t('checkout_title', 'Checkout')) ?></h1>

            <?php if ($success): ?>
                <div class="checkout-success">
                    <h3 class="checkout-success__title"><i class="fas fa-check-circle"></i> <?= htmlspecialchars(t('checkout_success_title', 'Order placed successfully!')) ?></h3>
                    <p><?= htmlspecialchars(t('checkout_success_text', 'Thank you for your order. We will prepare your food right away.')) ?></p>
                    <a href="<?= htmlspecialchars(localized_url('index.php')) ?>" class="btn btn--primary" style="margin-top:16px;"><?= htmlspecialchars(t('checkout_back_home', 'Back to home')) ?></a>
                </div>
                <script>
                    // Clear cart on success
                    sessionStorage.removeItem('foodie_cart');
                </script>
            <?php else: ?>

                <?php if ($error): ?>
                    <div class="checkout-alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="checkout-payment-note" style="margin-bottom:16px;">
                    <p class="checkout-item__meta" style="margin:0;">
                        <?= htmlspecialchars(t('checkout_logged_as', 'Checking out as:')) ?>
                        <strong><?= htmlspecialchars($email) ?></strong>
                    </p>
                </div>

                <form method="POST" action="<?= htmlspecialchars(localized_url('checkout.php')) ?>" id="checkoutForm">
                    <input type="hidden" name="cart_data" id="cartDataInput">

                    <h3 class="checkout-section__title"><?= htmlspecialchars(t('checkout_delivery_title', 'Delivery details')) ?></h3>
                    <div class="form-group">
                        <label for="name"><?= htmlspecialchars(t('checkout_full_name', 'Full Name *')) ?></label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required>
                    </div>

                    <div class="checkout-grid">
                        <div class="form-group">
                            <label for="phone"><?= htmlspecialchars(t('checkout_phone', 'Phone Number *')) ?></label>
                            <input type="tel" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email"><?= htmlspecialchars(t('checkout_email', 'Email Address')) ?></label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address"><?= htmlspecialchars(t('checkout_address', 'Delivery Address *')) ?></label>
                        <textarea id="address" name="address" class="form-control" rows="3" required><?= htmlspecialchars($address) ?></textarea>
                    </div>

                    <h3 class="checkout-section__title"><?= htmlspecialchars(t('checkout_payment_title', 'Payment method')) ?></h3>
                    <div class="form-group">
                        <select id="payment-method" name="payment" class="form-control">
                            <?php foreach ($payment_methods as $method): ?>
                                <option value="<?= htmlspecialchars($method) ?>" <?= $payment === $method ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($payment_method_labels[$method] ?? $method) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php $show_wallet_fields = ($payment === $wallet_payment_method); ?>
                    <div class="checkout-payment-note" id="walletHelp" <?= $show_wallet_fields ? '' : 'hidden' ?>>
                        <p class="checkout-item__meta">
                            <?= htmlspecialchars(t('checkout_wallet_help', 'Pay to our mobile wallet and paste your transaction reference below. The order remains pending until payment is confirmed by admin.')) ?>
                        </p>
                    </div>

                    <div class="form-group" id="walletFields" <?= $show_wallet_fields ? '' : 'hidden' ?>>
                        <label for="transaction_reference"><?= htmlspecialchars(t('checkout_wallet_reference', 'Transaction reference *')) ?></label>
                        <input
                            type="text"
                            id="transaction_reference"
                            name="transaction_reference"
                            class="form-control"
                            maxlength="100"
                            value="<?= htmlspecialchars($transaction_reference) ?>"
                            <?= $show_wallet_fields ? 'required' : '' ?>
                        >
                    </div>

                    <button type="submit" class="btn btn--primary checkout-form__submit"><?= htmlspecialchars(t('checkout_place_order', 'Place order')) ?></button>
                </form>
            <?php endif; ?>
        </div>

        <div class="checkout-summary" <?php if($success) echo 'style="display:none;"'; ?>>
            <h3 class="checkout-summary__title"><?= htmlspecialchars(t('checkout_order_summary', 'Order Summary')) ?></h3>
            <div id="checkoutItemsList">
                <!-- Javascript will populate this -->
            </div>
            <div class="checkout-total">
                <span><?= htmlspecialchars(t('checkout_total', 'Total:')) ?></span>
                <span id="checkoutTotalAmount">$0.00</span>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
    (function(){
        // Check if not success page
        if (document.getElementById('checkoutForm')) {
            const cart = JSON.parse(sessionStorage.getItem('foodie_cart') || '[]');

            if (cart.length === 0) {
                document.getElementById('checkoutItemsList').innerHTML = '<p><?= htmlspecialchars(t('checkout_empty_cart', 'Your cart is empty.')) ?></p>';
            } else {
                document.getElementById('cartDataInput').value = JSON.stringify(cart);

                const list = document.getElementById('checkoutItemsList');
                let total = 0;

                cart.forEach(function (item) {
                    total += (item.price * item.qty);
                    list.innerHTML += `
                        <div class="checkout-item">
                            <div>
                                <strong>${item.name}</strong>
                                <div class="checkout-item__meta">x${item.qty}</div>
                            </div>
                            <div>$${(item.price * item.qty).toFixed(2)}</div>
                        </div>
                    `;
                });

                document.getElementById('checkoutTotalAmount').innerText = '$' + total.toFixed(2);
            }
        }

        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function () {
            navbar.classList.toggle('navbar--scrolled', window.scrollY > 10);
        }, { passive: true });

        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('nav-links');
        if (hamburger && navLinks) {
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
        }

        const paymentMethod = document.getElementById('payment-method');
        const walletHelp = document.getElementById('walletHelp');
        const walletFields = document.getElementById('walletFields');
        const transactionReference = document.getElementById('transaction_reference');

        function toggleWalletFields() {
            if (!paymentMethod || !walletHelp || !walletFields || !transactionReference) {
                return;
            }

            const isWallet = paymentMethod.value === 'Mobile Wallet / QR Pay';
            walletHelp.hidden = !isWallet;
            walletFields.hidden = !isWallet;
            transactionReference.required = isWallet;
        }

        if (paymentMethod) {
            paymentMethod.addEventListener('change', toggleWalletFields);
            toggleWalletFields();
        }
    })();
    </script>
</body>
</html>
