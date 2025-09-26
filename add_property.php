<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "config.php";

// Get all amenities for the selection form
$stmt = $conn->prepare("SELECT * FROM amenities ORDER BY name");
$stmt->execute();
$amenities = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $title = trim($_POST['title']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $bedrooms = filter_var($_POST['bedrooms'], FILTER_VALIDATE_INT);
    $bathrooms = filter_var($_POST['bathrooms'], FILTER_VALIDATE_INT);
    $area = filter_var($_POST['area'], FILTER_VALIDATE_INT);
    $type = trim($_POST['type']);
    $furnishing = trim($_POST['furnishing']);
    $description = trim($_POST['description']);
    $featured = isset($_POST['featured']) ? 1 : 0;
    $amenity_ids = isset($_POST['amenities']) ? $_POST['amenities'] : [];

    // Validation
    $errors = [];
    if (empty($title)) $errors[] = "Title is required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($city)) $errors[] = "City is required";
    if (empty($state)) $errors[] = "State is required";
    if ($price === false || $price <= 0) $errors[] = "Valid price is required";
    if ($bedrooms === false || $bedrooms <= 0) $errors[] = "Valid number of bedrooms is required";
    if ($bathrooms === false || $bathrooms <= 0) $errors[] = "Valid number of bathrooms is required";
    if ($area === false || $area <= 0) $errors[] = "Valid area is required";

    // If no errors, proceed with insertion
    if (empty($errors)) {
        try {
            // Start transaction
            $conn->begin_transaction();

            // Insert property
            $stmt = $conn->prepare("INSERT INTO properties (owner_id, title, address, city, state, price, bedrooms, bathrooms, area, type, furnishing, description, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssdiiisssi", $_SESSION['user_id'], $title, $address, $city, $state, $price, $bedrooms, $bathrooms, $area, $type, $furnishing, $description, $featured);
            $stmt->execute();

            $property_id = $conn->insert_id;

            // Handle image uploads
            $uploadDir = 'uploads/properties/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $uploadedImages = [];

            if (!empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $file_name = $_FILES['images']['name'][$key];
                    $file_size = $_FILES['images']['size'][$key];
                    $file_tmp = $_FILES['images']['tmp_name'][$key];
                    $file_type = $_FILES['images']['type'][$key];

                    // Generate unique filename
                    $uniqueName = uniqid() . '_' . $file_name;
                    $targetFile = $uploadDir . $uniqueName;

                    // Check if image file is an actual image
                    $check = getimagesize($file_tmp);
                    if ($check !== false) {
                        // Check file size (limit to 5MB)
                        if ($file_size > 5000000) {
                            $errors[] = "File $file_name is too large. Max size is 5MB.";
                            continue;
                        }

                        // Allow certain file formats
                        $allowed = ["image/jpeg", "image/jpg", "image/png"];
                        if (!in_array($file_type, $allowed)) {
                            $errors[] = "Only JPG, JPEG, PNG files are allowed.";
                            continue;
                        }

                        if (move_uploaded_file($file_tmp, $targetFile)) {
                            $uploadedImages[] = $targetFile;

                            // Insert image record
                            $stmt = $conn->prepare("INSERT INTO property_images (property_id, image_url) VALUES (?, ?)");
                            $stmt->bind_param("is", $property_id, $targetFile);
                            $stmt->execute();
                        } else {
                            $errors[] = "Failed to upload $file_name.";
                        }
                    } else {
                        $errors[] = "File $file_name is not a valid image.";
                    }
                }
            }

            // Insert amenities
            if (!empty($amenity_ids)) {
                $stmt = $conn->prepare("INSERT INTO property_amenities (property_id, amenity_id) VALUES (?, ?)");
                foreach ($amenity_ids as $amenity_id) {
                    $stmt->bind_param("ii", $property_id, $amenity_id);
                    $stmt->execute();
                }
            }

            // Commit transaction
            $conn->commit();

            $_SESSION['success'] = "Property added successfully!";
            header("Location: dashboard.php");
            exit();
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">
    <?php include 'components/header.php'; ?>

    <main class="container mx-auto py-8 px-4">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Add New Property</h1>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="add_property.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Property Title*</label>
                        <input type="text" id="title" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Property Type*</label>
                        <select id="type" name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                            <option value="">Select Type</option>
                            <option value="Apartment">Apartment</option>
                            <option value="House">House</option>
                            <option value="Condo">Condo</option>
                            <option value="Townhouse">Townhouse</option>
                            <option value="Villa">Villa</option>
                            <option value="Office">Office</option>
                            <option value="Commercial">Commercial</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address*</label>
                    <input type="text" id="address" name="address" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City*</label>
                        <input type="text" id="city" name="city" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State*</label>
                        <input type="text" id="state" name="state" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price ($)*</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>

                    <div>
                        <label for="area" class="block text-sm font-medium text-gray-700 mb-1">Area (sq ft)*</label>
                        <input type="number" id="area" name="area" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">Bedrooms*</label>
                        <input type="number" id="bedrooms" name="bedrooms" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>

                    <div>
                        <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">Bathrooms*</label>
                        <input type="number" id="bathrooms" name="bathrooms" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>

                    <div>
                        <label for="furnishing" class="block text-sm font-medium text-gray-700 mb-1">Furnishing</label>
                        <select id="furnishing" name="furnishing" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                            <option value="">Select Furnishing</option>
                            <option value="Furnished">Furnished</option>
                            <option value="Semi-Furnished">Semi-Furnished</option>
                            <option value="Unfurnished">Unfurnished</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>

                    <!-- List of amenities with checkboxes and delete -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <?php foreach ($amenities as $amenity): ?>
                            <div class="flex items-center justify-between border p-2 rounded-md">
                                <label class="flex items-center gap-2 w-full">
                                    <input type="checkbox" name="amenities[]" value="<?= $amenity['id'] ?>" class="h-4 w-4 text-gray-900 focus:ring-gray-500 border-gray-300 rounded">
                                    <span><?= htmlspecialchars($amenity['name']) ?></span>
                                </label>
                                <button type="submit" name="delete_amenity" value="<?= $amenity['id'] ?>" class="text-red-500 hover:text-red-700">✕</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>




                <div class="mb-6">
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Property Images</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/jpeg, image/png" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    <p class="mt-1 text-xs text-gray-500">You can upload multiple images. Accepted formats: JPG, PNG. Max size: 5MB per image.</p>
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="featured" name="featured" class="h-4 w-4 text-gray-900 focus:ring-gray-500 border-gray-300 rounded">
                    <label for="featured" class="ml-2 text-sm text-gray-700">Mark as Featured Property</label>
                </div>

                <div class="flex justify-end">
                    <a href="dashboard.php" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 mr-4">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-10 px-6">
                        Add Property
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>