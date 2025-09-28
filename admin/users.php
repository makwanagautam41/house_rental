<?php
require_once __DIR__ . '/auth.php';
require_admin();
require_once __DIR__ . '/../config.php';

$message = '';
$error = '';

// Delete user
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
  $userId = (int)$_GET['id'];
  $stmt = $conn->prepare('DELETE FROM users WHERE userId = ?');
  $stmt->bind_param('i', $userId);
  if ($stmt->execute()) {
    $message = 'User deleted successfully.';
  } else {
    $error = 'Failed to delete user: ' . $conn->error;
  }
  $stmt->close();
}

$result = $conn->query("SELECT userId, name, email, phone FROM users ORDER BY userId DESC");
?>
<?php include __DIR__ . '/components/header.php'; ?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <!-- Page Heading -->
  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800">Manage Users</h1>
  </div>

  <!-- Messages -->
  <?php if ($message): ?>
    <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm shadow-sm">
      <?php echo htmlspecialchars($message); ?>
    </div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm shadow-sm">
      <?php echo htmlspecialchars($error); ?>
    </div>
  <?php endif; ?>

  <!-- Table -->
  <div class="overflow-hidden rounded-lg bg-white shadow border">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($u = $result->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-gray-700 font-medium">#<?php echo (int)$u['userId']; ?></td>
                <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($u['name']); ?></td>
                <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($u['email']); ?></td>
                <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($u['phone']); ?></td>
                <td class="px-6 py-4 text-center space-x-2">
                  <a href="/house_rental/admin/properties.php?owner_id=<?php echo (int)$u['userId']; ?>"
                    class="inline-block rounded-md bg-indigo-600 text-white px-3 py-1 text-xs font-medium hover:bg-indigo-700 transition">
                    View Properties
                  </a>
                  <a href="/house_rental/admin/users.php?action=delete&id=<?php echo (int)$u['userId']; ?>"
                    onclick="return confirm('Delete this user? Their properties and related records will also be removed.')"
                    class="inline-block rounded-md bg-red-600 text-white px-3 py-1 text-xs font-medium hover:bg-red-700 transition">
                    Delete
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                No users found.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/components/footer.php'; ?>