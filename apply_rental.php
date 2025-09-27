<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=apply_rental.php?property_id=" . $_GET['property_id']);
    exit();
}

$propertyId = $_GET['property_id'] ?? null;
$userId = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch property details
if ($propertyId) {
    $stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->bind_param("i", $propertyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();

    if (!$property) {
        header("Location: properties.php");
        exit();
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $budget = $_POST['budget'];
    $occupants = $_POST['occupants'];
    $message = $_POST['message'];

    // Validate inputs
    if (empty($startDate) || empty($endDate) || empty($budget) || empty($occupants)) {
        $error = "All fields are required except message.";
    } else {
        // Insert application into database
        $stmt = $conn->prepare("INSERT INTO rental_applications (property_id, user_id, start_date, end_date, monthly_budget, occupants, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssis", $propertyId, $userId, $startDate, $endDate, $budget, $occupants, $message);

        if ($stmt->execute()) {
            $success = "Your rental application has been submitted successfully!";
        } else {
            $error = "Error submitting application: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Rental - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">
    <?php include 'components/header.php'; ?>

    <main class="min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Apply for Rental</h1>

                <?php if ($property): ?>
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($property['title']); ?></h2>
                        <p class="text-gray-600"><?php echo htmlspecialchars($property['address'] . ', ' . $property['city'] . ', ' . $property['state']); ?></p>
                        <p class="text-lg font-medium text-gray-800 mt-2">$<?php echo number_format($property['price'], 2); ?> / month</p>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="bg-red-50 text-red-700 p-4 rounded-md mb-6">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-50 text-green-700 p-4 rounded-md mb-6">
                        <?php echo $success; ?>
                        <p class="mt-2">
                            <a href="property.php?id=<?php echo $propertyId; ?>" class="text-blue-600 hover:underline">Return to property</a>
                        </p>
                    </div>
                <?php else: ?>
                    <form method="POST" action="">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Move-in Date</label>
                                <input type="date" id="start_date" name="start_date" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Move-out Date</label>
                                <input type="date" id="end_date" name="end_date" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Monthly Budget ($)</label>
                                <input type="number" id="budget" name="budget" min="0" step="0.01" required
                                    value="<?php echo htmlspecialchars($property['price'] ?? ''); ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="occupants" class="block text-sm font-medium text-gray-700 mb-1">Number of Occupants</label>
                                <input type="number" id="occupants" name="occupants" min="1" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message to Owner (Optional)</label>
                            <textarea id="message" name="message" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Introduce yourself and explain why you're interested in this property..."></textarea>
                        </div>

                        <div class="flex justify-end">
                            <a href="property.php?id=<?php echo $propertyId; ?>" class="mr-4 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Submit Application
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>