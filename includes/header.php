<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en" data-theme="light" data-assets-path="assets/" dir="ltr" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no,minimum-scale=1.0,maximum-scale=1.0" />
    <meta name="robots" content="index, follow" />
    <title>Noir Spoon — Fine Dining Experience</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

    <!-- Your Main CSS -->
    <link rel="stylesheet" href="assets/dist/css/output.css" />
    
    <!-- Flatpickr for date picker -->
    <link rel="stylesheet" href="assets/dist/libs/flatpickr/dist/flatpickr.css" />
    
    <!-- Theme JS -->
    <script type="text/javascript">
      (function () {
        try {
          const root = document.documentElement;
          const layoutPath = root.getAttribute('data-layout-path')?.replace('/', '') || 'restaurant';
          const localStorageKey = `${layoutPath}-theme`;
          const savedTheme = localStorage.getItem(localStorageKey) || 'light';
          root.setAttribute('data-theme', savedTheme);
        } catch (e) {
          console.warn('Theme script error:', e);
        }
      })();
    </script>
</head>
<body class="scroll-smooth">
<header class="border-base-content/20 bg-base-100 py-0.25 fixed top-0 z-10 w-full border-b">
    <nav class="navbar mx-auto max-w-[1280px] rounded-b-xl px-4 sm:px-6 lg:px-8">
        <div class="w-full lg:flex lg:items-center lg:gap-2">
            <div class="navbar-start items-center justify-between max-lg:w-full">
                <a class="text-base-content flex items-center gap-3 text-xl font-semibold" href="./">
                    <span class="text-primary">🍽️</span> Noir Spoon
                </a>
                <div class="flex items-center gap-5 lg:hidden">
                    <a href="reservation.php" class="btn btn-primary btn-sm">Reserve</a>
                    <button type="button" class="collapse-toggle btn btn-outline btn-secondary btn-square" data-collapse="#navbar-menu" aria-controls="navbar-menu">
                        <span class="icon-[tabler--menu-2] collapse-open:hidden size-5.5"></span>
                        <span class="icon-[tabler--x] collapse-open:block size-5.5 hidden"></span>
                    </button>
                </div>
            </div>
            <div id="navbar-menu" class="lg:navbar-center transition-height collapse hidden grow overflow-hidden font-medium duration-300 lg:flex">
                <div class="text-base-content flex gap-6 text-base max-lg:mt-4 max-lg:flex-col lg:items-center">
                    <a href="./" class="hover:text-primary">Home</a>
                    <a href="menu.php" class="hover:text-primary">Menu</a>
                    <a href="about.php" class="hover:text-primary">About</a>
                    <a href="contact.php" class="hover:text-primary">Contact</a>
                </div>
            </div>
            <div class="navbar-end max-lg:hidden">
                <a href="reservation.php" class="btn btn-primary">Reserve a Table</a>
                <a href="admin/login.php" class="btn btn-ghost btn-sm ml-2">Admin</a>
            </div>
        </div>
    </nav>
</header>
<main class="pt-20">
