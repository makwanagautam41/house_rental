<?php
require_once __DIR__ . '/auth.php';
require_admin();
require_once __DIR__ . '/../config.php';

function fetch_count(mysqli $conn, string $sql): int {
    $res = $conn->query($sql);
    if ($res && $row = $res->fetch_row()) {
        return (int)$row[0];
    }
    return 0;
}

$userCount = fetch_count($conn, "SELECT COUNT(*) FROM users");
$propertyCount = fetch_count($conn, "SELECT COUNT(*) FROM properties");
$activeAgreements = fetch_count($conn, "SELECT COUNT(*) FROM rental_agreements WHERE status = 'active'");
$pendingApps = fetch_count($conn, "SELECT COUNT(*) FROM rental_applications WHERE status = 'pending'");
$amenitiesCount = 0;
// amenities table may not have id in schema but exists; safeguard
$amenitiesCount = fetch_count($conn, "SELECT COUNT(*) FROM amenities");

?>
<?php include __DIR__ . '/components/header.php'; ?>
  <h1 class="text-2xl font-semibold mb-6">Dashboard</h1>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="rounded-lg bg-white shadow p-6">
      <div class="text-sm text-gray-500">Users</div>
      <div class="mt-2 text-3xl font-bold text-indigo-700"><?php echo $userCount; ?></div>
    </div>
    <div class="rounded-lg bg-white shadow p-6">
      <div class="text-sm text-gray-500">Properties</div>
      <div class="mt-2 text-3xl font-bold text-indigo-700"><?php echo $propertyCount; ?></div>
    </div>
    <div class="rounded-lg bg-white shadow p-6">
      <div class="text-sm text-gray-500">Active Agreements</div>
      <div class="mt-2 text-3xl font-bold text-indigo-700"><?php echo $activeAgreements; ?></div>
    </div>
    <div class="rounded-lg bg-white shadow p-6">
      <div class="text-sm text-gray-500">Pending Applications</div>
      <div class="mt-2 text-3xl font-bold text-indigo-700"><?php echo $pendingApps; ?></div>
    </div>
    <div class="rounded-lg bg-white shadow p-6">
      <div class="text-sm text-gray-500">Amenities</div>
      <div class="mt-2 text-3xl font-bold text-indigo-700"><?php echo $amenitiesCount; ?></div>
    </div>
  </div>

  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="rounded-lg bg-white shadow p-6">
      <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>
      <div class="flex flex-wrap gap-3">
        <a href="/house_rental/admin/users.php" class="inline-block rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Manage Users</a>
        <a href="/house_rental/admin/properties.php" class="inline-block rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Manage Properties</a>
      </div>
    </div>
    <div class="rounded-lg bg-white shadow p-6">
      <h2 class="text-lg font-semibold mb-4">Notes</h2>
      <ul class="list-disc ml-6 text-sm text-gray-700 space-y-2">
        <li>The dashboard reflects live data from your database.</li>
        <li>Actions here mirror existing site flows, but with admin privileges.</li>
        <li>Use the navigation above to access each module.</li>
      </ul>
    </div>
  </div>
<?php include __DIR__ . '/components/footer.php'; ?>