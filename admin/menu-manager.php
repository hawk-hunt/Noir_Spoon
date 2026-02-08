<?php
require __DIR__ . '/includes/db.php';

// Check if user is logged in (admin)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle menu item creation/update
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        if (!$pdo) {
            $error = 'Database connection required for this operation.';
        } else {
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $category_id = intval($_POST['category_id'] ?? 0);
            $description = trim($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $featured = isset($_POST['featured']) ? 1 : 0;
            $order = intval($_POST['order'] ?? 0);
            
            if (empty($name)) {
                $error = 'Item name is required.';
            } elseif ($category_id <= 0) {
                $error = 'Please select a category.';
            } elseif ($price < 0) {
                $error = 'Price cannot be negative.';
            } else {
                // Handle image upload
                $image_file = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['image'];
                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    
                    if (!in_array($ext, $allowed)) {
                        $error = 'Invalid file type. Allowed: JPG, PNG, WebP';
                    } elseif ($file['size'] > 5 * 1024 * 1024) {
                        $error = 'File size exceeds 5MB limit.';
                    } else {
                        // Get category name for directory
                        $stmt = $pdo->prepare('SELECT name FROM menu_categories WHERE id = ?');
                        $stmt->execute([$category_id]);
                        $cat = $stmt->fetch();
                        $cat_dir = strtolower($cat['name']);
                        
                        // Create unique filename
                        $filename = sanitize_filename($name) . '-' . time() . '.' . $ext;
                        $upload_dir = __DIR__ . '/assets/img/menu/' . $cat_dir . '/';
                        
                        // Create directory if it doesn't exist
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }
                        
                        $filepath = $upload_dir . $filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $filepath)) {
                            $image_file = $cat_dir . '/' . $filename;
                        } else {
                            $error = 'Failed to upload image.';
                        }
                    }
                }
                
                if (empty($error)) {
                    try {
                        if ($action === 'add') {
                            $stmt = $pdo->prepare('
                                INSERT INTO menu_items (name, category_id, description, price, image, featured, "order") 
                                VALUES (?, ?, ?, ?, ?, ?, ?)
                            ');
                            $stmt->execute([$name, $category_id, $description, $price, $image_file, $featured, $order]);
                            $message = 'Menu item added successfully!';
                        } else {
                            $update_fields = ['name' => $name, 'category_id' => $category_id, 'description' => $description, 'price' => $price, 'featured' => $featured, 'order' => $order];
                            if ($image_file) {
                                $update_fields['image'] = $image_file;
                            }
                            
                            $set_clause = implode(', ', array_map(fn($k) => "$k = ?", array_keys($update_fields)));
                            $values = array_values($update_fields);
                            $values[] = $id;
                            
                            $stmt = $pdo->prepare("UPDATE menu_items SET $set_clause WHERE id = ?");
                            $stmt->execute($values);
                            $message = 'Menu item updated successfully!';
                        }
                    } catch (PDOException $e) {
                        $error = 'Database error: ' . $e->getMessage();
                    }
                }
            }
        }
    } elseif ($action === 'delete') {
        if ($pdo) {
            $id = intval($_POST['id'] ?? 0);
            try {
                // Get image path to delete
                $stmt = $pdo->prepare('SELECT image FROM menu_items WHERE id = ?');
                $stmt->execute([$id]);
                $item = $stmt->fetch();
                
                if ($item && $item['image']) {
                    $file_path = __DIR__ . '/assets/img/menu/' . $item['image'];
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
                
                // Delete from database
                $stmt = $pdo->prepare('DELETE FROM menu_items WHERE id = ?');
                $stmt->execute([$id]);
                $message = 'Menu item deleted successfully!';
            } catch (PDOException $e) {
                $error = 'Error deleting item: ' . $e->getMessage();
            }
        }
    }
}

// Get categories
if ($pdo) {
    $stmt = $pdo->query('SELECT * FROM menu_categories ORDER BY "order" ASC');
    $categories = $stmt->fetchAll();
} else {
    $categories = get_mock_menu_categories();
}

// Get menu items
if ($pdo) {
    $stmt = $pdo->query('SELECT mi.*, mc.name AS category FROM menu_items mi LEFT JOIN menu_categories mc ON mi.category_id = mc.id ORDER BY mi.category_id, mi."order", mi.name');
    $items = $stmt->fetchAll();
} else {
    $items = get_mock_menu_items();
}

// Helper function to sanitize filenames
function sanitize_filename($filename) {
    return preg_replace('/[^a-z0-9-]/i', '-', strtolower($filename));
}

include __DIR__ . '/includes/header.php';
?>

<section class="bg-base-100 py-12">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold mb-2">Menu Management</h1>
            <p class="text-base-content/70">Add, edit, or remove menu items</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success mb-6 shadow-lg">
                <span class="icon-[tabler--check] size-5"></span>
                <span><?php echo e($message); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error mb-6 shadow-lg">
                <span class="icon-[tabler--alert-circle] size-5"></span>
                <span><?php echo e($error); ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-1">
                <div class="card bg-base-200 shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-4">Add New Item</h2>
                        
                        <form method="POST" enctype="multipart/form-data" class="space-y-4">
                            <input type="hidden" name="action" value="add">
                            
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Item Name *</span>
                                </label>
                                <input type="text" name="name" placeholder="Entrance item name" class="input input-bordered" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Category *</span>
                                </label>
                                <select name="category_id" class="select select-bordered" required>
                                    <option value="">Select a category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo e($cat['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Description</span>
                                </label>
                                <textarea name="description" placeholder="Brief description" class="textarea textarea-bordered h-20"></textarea>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Price ($) *</span>
                                </label>
                                <input type="number" name="price" step="0.01" placeholder="0.00" class="input input-bordered" required>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Order</span>
                                </label>
                                <input type="number" name="order" value="0" class="input input-bordered">
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Image</span>
                                </label>
                                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="file-input file-input-bordered">
                                <label class="label">
                                    <span class="label-text-alt">JPG, PNG, or WebP (max 5MB)</span>
                                </label>
                            </div>

                            <div class="form-control">
                                <label class="cursor-pointer label">
                                    <span class="label-text font-semibold">Featured Item</span>
                                    <input type="checkbox" name="featured" class="checkbox checkbox-primary">
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-full">
                                <span class="icon-[tabler--plus] size-5"></span>
                                Add Item
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Items List Section -->
            <div class="lg:col-span-2">
                <div class="card bg-base-200 shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-4">Menu Items</h2>
                        
                        <?php if (empty($items)): ?>
                            <p class="text-base-content/70">No menu items found.</p>
                        <?php else: ?>
                            <div class="space-y-3 max-h-screen overflow-y-auto">
                                <?php foreach ($items as $item): ?>
                                    <div class="card bg-base-100 p-4 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="flex-1">
                                                <h3 class="font-bold text-lg"><?php echo e($item['name']); ?></h3>
                                                <p class="text-sm text-base-content/70"><?php echo e($item['category'] ?? 'Unknown'); ?></p>
                                                <p class="text-sm text-base-content/60 mt-1"><?php echo e($item['description']); ?></p>
                                                <div class="flex items-center gap-4 mt-2">
                                                    <span class="badge badge-primary">$<?php echo number_format($item['price'], 2); ?></span>
                                                    <?php if ($item['featured']): ?>
                                                        <span class="badge badge-warning">Featured</span>
                                                    <?php endif; ?>
                                                    <?php if ($item['image']): ?>
                                                        <span class="badge badge-info">Has Image</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <button class="btn btn-sm btn-ghost edit-btn" data-id="<?php echo $item['id']; ?>">
                                                    <span class="icon-[tabler--edit] size-4"></span>
                                                </button>
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this item?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-ghost text-error">
                                                        <span class="icon-[tabler--trash] size-4"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        alert('Edit functionality coming soon! You can delete and re-add items for now.');
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
