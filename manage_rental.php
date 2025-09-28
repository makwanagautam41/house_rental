<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$currentUserId = (int)$_SESSION['user_id'];
$propertyId = isset($_GET['property_id']) ? (int)$_GET['property_id'] : null;

// Handle actions: terminate agreement, extend end date
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agreement_id'])) {
    $agreementId = (int)$_POST['agreement_id'];
    $action = $_POST['action'] ?? '';

    // Fetch agreement to validate permissions
    $stmt = $conn->prepare("SELECT id, property_id, owner_id, tenant_id, status, start_date, end_date FROM rental_agreements WHERE id = ?");
    $stmt->bind_param('i', $agreementId);
    $stmt->execute();
    $agreement = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($agreement) {
        $isOwner = ($agreement['owner_id'] == $currentUserId);
        $isTenant = ($agreement['tenant_id'] == $currentUserId);

        if ($action === 'terminate' && ($isOwner || $isTenant)) {
            $stmt = $conn->prepare("UPDATE rental_agreements SET status = 'terminated' WHERE id = ?");
            $stmt->bind_param('i', $agreementId);
            $stmt->execute();
            $stmt->close();
            $message = 'Agreement terminated successfully.';
        } elseif ($action === 'extend' && $isOwner) {
            $newEnd = $_POST['new_end_date'] ?? '';
            if ($newEnd) {
                $stmt = $conn->prepare("UPDATE rental_agreements SET end_date = ? WHERE id = ?");
                $stmt->bind_param('si', $newEnd, $agreementId);
                $stmt->execute();
                $stmt->close();
                $message = 'Agreement end date updated.';
            } else {
                $message = 'Please provide a valid new end date.';
            }
        }
    }
}

// Load active agreements for the current user (as owner or tenant)
$sql = "SELECT ra.*, p.title, p.address, p.city, p.state,
               uo.name AS owner_name, ut.name AS tenant_name
        FROM rental_agreements ra
        JOIN properties p ON ra.property_id = p.id
        JOIN users uo ON ra.owner_id = uo.userId
        JOIN users ut ON ra.tenant_id = ut.userId
        WHERE ra.status = 'active' AND (ra.owner_id = ? OR ra.tenant_id = ?)";

if ($propertyId) {
    $sql .= " AND ra.property_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param('iii', $currentUserId, $currentUserId, $propertyId);
} else {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param('ii', $currentUserId, $currentUserId);
}

$stmt->execute();
$agreementsResult = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rentals</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">
    <?php include 'components/header.php'; ?>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">Manage Rentals</h2>

        <?php if (!empty($message)) : ?>
            <div class="mb-4 p-3 rounded-md bg-blue-100 text-blue-800 border border-blue-300">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <p class="text-gray-600 mb-6">Viewing active rental agreements where you are the landlord or tenant.</p>

        <?php if ($agreementsResult && $agreementsResult->num_rows > 0): ?>
            <div class="space-y-6">
                <?php while ($ag = $agreementsResult->fetch_assoc()):
                    $role = $ag['owner_id'] == $currentUserId ? 'Landlord' : 'Tenant';
                ?>
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                        <h3 class="text-xl font-semibold mb-2">
                            <?= htmlspecialchars($ag['title']) ?>
                            <span class="text-gray-500 text-sm"> (<?= htmlspecialchars($ag['city']) ?>, <?= htmlspecialchars($ag['state']) ?>)</span>
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Role: <span class="font-medium text-gray-800"><?= $role ?></span></p>
                                <p>Tenant: <span class="font-medium"><?= htmlspecialchars($ag['tenant_name']) ?></span></p>
                                <p>Landlord: <span class="font-medium"><?= htmlspecialchars($ag['owner_name']) ?></span></p>
                            </div>
                            <div>
                                <p>Start: <span class="font-medium"><?= htmlspecialchars($ag['start_date']) ?></span></p>
                                <p>End: <span class="font-medium"><?= htmlspecialchars($ag['end_date']) ?></span></p>
                                <p>Monthly Rent: <span class="font-medium">₹<?= number_format((float)$ag['monthly_rent']) ?></span></p>
                                <p>Security Deposit: <span class="font-medium">₹<?= number_format((float)$ag['security_deposit']) ?></span></p>
                                <p>Status: <span class="text-green-600 font-semibold"><?= htmlspecialchars($ag['status']) ?></span></p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="view_agreement.php?application_id=<?= (int)$ag['application_id'] ?>"
                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                View Agreement
                            </a>


                            <?php if ($ag['owner_id'] == $currentUserId): ?>
                                <form method="post" class="flex flex-col gap-2 md:flex-row md:items-center">
                                    <input type="hidden" name="agreement_id" value="<?= (int)$ag['id'] ?>">
                                    <input type="hidden" name="action" value="extend">
                                    <div>
                                        <label for="new_end_<?= (int)$ag['id'] ?>" class="block text-sm text-gray-700">Extend end date</label>
                                        <input type="date" id="new_end_<?= (int)$ag['id'] ?>" name="new_end_date"
                                            class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full md:w-auto">
                                    </div>
                                    <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-yellow-400 text-gray-900 font-medium hover:bg-yellow-500 transition">
                                        Update
                                    </button>
                                </form>
                            <?php endif; ?>

                            <form method="post" onsubmit="return confirm('Are you sure to terminate this agreement?');">
                                <input type="hidden" name="agreement_id" value="<?= (int)$ag['id'] ?>">
                                <input type="hidden" name="action" value="terminate">
                                <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                                    Terminate
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="p-4 rounded-md bg-gray-100 border border-gray-300 text-gray-700">
                No active rental agreements found for your account.
            </div>
        <?php endif; ?>

        <div class="mt-6">
            <a href="properties.php"
                class="inline-block px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                Browse Properties
            </a>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>

</html>