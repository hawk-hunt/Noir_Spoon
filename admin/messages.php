<?php
require __DIR__ . '/../includes/db.php';
session_start();
if (empty($_SESSION['admin'])) { header('Location: login.php'); exit; }

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM contact_messages WHERE id = :id');
    $stmt->execute(['id'=> (int)$_GET['delete']]);
    header('Location: messages.php'); exit;
}

$msgs = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>

<section class="bg-base-100 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-base-content">Contact Messages</h1>
            <a href="dashboard.php" class="btn btn-outline">Dashboard</a>
        </div>

        <div class="card card-border shadow-none">
            <div class="card-body">
                <?php if (empty($msgs)): ?>
                    <div class="text-center py-12">
                        <p class="text-base-content/60 text-lg">No messages yet</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach ($msgs as $m): ?>
                            <div class="border-b border-base-content/10 pb-6 last:border-b-0">
                                <div class="flex justify-between items-start gap-4 mb-3">
                                    <div>
                                        <h3 class="font-semibold text-base-content text-lg"><?php echo e($m['name']); ?></h3>
                                        <p class="text-base-content/60 text-sm"><?php echo e($m['email']); ?></p>
                                        <p class="text-base-content/50 text-xs mt-1"><?php echo date('M d, Y at H:i', strtotime($m['created_at'])); ?></p>
                                    </div>
                                    <a href="?delete=<?php echo (int)$m['id']; ?>" class="btn btn-sm btn-outline btn-error" onclick="return confirm('Delete this message?')">Delete</a>
                                </div>
                                <div class="bg-base-200 rounded p-4 mt-3">
                                    <p class="text-base-content whitespace-pre-wrap"><?php echo e($m['message']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
