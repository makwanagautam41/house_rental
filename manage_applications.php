<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=manage_applications.php");
    exit();
}

$userId = $_SESSION['user_id'];
$propertyId = $_GET['property_id'] ?? null;
$error = '';
$success = '';

// Process application status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id']) && isset($_POST['status'])) {
    $applicationId = $_POST['application_id'];
    $status = $_POST['status'];
    
    if ($status === 'approved' || $status === 'rejected') {
        // Verify ownership of the property
        $stmt = $conn->prepare("
            SELECT p.owner_id 
            FROM rental_applications ra
            JOIN properties p ON ra.property_id = p.id
            WHERE ra.id = ? AND p.owner_id = ?
        ");
        $stmt->bind_param("ii", $applicationId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update application status
            $stmt = $conn->prepare("UPDATE rental_applications SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $applicationId);
            
            if ($stmt->execute()) {
                $success = "Application status updated successfully!";
                
                // If approved, create rental agreement
                if ($status === 'approved') {
                    // Get application details
                    $stmt = $conn->prepare("
                        SELECT ra.*, p.price, p.owner_id
                        FROM rental_applications ra
                        JOIN properties p ON ra.property_id = p.id
                        WHERE ra.id = ?
                    ");
                    $stmt->bind_param("i", $applicationId);
                    $stmt->execute();
                    $appResult = $stmt->get_result();
                    $application = $appResult->fetch_assoc();
                    
                    // Create rental agreement
                    $securityDeposit = $application['price'] * 2; // Two months rent as security deposit
                    $terms = "Standard rental agreement terms apply. Security deposit is refundable upon successful completion of the lease term without damages.";
                    
                    $stmt = $conn->prepare("
                        INSERT INTO rental_agreements 
                        (application_id, property_id, tenant_id, owner_id, start_date, end_date, monthly_rent, security_deposit, terms)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->bind_param(
                        "iiisssdds", 
                        $applicationId, 
                        $application['property_id'], 
                        $application['user_id'], 
                        $application['owner_id'],
                        $application['start_date'], 
                        $application['end_date'], 
                        $application['price'], 
                        $securityDeposit, 
                        $terms
                    );
                    $stmt->execute();
                }
            } else {
                $error = "Error updating application status: " . $conn->error;
            }
        } else {
            $error = "You don't have permission to update this application.";
        }
    } else {
        $error = "Invalid status value.";
    }
}

// Fetch property details if property ID is provided
$property = null;
if ($propertyId) {
    $stmt = $conn->prepare("SELECT * FROM properties WHERE id = ? AND owner_id = ?");
    $stmt->bind_param("ii", $propertyId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();
    
    if (!$property) {
        header("Location: dashboard.php");
        exit();
    }
}

// Fetch all applications for the property or all properties owned by the user
$applications = [];
if ($propertyId) {
    $stmt = $conn->prepare("
        SELECT ra.*, p.title as property_title, u.name as applicant_name, u.email as applicant_email, u.phone as applicant_phone
        FROM rental_applications ra
        JOIN properties p ON ra.property_id = p.id
        JOIN users u ON ra.user_id = u.userId
        WHERE p.id = ? AND p.owner_id = ?
        ORDER BY ra.created_at DESC
    ");
    $stmt->bind_param("ii", $propertyId, $userId);
} else {
    $stmt = $conn->prepare("
        SELECT ra.*, p.title as property_title, u.name as applicant_name, u.email as applicant_email, u.phone as applicant_phone
        FROM rental_applications ra
        JOIN properties p ON ra.property_id = p.id
        JOIN users u ON ra.user_id = u.userId
        WHERE p.owner_id = ?
        ORDER BY ra.created_at DESC
    ");
    $stmt->bind_param("i", $userId);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rental Applications - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <?php include 'components/header.php'; ?>
    
    <main class="min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">
                    <?php if ($property): ?>
                        Rental Applications for <?php echo htmlspecialchars($property['title']); ?>
                    <?php else: ?>
                        All Rental Applications
                    <?php endif; ?>
                </h1>
                
                <?php if ($property): ?>
                    <a href="property.php?id=<?php echo $propertyId; ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Back to Property
                    </a>
                <?php else: ?>
                    <a href="dashboard.php" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Back to Dashboard
                    </a>
                <?php endif; ?>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-50 text-red-700 p-4 rounded-md mb-6">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-md mb-6">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($applications)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-500">No rental applications found.</p>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <?php if (!$property): ?>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Property
                                    </th>
                                <?php endif; ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Applicant
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dates
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Budget
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <?php if (!$property): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="property.php?id=<?php echo $app['property_id']; ?>" class="text-blue-600 hover:underline">
                                                <?php echo htmlspecialchars($app['property_title']); ?>
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($app['applicant_name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($app['applicant_email']); ?></div>
                                        <?php if (!empty($app['applicant_phone'])): ?>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($app['applicant_phone']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">Move-in:</span> <?php echo date('M j, Y', strtotime($app['start_date'])); ?>
                                        </div>
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">Move-out:</span> <?php echo date('M j, Y', strtotime($app['end_date'])); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">$<?php echo number_format($app['monthly_budget'], 2); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo $app['occupants']; ?> occupants</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($app['status'] === 'pending'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        <?php elseif ($app['status'] === 'approved'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button type="button" class="text-blue-600 hover:text-blue-900 mr-3" onclick="showApplicationDetails(<?php echo $app['id']; ?>)">
                                            View Details
                                        </button>
                                        
                                        <?php if ($app['status'] === 'pending'): ?>
                                            <form method="POST" action="" class="inline-block">
                                                <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                    Approve
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="" class="inline-block">
                                                <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Reject
                                                </button>
                                            </form>
                                        <?php elseif ($app['status'] === 'approved'): ?>
                                            <a href="view_agreement.php?application_id=<?php echo $app['id']; ?>" class="text-indigo-600 hover:text-indigo-900">
                                                View Agreement
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                
                                <!-- Application Details Modal (Hidden by default) -->
                                <tr id="details-<?php echo $app['id']; ?>" class="hidden bg-gray-50">
                                    <td colspan="<?php echo $property ? '5' : '6'; ?>" class="px-6 py-4">
                                        <div class="text-sm text-gray-900 mb-2">
                                            <span class="font-medium">Application Date:</span> 
                                            <?php echo date('F j, Y', strtotime($app['created_at'])); ?>
                                        </div>
                                        <?php if (!empty($app['message'])): ?>
                                            <div class="mb-2">
                                                <span class="font-medium text-sm">Message from Applicant:</span>
                                                <p class="text-gray-700 mt-1 whitespace-pre-wrap"><?php echo htmlspecialchars($app['message']); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include 'components/footer.php'; ?>
    
    <script>
        function showApplicationDetails(id) {
            const detailsRow = document.getElementById(`details-${id}`);
            if (detailsRow.classList.contains('hidden')) {
                detailsRow.classList.remove('hidden');
            } else {
                detailsRow.classList.add('hidden');
            }
        }
    </script>
</body>
</html>