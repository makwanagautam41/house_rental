<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "config.php";

// Check if property ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid property ID.";
    header("Location: dashboard.php");
    exit();
}

$property_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Verify that the property belongs to the current user
$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ? AND owner_id = ?");
$stmt->bind_param("ii", $property_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "You don't have permission to edit this property or it doesn't exist.";
    header("Location: dashboard.php");
    exit();
}

$property = $result->fetch_assoc();

// Get property images
$stmt = $conn->prepare("SELECT * FROM property_images WHERE property_id = ?");
$stmt->bind_param("i", $property_id);
$stmt->execute();
$images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get all amenities
$stmt = $conn->prepare("SELECT * FROM amenities ORDER BY name");
$stmt->execute();
$amenities = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get selected amenities for this property
$stmt = $conn->prepare("SELECT amenity_id FROM property_amenities WHERE property_id = ?");
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();
$selected_amenities = [];
while ($row = $result->fetch_assoc()) {
    $selected_amenities[] = $row['amenity_id'];
}

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
    
    // If no errors, proceed with update
    if (empty($errors)) {
        try {
            // Start transaction
            $conn->begin_transaction();
            
            // Update property
            $stmt = $conn->prepare("UPDATE properties SET title = ?, address = ?, city = ?, state = ?, price = ?, bedrooms = ?, bathrooms = ?, area = ?, type = ?, furnishing = ?, description = ?, featured = ? WHERE id = ?");
            $stmt->bind_param("ssssdiiisssii", $title, $address, $city, $state, $price, $bedrooms, $bathrooms, $area, $type, $furnishing, $description, $featured, $property_id);
            $stmt->execute();
            
            // Handle image uploads
            $uploadDir = 'uploads/properties/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Process new image uploads
            if (!empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    if (empty($tmp_name)) continue;
                    
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
            
            // Handle image deletions
            if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
                foreach ($_POST['delete_images'] as $image_id) {
                    // Get image URL before deleting
                    $stmt = $conn->prepare("SELECT image_url FROM property_images WHERE id = ? AND property_id = ?");
                    $stmt->bind_param("ii", $image_id, $property_id);
                    $stmt->execute();
                    $image_result = $stmt->get_result();
                    
                    if ($image_result->num_rows > 0) {
                        $image_data = $image_result->fetch_assoc();
                        $image_url = $image_data['image_url'];
                        
                        // Delete from database
                        $stmt = $conn->prepare("DELETE FROM property_images WHERE id = ?");
                        $stmt->bind_param("i", $image_id);
                        $stmt->execute();
                        
                        // Delete file from server
                        if (file_exists($image_url)) {
                            unlink($image_url);
                        }
                    }
                }
            }
            
            // Update amenities - first delete existing ones
            $stmt = $conn->prepare("DELETE FROM property_amenities WHERE property_id = ?");
            $stmt->bind_param("i", $property_id);
            $stmt->execute();
            
            // Insert new amenities
            if (!empty($amenity_ids)) {
                $stmt = $conn->prepare("INSERT INTO property_amenities (property_id, amenity_id) VALUES (?, ?)");
                foreach ($amenity_ids as $amenity_id) {
                    $stmt->bind_param("ii", $property_id, $amenity_id);
                    $stmt->execute();
                }
            }
            
            // Commit transaction
            $conn->commit();
            
            $_SESSION['success'] = "Property updated successfully!";
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
    <title>Edit Property - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php include 'components/header.php'; ?>

    <main class="container mx-auto py-8 px-4">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Edit Property</h1>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form action="edit_property.php?id=<?= $property_id ?>" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Property Title*</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($property['title']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Property Type*</label>
                        <select id="type" name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                            <option value="">Select Type</option>
                            <option value="Apartment" <?= $property['type'] == 'Apartment' ? 'selected' : '' ?>>Apartment</option>
                            <option value="House" <?= $property['type'] == 'House' ? 'selected' : '' ?>>House</option>
                            <option value="Condo" <?= $property['type'] == 'Condo' ? 'selected' : '' ?>>Condo</option>
                            <option value="Townhouse" <?= $property['type'] == 'Townhouse' ? 'selected' : '' ?>>Townhouse</option>
                            <option value="Villa" <?= $property['type'] == 'Villa' ? 'selected' : '' ?>>Villa</option>
                            <option value="Office" <?= $property['type'] == 'Office' ? 'selected' : '' ?>>Office</option>
                            <option value="Commercial" <?= $property['type'] == 'Commercial' ? 'selected' : '' ?>>Commercial</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address*</label>
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($property['address']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City*</label>
                        <input type="text" id="city" name="city" value="<?= htmlspecialchars($property['city']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                    
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State*</label>
                        <input type="text" id="state" name="state" value="<?= htmlspecialchars($property['state']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price ($)*</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($property['price']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                    
                    <div>
                        <label for="area" class="block text-sm font-medium text-gray-700 mb-1">Area (sq ft)*</label>
                        <input type="number" id="area" name="area" min="1" value="<?= htmlspecialchars($property['area']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">Bedrooms*</label>
                        <input type="number" id="bedrooms" name="bedrooms" min="0" value="<?= htmlspecialchars($property['bedrooms']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                    
                    <div>
                        <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">Bathrooms*</label>
                        <input type="number" id="bathrooms" name="bathrooms" min="0" value="<?= htmlspecialchars($property['bathrooms']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    </div>
                    
                    <div>
                        <label for="furnishing" class="block text-sm font-medium text-gray-700 mb-1">Furnishing</label>
                        <select id="furnishing" name="furnishing" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                            <option value="">Select Furnishing</option>
                            <option value="Furnished" <?= $property['furnishing'] == 'Furnished' ? 'selected' : '' ?>>Furnished</option>
                            <option value="Semi-Furnished" <?= $property['furnishing'] == 'Semi-Furnished' ? 'selected' : '' ?>>Semi-Furnished</option>
                            <option value="Unfurnished" <?= $property['furnishing'] == 'Unfurnished' ? 'selected' : '' ?>>Unfurnished</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900"><?= htmlspecialchars($property['description']) ?></textarea>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <?php foreach ($amenities as $amenity): ?>
                            <div class="flex items-center">
                                <input type="checkbox" id="amenity_<?= $amenity['id'] ?>" name="amenities[]" value="<?= $amenity['id'] ?>" <?= in_array($amenity['id'], $selected_amenities) ? 'checked' : '' ?> class="h-4 w-4 text-gray-900 focus:ring-gray-500 border-gray-300 rounded">
                                <label for="amenity_<?= $amenity['id'] ?>" class="ml-2 text-sm text-gray-700"><?= htmlspecialchars($amenity['name']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php if (count($images) > 0): ?>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php foreach ($images as $image): ?>
                            <div class="relative group">
                                <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Property Image" class="w-full h-32 object-cover rounded-md">
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-md">
                                    <label class="text-white cursor-pointer">
                                        <input type="checkbox" name="delete_images[]" value="<?= $image['id'] ?>" class="mr-2">
                                        Delete
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Check the images you want to delete.</p>
                </div>
                <?php endif; ?>
                
                <div class="mb-6">
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Add New Images</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/jpeg, image/png" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900">
                    <p class="mt-1 text-xs text-gray-500">You can upload multiple images. Accepted formats: JPG, PNG. Max size: 5MB per image.</p>
                </div>
                
                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="featured" name="featured" <?= $property['featured'] ? 'checked' : '' ?> class="h-4 w-4 text-gray-900 focus:ring-gray-500 border-gray-300 rounded">
                    <label for="featured" class="ml-2 text-sm text-gray-700">Mark as Featured Property</label>
                </div>
                
                <div class="flex justify-end">
                    <a href="dashboard.php" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 mr-4">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-10 px-6">
                        Update Property
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>
</html>