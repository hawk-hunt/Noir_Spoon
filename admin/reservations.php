<?php
require __DIR__ . '/../includes/db.php';
session_start();
if (empty($_SESSION['admin'])) { header('Location: login.php'); exit; }

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM reservations WHERE id = :id');
    $stmt->execute(['id'=> (int)$_GET['delete']]);
    header('Location: reservations.php'); exit;
}

$res = $pdo->query('SELECT * FROM reservations ORDER BY res_date DESC, res_time DESC')->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>

<section class="bg-base-100 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-base-content">Reservations</h1>
            <a href="dashboard.php" class="btn btn-outline">Dashboard</a>
        </div>

        <div class="card card-border shadow-none">
            <div class="card-body">
                <?php if (empty($res)): ?>
                    <div class="text-center py-12">
                        <p class="text-base-content/60 text-lg">No reservations yet</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Guests</th>
                                    <th>Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($res as $r): ?>
                                    <tr>
                                        <td class="font-medium"><?php echo e($r['name']); ?></td>
                                        <td><?php echo e($r['phone']); ?></td>
                                        <td><?php echo e($r['res_date']); ?></td>
                                        <td><?php echo e($r['res_time']); ?></td>
                                        <td class="text-center"><?php echo (int)$r['guests']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($r['created_at'])); ?></td>
                                        <td>
                                            <a href="?delete=<?php echo (int)$r['id']; ?>" class="btn btn-sm btn-outline btn-error" onclick="return confirm('Delete this reservation?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
