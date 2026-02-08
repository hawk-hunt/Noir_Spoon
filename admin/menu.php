<?php
require __DIR__ . '/../includes/db.php';
session_start();
if (empty($_SESSION['admin'])) { header('Location: login.php'); exit; }

$action = $_GET['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $name = trim($_POST['name']);
        $stmt = $pdo->prepare('INSERT INTO menu_categories (name, "order") VALUES (:name, :ord)');
        $stmt->execute(['name'=>$name,'ord'=> (int)($_POST['order'] ?? 0)]);
    } elseif (isset($_POST['add_item'])) {
        $name = trim($_POST['name']);
        $desc = trim($_POST['description']);
        $price = (float)$_POST['price'];
        $cid = (int)$_POST['category_id'];
        $featured = !empty($_POST['featured']) ? true : false;
        $imageName = null;
        if (!empty($_FILES['image']['tmp_name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('img_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../assets/img/' . $imageName);
        }
        $stmt = $pdo->prepare('INSERT INTO menu_items (category_id, name, description, price, image, featured, "order") VALUES (:cid,:name,:desc,:price,:img,:f,:ord)');
        $stmt->execute(['cid'=>$cid,'name'=>$name,'desc'=>$desc,'price'=>$price,'img'=>$imageName,'f'=>$featured,'ord'=>(int)($_POST['order'] ?? 0)]);
    } elseif (isset($_POST['delete_item'])) {
        $stmt = $pdo->prepare('DELETE FROM menu_items WHERE id = :id');
        $stmt->execute(['id'=> (int)$_POST['id']]);
    } elseif (isset($_POST['delete_category'])) {
        $stmt = $pdo->prepare('DELETE FROM menu_categories WHERE id = :id');
        $stmt->execute(['id'=> (int)$_POST['id']]);
    }
    header('Location: menu.php'); exit;
}

$cats = $pdo->query('SELECT * FROM menu_categories ORDER BY "order" ASC')->fetchAll();
$items = $pdo->query('SELECT mi.*, mc.name as category FROM menu_items mi JOIN menu_categories mc ON mi.category_id = mc.id ORDER BY mc."order" ASC, mi."order" ASC')->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>

<section class="bg-base-100 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-base-content">Manage Menu</h1>
            <a href="dashboard.php" class="btn btn-outline">Dashboard</a>
        </div>

        <!-- Add Category -->
        <div class="card card-border shadow-none mb-12">
            <div class="card-body">
                <h2 class="card-title mb-6">Add Category</h2>
                <form method="post" class="flex gap-4 max-md:flex-col">
                    <input type="text" name="name" placeholder="Category name" class="input w-full" required />
                    <input type="number" name="order" min="0" value="0" placeholder="Order" class="input w-32" />
                    <button type="submit" name="add_category" class="btn btn-primary btn-gradient">Add</button>
                </form>
            </div>
        </div>

        <!-- Categories List -->
        <div class="card card-border shadow-none mb-12">
            <div class="card-body">
                <h2 class="card-title mb-6">Categories</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Order</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cats as $c): ?>
                                <tr>
                                    <td class="font-medium"><?php echo e($c['name']); ?></td>
                                    <td><?php echo (int)$c['order']; ?></td>
                                    <td>
                                        <form method="post" style="display:inline">
                                            <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                                            <button type="submit" name="delete_category" class="btn btn-sm btn-outline btn-error" onclick="return confirm('Delete this category?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Item -->
        <div class="card card-border shadow-none mb-12">
            <div class="card-body">
                <h2 class="card-title mb-6">Add Menu Item</h2>
                <form method="post" enctype="multipart/form-data" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <select name="category_id" class="select select-bordered" required>
                            <option value="">Select Category</option>
                            <?php foreach ($cats as $c): ?>
                                <option value="<?php echo (int)$c['id']; ?>"><?php echo e($c['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="name" placeholder="Dish name" class="input input-bordered" required />
                        <input type="number" name="price" placeholder="Price" step="0.01" class="input input-bordered" required />
                        <input type="number" name="order" min="0" value="0" placeholder="Order" class="input input-bordered" />
                    </div>
                    <textarea name="description" placeholder="Description" class="textarea textarea-bordered w-full"></textarea>
                    <div class="flex gap-4 flex-wrap">
                        <input type="file" name="image" accept="image/*" class="file-input file-input-bordered" />
                        <label class="checkbox checkbox-primary flex items-center gap-2">
                            <input type="checkbox" name="featured" />
                            <span>Featured Dish</span>
                        </label>
                    </div>
                    <button type="submit" name="add_item" class="btn btn-primary btn-gradient">Add Item</button>
                </form>
            </div>
        </div>

        <!-- Items List -->
        <div class="card card-border shadow-none">
            <div class="card-body">
                <h2 class="card-title mb-6">Menu Items</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Featured</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $it): ?>
                                <tr>
                                    <td class="font-medium"><?php echo e($it['name']); ?></td>
                                    <td><?php echo e($it['category']); ?></td>
                                    <td>$<?php echo number_format($it['price'], 2); ?></td>
                                    <td><?php echo $it['featured'] ? '✓' : ''; ?></td>
                                    <td>
                                        <form method="post" style="display:inline">
                                            <input type="hidden" name="id" value="<?php echo (int)$it['id']; ?>">
                                            <button type="submit" name="delete_item" class="btn btn-sm btn-outline btn-error" onclick="return confirm('Delete this item?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
