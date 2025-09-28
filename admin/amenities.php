<?php
require_once __DIR__ . '/auth.php';
require_admin();
require_once __DIR__ . '/../config.php';

$message = '';
$error = '';

// Create amenity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name'] ?? '');
    if ($name !== '') {
        $stmt = $conn->prepare('INSERT INTO amenities (name) VALUES (?)');
        if ($stmt) {
            $stmt->bind_param('s', $name);
            if ($stmt->execute()) {
                $message = 'Amenity added.';
            } else {
                $error = 'Failed to add: ' . $conn->error;
            }
            $stmt->close();
        } else {
            $error = 'Database error: ' . $conn->error;
        }
    } else {
        $error = 'Amenity name is required.';
    }
}

// Update amenity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    if ($id && $name !== '') {
        $stmt = $conn->prepare('UPDATE amenities SET name = ? WHERE id = ?');
        if ($stmt) {
            $stmt->bind_param('si', $name, $id);
            if ($stmt->execute()) {
                $message = 'Amenity updated.';
            } else {
                $error = 'Failed to update: ' . $conn->error;
            }
            $stmt->close();
        } else {
            $error = 'Database error: ' . $conn->error;
        }
    } else {
        $error = 'Invalid data.';
    }
}

// Delete amenity
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare('DELETE FROM amenities WHERE id = ?');
    if ($stmt) {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $message = 'Amenity deleted.';
        } else {
            $error = 'Failed to delete: ' . $conn->error;
        }
        $stmt->close();
    } else {
        $error = 'Database error: ' . $conn->error;
    }
}

$amenities = $conn->query('SELECT id, name FROM amenities ORDER BY name ASC');
?>
<?php include __DIR__ . '/components/header.php'; ?>
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-semibold">Manage Amenities</h1>
      <p class="text-sm text-gray-600">Add, edit, and delete amenities.</p>
    </div>
    <a href="/house_rental/admin/properties.php" class="text-sm text-indigo-600 hover:text-indigo-800">Back to Properties</a>
  </div>

  <?php if ($message): ?>
    <div class="mb-4 rounded bg-green-50 border border-green-200 p-3 text-green-800 text-sm"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="mb-4 rounded bg-red-50 border border-red-200 p-3 text-red-800 text-sm"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <!-- Add New Amenity -->
  <div class="rounded-lg bg-white shadow border p-4 mb-6">
    <form method="POST" class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
      <div class="flex-1">
        <label class="block text-sm font-medium mb-1">New Amenity</label>
        <input type="text" name="name" class="w-full rounded border px-3 py-2" placeholder="e.g., Swimming Pool" required />
      </div>
      <input type="hidden" name="action" value="add" />
      <button class="rounded bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">Add</button>
    </form>
    <p class="mt-2 text-xs text-gray-500">Amenity names must be unique.</p>
  </div>

  <!-- List and Edit Amenities -->
  <div class="rounded-lg bg-white shadow border">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-4 py-2 text-left">ID</th>
            <th class="px-4 py-2 text-left">Name</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($amenities && $amenities->num_rows > 0): ?>
            <?php while ($a = $amenities->fetch_assoc()): ?>
              <tr class="border-b">
                <td class="px-4 py-2">#<?php echo (int)$a['id']; ?></td>
                <td class="px-4 py-2">
                  <form method="POST" class="flex items-center gap-2">
                    <input type="hidden" name="action" value="edit" />
                    <input type="hidden" name="id" value="<?php echo (int)$a['id']; ?>" />
                    <input type="text" name="name" value="<?php echo htmlspecialchars($a['name']); ?>" class="rounded border px-2 py-1 w-64" />
                    <button class="rounded bg-gray-900 text-white px-3 py-1 hover:bg-gray-800">Save</button>
                  </form>
                </td>
                <td class="px-4 py-2 text-center">
                  <a href="/house_rental/admin/amenities.php?action=delete&id=<?php echo (int)$a['id']; ?>" class="rounded bg-red-600 text-white px-3 py-1 text-xs hover:bg-red-700" onclick="return confirm('Delete this amenity?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="px-4 py-6 text-center text-gray-600">No amenities found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php include __DIR__ . '/components/footer.php'; ?>