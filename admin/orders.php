<?php
// admin/orders.php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Handle Order Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['order_status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, payment_status = ? WHERE id = ?");
    $stmt->execute([$_POST['order_status'], $_POST['payment_status'], $_POST['order_id']]);
    header("Location: orders.php");
    exit;
}

// Fetch Orders
$query = "SELECT * FROM orders ORDER BY created_at DESC";
$orders = $pdo->query($query)->fetchAll();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Order Management</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm align-middle">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Contact</th>
                <th>Amount</th>
                <th>Payment</th>
                <th>Reference</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $order): ?>
            <tr>
                <td>#<?= $order['id'] ?></td>
                <td>
                    <?= htmlspecialchars($order['customer_name']) ?><br>
                    <small class="text-muted"><?= htmlspecialchars($order['customer_address']) ?></small>
                </td>
                <td>
                    <?= htmlspecialchars($order['customer_phone']) ?><br>
                    <small><?= htmlspecialchars($order['customer_email']) ?></small>
                </td>
                <td>$<?= number_format($order['total_amount'], 2) ?></td>
                <td>
                    <span class="badge bg-<?= $order['payment_status'] == 'paid' ? 'success' : ($order['payment_status'] == 'failed' ? 'danger' : 'warning') ?>">
                        <?= ucfirst($order['payment_status']) ?>
                    </span><br>
                    <small><?= htmlspecialchars($order['payment_method']) ?></small>
                </td>
                <td>
                    <?php if (!empty($order['transaction_reference'] ?? '')): ?>
                        <small class="text-break"><?= htmlspecialchars($order['transaction_reference']) ?></small>
                    <?php else: ?>
                        <small class="text-muted">-</small>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="badge bg-<?=
                        $order['order_status'] == 'delivered' ? 'success' :
                        ($order['order_status'] == 'cancelled' ? 'danger' :
                        ($order['order_status'] == 'ready' ? 'info' :
                        ($order['order_status'] == 'preparing' ? 'primary' : 'secondary')))
                    ?>">
                        <?= ucfirst($order['order_status']) ?>
                    </span>
                </td>
                <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderModal<?= $order['id'] ?>">
                        Manage
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="orderModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="orders.php" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Manage Order #<?= $order['id'] ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Payment Status</label>
                                        <select name="payment_status" class="form-select">
                                            <option value="pending" <?= $order['payment_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="paid" <?= $order['payment_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                                            <option value="failed" <?= $order['payment_status'] == 'failed' ? 'selected' : '' ?>>Failed</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Transaction Reference</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            value="<?= htmlspecialchars($order['transaction_reference'] ?? 'N/A') ?>"
                                            readonly
                                        >
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Order Status</label>
                                        <select name="order_status" class="form-select">
                                            <option value="pending" <?= $order['order_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="preparing" <?= $order['order_status'] == 'preparing' ? 'selected' : '' ?>>Preparing</option>
                                            <option value="ready" <?= $order['order_status'] == 'ready' ? 'selected' : '' ?>>Ready</option>
                                            <option value="delivered" <?= $order['order_status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                            <option value="cancelled" <?= $order['order_status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                    </div>

                                    <!-- Embedded Items List -->
                                    <h6>Items Ordered</h6>
                                    <ul class="list-group list-group-flush mb-3">
                                        <?php
                                        $items_query = $pdo->prepare("
                                            SELECT oi.*, m.name
                                            FROM order_items oi
                                            JOIN menu_items m ON oi.menu_item_id = m.id
                                            WHERE oi.order_id = ?
                                        ");
                                        $items_query->execute([$order['id']]);
                                        $items = $items_query->fetchAll();
                                        foreach($items as $oi):
                                        ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <?= htmlspecialchars($oi['name']) ?> (x<?= $oi['quantity'] ?>)
                                            <span>$<?= number_format($oi['quantity'] * $oi['price_at_time'], 2) ?></span>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($orders)): ?>
            <tr>
                <td colspan="9" class="text-center">No orders found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
