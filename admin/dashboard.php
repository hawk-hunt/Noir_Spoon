<?php
require __DIR__ . '/../includes/db.php';
session_start();
if (empty($_SESSION['admin'])) { header('Location: login.php'); exit; }

// Counts
$counts = [];
$tables = ['menu_categories','menu_items','reservations','contact_messages'];
foreach ($tables as $t) {
    $stmt = $pdo->query("SELECT count(*) FROM $t");
    $counts[$t] = (int)$stmt->fetchColumn();
}

include __DIR__ . '/../../includes/header.php';
?>

<section class="bg-base-100 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-base-content text-4xl font-bold">Admin Dashboard</h1>
                <p class="text-base-content/60 mt-2">Manage your restaurant</p>
            </div>
            <a href="logout.php" class="btn btn-outline">Logout</a>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="card card-border shadow-none">
                <div class="card-body text-center">
                    <div class="text-4xl font-bold text-primary"><?php echo $counts['menu_categories']; ?></div>
                    <p class="text-base-content/60">Categories</p>
                </div>
            </div>
            <div class="card card-border shadow-none">
                <div class="card-body text-center">
                    <div class="text-4xl font-bold text-primary"><?php echo $counts['menu_items']; ?></div>
                    <p class="text-base-content/60">Menu Items</p>
                </div>
            </div>
            <div class="card card-border shadow-none">
                <div class="card-body text-center">
                    <div class="text-4xl font-bold text-primary"><?php echo $counts['reservations']; ?></div>
                    <p class="text-base-content/60">Reservations</p>
                </div>
            </div>
            <div class="card card-border shadow-none">
                <div class="card-body text-center">
                    <div class="text-4xl font-bold text-primary"><?php echo $counts['contact_messages']; ?></div>
                    <p class="text-base-content/60">Messages</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="menu.php" class="btn btn-lg btn-primary btn-gradient w-full">
                <span class="icon-[tabler--cutlery]"></span>
                Manage Menu
            </a>
            <a href="reservations.php" class="btn btn-lg btn-primary btn-gradient w-full">
                <span class="icon-[tabler--calendar-check]"></span>
                View Reservations
            </a>
            <a href="messages.php" class="btn btn-lg btn-primary btn-gradient w-full">
                <span class="icon-[tabler--mail]"></span>
                View Messages
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
