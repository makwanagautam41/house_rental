<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "config.php";

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT fullname, email FROM user WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">
    <?php include 'components/header.php'; ?>

    <main class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="w-full max-w-xl bg-white p-8 rounded-lg shadow border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Your Profile</h1>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <p class="mt-1 text-gray-900"><?= htmlspecialchars($user['fullname']) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <p class="mt-1 text-gray-900"><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="edit_profile.php" class="inline-block px-4 py-2 text-sm font-medium bg-gray-900 text-white rounded hover:bg-gray-800">Edit Profile</a>
                <a href="logout.php" class="inline-block px-4 py-2 text-sm font-medium bg-red-600 text-white rounded hover:bg-red-500">Logout</a>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>