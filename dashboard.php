<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "config.php";

// Get user's properties
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT p.*, 
    (SELECT image_url FROM property_images WHERE property_id = p.id LIMIT 1) as main_image 
    FROM properties p 
    WHERE p.owner_id = ? 
    ORDER BY p.created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$properties = $result->fetch_all(MYSQLI_ASSOC);
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
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
            <a href="add_property.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-10 px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add New Property
            </a>
        </div>

        <!-- Dashboard Navigation -->
        <div class="mb-8 border-b border-gray-200">
            <nav class="flex space-x-8">
                <a href="#" class="px-1 py-4 text-sm font-medium text-gray-900 border-b-2 border-gray-900">
                    My Properties
                </a>
                <a href="profile.php" class="px-1 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent">
                    Profile Settings
                </a>
                <a href="manage_rental.php" class="px-1 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent">
                    My Rentals
                </a>
            </nav>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (count($properties) > 0): ?>
                <?php foreach ($properties as $property): ?>
                    <div class="bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="relative">
                            <a href="property.php?id=<?= htmlspecialchars($property['id']) ?>" class="block aspect-[4/3] overflow-hidden">
                                <?php if (!empty($property['main_image'])): ?>
                                    <img src="<?= htmlspecialchars($property['main_image']) ?>" alt="<?= htmlspecialchars($property['title']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-gray-400">
                                            <path d="M5 4h14c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <path d="m21 15-5-5L5 21"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </a>
                            <div class="absolute top-3 left-3">
                                <span class="inline-block bg-gray-900/75 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    <?= htmlspecialchars($property['type']) ?>
                                </span>
                            </div>
                            <?php if ($property['featured']): ?>
                                <div class="absolute top-3 right-3">
                                    <span class="inline-block bg-yellow-500/90 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                        Featured
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                <?= htmlspecialchars($property['title']) ?>
                            </h3>
                            <p class="text-sm text-gray-500 mb-3">
                                <?= htmlspecialchars($property['address']) ?>, <?= htmlspecialchars($property['city']) ?>
                            </p>
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-xl font-bold text-gray-900">$<?= number_format($property['price'], 2) ?></p>
                                <p class="text-sm text-gray-500"><?= date('M d, Y', strtotime($property['created_at'])) ?></p>
                            </div>
                            <div class="flex justify-between border-t border-gray-100 pt-4">
                                <a href="edit_property.php?id=<?= $property['id'] ?>" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                    Edit
                                </a>
                                <a href="delete_property.php?id=<?= $property['id'] ?>" class="text-sm font-medium text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this property?')">
                                    Delete
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 py-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto text-gray-400 mb-4">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No properties yet</h3>
                    <p class="text-gray-500 mb-6">You haven't added any properties to your account yet.</p>
                    <a href="add_property.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-10 px-6">
                        Add Your First Property
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>