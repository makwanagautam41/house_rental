<?php
require_once __DIR__ . '/auth.php';
if (is_admin_logged_in()) {
    header('Location: /house_rental/admin/dashboard.php');
    exit();
}
?>
<?php include __DIR__ . '/components/header.php'; ?>
  <div class="mx-auto max-w-md">
    <div class="bg-white shadow rounded p-6">
      <h1 class="text-2xl font-semibold mb-4">Admin Login</h1>
      <?php if (isset($_GET['error'])): ?>
        <p class="mb-4 text-red-600 text-sm"><?php echo htmlspecialchars($_GET['error']); ?></p>
      <?php endif; ?>
      <form method="POST" action="/house_rental/admin/login_admin.php" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input type="email" name="email" required class="w-full rounded border px-3 py-2" placeholder="admin@gmail.com" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Password</label>
          <input type="password" name="password" required class="w-full rounded border px-3 py-2" placeholder="123" />
        </div>
        <button type="submit" class="w-full rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Login</button>
      </form>
      <p class="mt-4 text-xs text-gray-500">Use email <strong>admin@gmail.com</strong> and password <strong>123</strong>.</p>
    </div>
  </div>
<?php include __DIR__ . '/components/footer.php'; ?>