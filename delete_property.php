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
    $_SESSION['error'] = "You don't have permission to delete this property or it doesn't exist.";
    header("Location: dashboard.php");
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();
    
    // Delete property images from database (and optionally from file system)
    $stmt = $conn->prepare("SELECT image_url FROM property_images WHERE property_id = ?");
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    $images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Delete image files from server
    foreach ($images as $image) {
        if (file_exists($image['image_url'])) {
            unlink($image['image_url']);
        }
    }
    
    // Delete related records from property_amenities table
    $stmt = $conn->prepare("DELETE FROM property_amenities WHERE property_id = ?");
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    
    // Delete related records from property_images table
    $stmt = $conn->prepare("DELETE FROM property_images WHERE property_id = ?");
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    
    // Delete related records from testimonials table
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE property_id = ?");
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    
    // Delete the property
    $stmt = $conn->prepare("DELETE FROM properties WHERE id = ?");
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    
    // Commit transaction
    $conn->commit();
    
    $_SESSION['success'] = "Property deleted successfully.";
    
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $_SESSION['error'] = "Error deleting property: " . $e->getMessage();
}

// Redirect back to dashboard
header("Location: dashboard.php");
exit();
?>