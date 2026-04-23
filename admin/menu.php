<?php
// admin/menu.php
// require_once 'includes/auth.php';
require_once '../includes/db.php';

// Handle Delete 
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT image_url FROM menu_items WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $item = $stmt->fetch();

    // Optionally delete the file if it's local
    if ($item && !empty($item['image_url']) && strpos($item['image_url'], 'http') !== 0) {
        $filePath = '../' . ltrim($item['image_url'], '/');
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: menu.php");
    exit;
}

// Handle Toggle Availability
if (isset($_GET['toggle_status'])) {
    $stmt = $pdo->prepare("UPDATE menu_items SET is_available = NOT is_available WHERE id = ?");
    $stmt->execute([$_GET['toggle_status']]);
    header("Location: menu.php");
    exit;
}

// Fetch Menu Items with Category Name
$query = "SELECT m.*, c.name as category_name 
          FROM menu_items m 
          LEFT JOIN categories c ON m.category_id = c.id 
          ORDER BY m.id DESC";
$items = $pdo->query($query)->fetchAll();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Menu Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="add_food.php" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Food
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm align-middle">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): ?>
            <tr>
                <td>
                    <?php if($item['image_url']): ?>
                        <img src="<?= htmlspecialchars(strpos($item['image_url'], 'http') === 0 ? $item['image_url'] : '../' . ltrim($item['image_url'], '/')) ?>" alt="Food image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    <?php else: ?>
                        <div style="width: 50px; height: 50px; background: #eee; border-radius: 4px; display:flex; align-items:center; justify-content:center"><i class="fas fa-image text-muted"></i></div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['category_name']) ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td>
                    <a href="menu.php?toggle_status=<?= $item['id'] ?>" class="badge bg-<?= $item['is_available'] ? 'success' : 'danger' ?> text-decoration-none">
                        <?= $item['is_available'] ? 'Available' : 'Unavailable' ?>
                    </a>
                </td>
                <td>
                    <a href="edit_food.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Edit</a>
                    <a href="menu.php?delete=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fas fa-trash"></i> Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($items)): ?>
            <tr>
                <td colspan="6" class="text-center">No menu items found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
