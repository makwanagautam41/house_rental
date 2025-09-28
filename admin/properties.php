<?php
require_once __DIR__ . '/auth.php';
require_admin();
require_once __DIR__ . '/../config.php';

$message = '';
$error = '';

// Add new amenity (handled here to keep only Dashboard/Users/Properties pages visible)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_amenity') {
    $amenityName = trim($_POST['amenity_name'] ?? '');
    if ($amenityName === '') {
        $error = 'Amenity name is required.';
    } else {
        $stmt = $conn->prepare('INSERT INTO amenities (name) VALUES (?)');
        if ($stmt) {
            $stmt->bind_param('s', $amenityName);
            if ($stmt->execute()) {
                $message = 'Amenity added successfully.';
            } else {
                // Likely duplicate due to UNIQUE constraint
                $error = 'Failed to add amenity: ' . $conn->error;
            }
            $stmt->close();
        } else {
            $error = 'Database error: ' . $conn->error;
        }
    }
}

// Admin delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
  $property_id = (int)$_GET['id'];
  try {
    $conn->begin_transaction();

    // Delete property images and files
    $stmt = $conn->prepare("SELECT image_url FROM property_images WHERE property_id = ?");
    $stmt->bind_param('i', $property_id);
    $stmt->execute();
    $images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($images as $image) {
      $path = $image['image_url'];
      if ($path && file_exists($path)) {
        @unlink($path);
      }
    }

    // Delete related tables
    $stmt = $conn->prepare('DELETE FROM property_amenities WHERE property_id = ?');
    $stmt->bind_param('i', $property_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare('DELETE FROM property_images WHERE property_id = ?');
    $stmt->bind_param('i', $property_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare('DELETE FROM testimonials WHERE property_id = ?');
    $stmt->bind_param('i', $property_id);
    $stmt->execute();
    $stmt->close();

    // Delete property itself
    $stmt = $conn->prepare('DELETE FROM properties WHERE id = ?');
    $stmt->bind_param('i', $property_id);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    $message = 'Property deleted successfully.';
  } catch (Exception $e) {
    $conn->rollback();
    $error = 'Error deleting property: ' . $e->getMessage();
  }
}

// Fetch properties
$sql = "SELECT p.*, 
               (SELECT COUNT(*) FROM rental_agreements ra WHERE ra.property_id = p.id AND ra.status = 'active') as active_rentals,
               (SELECT COUNT(*) FROM rental_applications ra WHERE ra.property_id = p.id AND ra.status = 'pending') as pending_apps,
               (SELECT image_url FROM property_images WHERE property_id = p.id LIMIT 1) as main_image,
               u.name AS owner_name, u.email AS owner_email
        FROM properties p
        JOIN users u ON p.owner_id = u.userId
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>
<?php include __DIR__ . '/components/header.php'; ?>
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Manage Properties</h1>
    <div class="flex items-center gap-3">
      <a href="/house_rental/properties.php" class="text-sm text-indigo-600 hover:text-indigo-800">View Public Properties</a>
      <a href="/house_rental/admin/amenities.php" class="text-sm text-indigo-600 hover:text-indigo-800">Manage Amenities</a>
    </div>
  </div>

  <?php if ($message): ?>
    <div class="mb-4 rounded bg-green-50 border border-green-200 p-3 text-green-800 text-sm"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="mb-4 rounded bg-red-50 border border-red-200 p-3 text-red-800 text-sm"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <!-- Amenities are managed on a dedicated page -->

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
      <div class="rounded-lg bg-white shadow border">
        <img src="<?php echo htmlspecialchars($row['main_image'] ?: 'https://via.placeholder.com/800x600?text=No+Image'); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="w-full h-40 object-cover rounded-t-lg" />
        <div class="p-4">
          <h3 class="text-lg font-semibold mb-1"><?php echo htmlspecialchars($row['title']); ?></h3>
          <p class="text-sm text-gray-500 mb-3"><?php echo htmlspecialchars($row['address'] . ', ' . $row['city'] . ', ' . $row['state']); ?></p>
          <div class="flex justify-between text-sm mb-3">
            <span class="text-gray-700 font-medium">$<?php echo number_format($row['price'], 2); ?></span>
            <span class="text-gray-500">Owner: <?php echo htmlspecialchars($row['owner_name']); ?></span>
          </div>
          <div class="flex items-center justify-between text-xs text-gray-600 mb-4">
            <span>Active rentals: <?php echo (int)$row['active_rentals']; ?></span>
            <span>Pending apps: <?php echo (int)$row['pending_apps']; ?></span>
          </div>
          <div class="flex justify-between items-center">
            <a href="/house_rental/property.php?id=<?php echo (int)$row['id']; ?>" class="text-indigo-600 hover:text-indigo-800 text-sm">View</a>
            <a href="/house_rental/admin/properties.php?action=delete&id=<?php echo (int)$row['id']; ?>" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Delete this property? This cannot be undone.')">Delete</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="rounded bg-white shadow p-6 text-gray-600">No properties found.</div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>