<?php
// admin/edit_food.php
require_once 'includes/auth.php';
require_once '../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header("Location: menu.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $image_url_post = trim($_POST['image_url'] ?? '');
    $current_image = $_POST['current_image'] ?? '';
    
    $image_url = $current_image; // Default to existing

    if (empty($name) || empty($category_id) || empty($price)) {
        $error = 'Name, category, and price are required.';
    } else {
        // Handle File Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $_FILES['image']['tmp_name']);
            finfo_close($fileInfo);

            if (in_array($mimeType, $allowedTypes)) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('food_') . '.' . $ext;
                $destination = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $image_url = '/uploads/' . $filename;
                    
                    // Optionally delete old local image
                    if (!empty($current_image) && strpos($current_image, 'http') !== 0) {
                        $oldFile = '../' . ltrim($current_image, '/');
                        if(file_exists($oldFile)){ unlink($oldFile); }
                    }
                } else {
                    $error = 'Failed to move uploaded file.';
                }
            } else {
                $error = 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.';
            }
        } elseif (!empty($image_url_post) && $image_url_post !== $current_image) {
            $image_url = $image_url_post;
             // Optionally delete old local image
             if (!empty($current_image) && strpos($current_image, 'http') !== 0) {
                $oldFile = '../' . ltrim($current_image, '/');
                if(file_exists($oldFile)){ unlink($oldFile); }
            }
        }

        if (empty($error)) {
            $stmt = $pdo->prepare("UPDATE menu_items SET category_id=?, name=?, price=?, description=?, image_url=? WHERE id=?");
            if ($stmt->execute([$category_id, $name, $price, $description, $image_url, $id])) {
                header("Location: menu.php");
                exit;
            } else {
                $error = 'Failed to update item in database.';
            }
        }
    }
}

// Fetch current item data
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    header("Location: menu.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Food Item: <?= htmlspecialchars($item['name']) ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="menu.php" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Menu
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST" action="edit_food.php?id=<?= $id ?>" enctype="multipart/form-data">
                    <input type="hidden" name="current_image" value="<?= htmlspecialchars($item['image_url']) ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Food Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Category *</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $item['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price ($) *</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="<?= $item['price'] ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($item['description']) ?></textarea>
                    </div>
                    
                    <?php if($item['image_url']): ?>
                    <div class="mb-3">
                        <p>Current Image:</p>
                        <img src="<?= htmlspecialchars(strpos($item['image_url'], 'http') === 0 ? $item['image_url'] : '../' . ltrim($item['image_url'], '/')) ?>" alt="Current image" style="max-width: 200px; border-radius: 8px;">
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="image" class="form-label">Upload New Image (Leaves current if empty)</label>
                        <input class="form-control" type="file" id="image" name="image" accept="image/*">
                    </div>
                    
                    <div class="mb-4">
                        <label for="image_url" class="form-label">Or new Image URL</label>
                        <input type="url" class="form-control" id="image_url" name="image_url" value="<?= strpos($item['image_url'], 'http') === 0 ? htmlspecialchars($item['image_url']) : '' ?>">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Food Item</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
