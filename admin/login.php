<?php
require __DIR__ . '/../includes/db.php';
session_start();
if (!empty($_SESSION['admin'])) header('Location: dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$pdo) {
        $error = 'Database is not connected. Admin panel requires PostgreSQL to be configured.';
    } else {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :u LIMIT 1');
        $stmt->execute(['u'=>$username]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin'] = ['id'=>$admin['id'],'username'=>$admin['username']];
            header('Location: dashboard.php'); exit;
        }
        $error = 'Invalid username or password';
    }
}
?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login — Noir Spoon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/dist/css/output.css" />
</head>
<body class="bg-base-200 flex items-center justify-center min-h-screen">
<main class="container mx-auto px-4">
    <div class="card shadow-lg max-w-md mx-auto">
        <div class="card-body gap-6">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-base-content">Admin Login</h1>
                <p class="text-base-content/60 mt-2">Noir Spoon Management Panel</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <svg class="h-6 w-6 shrink-0 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><?php echo e($error); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!$error || strlen($error) < 50): ?>
            <form method="post" class="space-y-6">
                <div>
                    <label class="label-text mb-2 block" for="username">Username</label>
                    <div class="input input-lg">
                        <input type="text" class="grow" placeholder="admin" id="username" name="username" required autofocus />
                        <span class="icon-[tabler--user] text-base-content/80 size-5.5 shrink-0"></span>
                    </div>
                </div>
                <div>
                    <label class="label-text mb-2 block" for="password">Password</label>
                    <div class="input input-lg">
                        <input type="password" class="grow" placeholder="••••••••" id="password" name="password" required />
                        <span class="icon-[tabler--lock] text-base-content/80 size-5.5 shrink-0"></span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-gradient w-full">Sign In</button>
            </form>
            <?php endif; ?>
            
            <div class="text-center">
                <a href="../" class="link link-primary text-sm">Back to website</a>
            </div>
        </div>
    </div>
</main>
<script src="../assets/dist/libs/flatpickr/dist/flatpickr.js"></script>
<script src="../assets/dist/libs/flyonui/flyonui.js"></script>
<script src="../assets/dist/js/theme-utils.js"></script>
</body>
</html>
