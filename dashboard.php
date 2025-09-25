<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php include 'components/header.php'; ?>

    <main class="container mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Dashboard content here -->
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>
</html>