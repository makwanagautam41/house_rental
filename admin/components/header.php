<?php
require_once __DIR__ . '/../auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>House Rental Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="/house_rental/assets/css/style.css" />
</head>
<body class="min-h-screen bg-gray-100 text-gray-900">
  <header class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
      <a href="/house_rental/admin/dashboard.php" class="text-xl font-bold text-indigo-600">Admin Panel</a>

      <!-- Desktop nav -->
      <nav id="admin-nav" class="hidden md:flex items-center gap-4 text-sm">
        <a class="hover:text-indigo-600" href="/house_rental/admin/dashboard.php">Dashboard</a>
        <a class="hover:text-indigo-600" href="/house_rental/admin/users.php">Users</a>
        <a class="hover:text-indigo-600" href="/house_rental/admin/properties.php">Properties</a>
      </nav>

      <!-- Desktop actions -->
      <div class="hidden md:flex items-center gap-3">
        <?php if (is_admin_logged_in()): ?>
          <span class="text-xs sm:text-sm text-gray-600">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['admin_email']); ?></strong></span>
          <a href="/house_rental/admin/logout.php" class="inline-block rounded bg-red-500 px-3 py-1 text-white text-sm hover:bg-red-600">Logout</a>
        <?php else: ?>
          <a href="/house_rental/admin/login.php" class="inline-block rounded bg-indigo-600 px-3 py-1 text-white text-sm hover:bg-indigo-700">Login</a>
        <?php endif; ?>
      </div>

      <!-- Mobile menu button -->
      <button id="admin-menu-btn" class="md:hidden inline-flex items-center justify-center rounded border border-gray-300 p-2 text-gray-700 hover:bg-gray-50" aria-controls="admin-mobile-menu" aria-expanded="false">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
        <span class="sr-only">Open menu</span>
      </button>
    </div>

    <!-- Mobile menu panel -->
    <div id="admin-mobile-menu" class="md:hidden hidden border-t bg-white">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3 space-y-3">
        <div class="flex flex-col gap-2 text-sm">
          <a class="hover:text-indigo-600" href="/house_rental/admin/dashboard.php">Dashboard</a>
          <a class="hover:text-indigo-600" href="/house_rental/admin/users.php">Users</a>
          <a class="hover:text-indigo-600" href="/house_rental/admin/properties.php">Properties</a>
        </div>
        <div class="pt-3 border-t flex items-center justify-between">
          <?php if (is_admin_logged_in()): ?>
            <span class="text-xs sm:text-sm text-gray-600">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['admin_email']); ?></strong></span>
            <a href="/house_rental/admin/logout.php" class="inline-block rounded bg-red-500 px-3 py-1 text-white text-sm hover:bg-red-600">Logout</a>
          <?php else: ?>
            <a href="/house_rental/admin/login.php" class="inline-block rounded bg-indigo-600 px-3 py-1 text-white text-sm hover:bg-indigo-700">Login</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>

  <script>
    (function() {
      var btn = document.getElementById('admin-menu-btn');
      var panel = document.getElementById('admin-mobile-menu');
      if (!btn || !panel) return;
      btn.addEventListener('click', function() {
        var isOpen = panel.classList.toggle('hidden') === false; // classList.toggle returns boolean for adding
        btn.setAttribute('aria-expanded', String(isOpen));
      });
    })();
  </script>

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">