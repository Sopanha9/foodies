<?php
// admin/index.php
// require_once 'includes/auth.php';
require_once '../includes/db.php';
require_once 'includes/header.php';

// Fetch quick stats
$stats = [];

// Total Orders
$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$stats['total_orders'] = $stmt->fetchColumn();

// Pending Orders
$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'");
$stats['pending_orders'] = $stmt->fetchColumn();

// Total Income
$stmt = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE payment_status = 'paid'");
$stats['total_income'] = $stmt->fetchColumn() ?: 0.00;

// Total Menu Items
$stmt = $pdo->query("SELECT COUNT(*) FROM menu_items");
$stats['menu_items'] = $stmt->fetchColumn();

// Recent Orders
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
$recent_orders = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <h2 class="card-text"><?= $stats['total_orders'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Pending Orders</h5>
                <h2 class="card-text"><?= $stats['pending_orders'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Income</h5>
                <h2 class="card-text">$<?= number_format($stats['total_income'], 2) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Menu Items</h5>
                <h2 class="card-text"><?= $stats['menu_items'] ?></h2>
            </div>
        </div>
    </div>
</div>

<h3>Recent Orders</h3>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($recent_orders as $order): ?>
            <tr>
                <td>#<?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                <td>$<?= number_format($order['total_amount'], 2) ?></td>
                <td>
                    <span class="badge bg-<?= $order['payment_status'] == 'paid' ? 'success' : ($order['payment_status'] == 'failed' ? 'danger' : 'warning') ?>">
                        <?= ucfirst($order['payment_status']) ?>
                    </span>
                </td>
                <td>
                    <span class="badge bg-<?= $order['order_status'] == 'delivered' ? 'success' : ($order['order_status'] == 'cancelled' ? 'danger' : 'secondary') ?>">
                        <?= ucfirst($order['order_status']) ?>
                    </span>
                </td>
                <td><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($recent_orders)): ?>
            <tr>
                <td colspan="6" class="text-center">No orders found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
