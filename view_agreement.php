<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=view_agreement.php");
    exit();
}

$userId = $_SESSION['user_id'];
$applicationId = $_GET['application_id'] ?? null;
$error = '';

// Fetch agreement details
$agreement = null;
if ($applicationId) {
    $stmt = $conn->prepare("
        SELECT ra.*, a.*, p.title as property_title, p.address, p.city, p.state,
               tenant.name as tenant_name, tenant.email as tenant_email, tenant.phone as tenant_phone,
               owner.name as owner_name, owner.email as owner_email, owner.phone as owner_phone
        FROM rental_agreements ra
        JOIN rental_applications a ON ra.application_id = a.id
        JOIN properties p ON ra.property_id = p.id
        JOIN users tenant ON ra.tenant_id = tenant.userId
        JOIN users owner ON ra.owner_id = owner.userId
        WHERE ra.application_id = ? AND (ra.tenant_id = ? OR ra.owner_id = ?)
    ");
    $stmt->bind_param("iii", $applicationId, $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $agreement = $result->fetch_assoc();
    } else {
        $error = "Agreement not found or you don't have permission to view it.";
    }
} else {
    $error = "No application ID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Agreement - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <?php include 'components/header.php'; ?>
    
    <main class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if ($error): ?>
                <div class="bg-red-50 text-red-700 p-4 rounded-md mb-6">
                    <?php echo $error; ?>
                </div>
                <div class="text-center">
                    <a href="dashboard.php" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Back to Dashboard
                    </a>
                </div>
            <?php elseif ($agreement): ?>
                <div class="bg-white rounded-lg shadow-md p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h1 class="text-2xl font-bold text-gray-900">Rental Agreement</h1>
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print Agreement
                        </button>
                    </div>
                    
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <h2 class="text-lg font-semibold mb-4">Property Details</h2>
                        <p class="text-gray-700"><span class="font-medium">Property:</span> <?php echo htmlspecialchars($agreement['property_title']); ?></p>
                        <p class="text-gray-700"><span class="font-medium">Address:</span> <?php echo htmlspecialchars($agreement['address'] . ', ' . $agreement['city'] . ', ' . $agreement['state']); ?></p>
                    </div>
                    
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <h2 class="text-lg font-semibold mb-4">Parties</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Landlord/Owner:</h3>
                                <p class="text-gray-700"><?php echo htmlspecialchars($agreement['owner_name']); ?></p>
                                <p class="text-gray-700"><?php echo htmlspecialchars($agreement['owner_email']); ?></p>
                                <?php if (!empty($agreement['owner_phone'])): ?>
                                    <p class="text-gray-700"><?php echo htmlspecialchars($agreement['owner_phone']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Tenant:</h3>
                                <p class="text-gray-700"><?php echo htmlspecialchars($agreement['tenant_name']); ?></p>
                                <p class="text-gray-700"><?php echo htmlspecialchars($agreement['tenant_email']); ?></p>
                                <?php if (!empty($agreement['tenant_phone'])): ?>
                                    <p class="text-gray-700"><?php echo htmlspecialchars($agreement['tenant_phone']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <h2 class="text-lg font-semibold mb-4">Agreement Terms</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <p class="text-gray-700"><span class="font-medium">Start Date:</span> <?php echo date('F j, Y', strtotime($agreement['start_date'])); ?></p>
                                <p class="text-gray-700"><span class="font-medium">End Date:</span> <?php echo date('F j, Y', strtotime($agreement['end_date'])); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-700"><span class="font-medium">Monthly Rent:</span> $<?php echo number_format($agreement['monthly_rent'], 2); ?></p>
                                <p class="text-gray-700"><span class="font-medium">Security Deposit:</span> $<?php echo number_format($agreement['security_deposit'], 2); ?></p>
                            </div>
                        </div>
                        <p class="text-gray-700"><span class="font-medium">Status:</span> 
                            <?php if ($agreement['status'] === 'active'): ?>
                                <span class="text-green-600 font-medium">Active</span>
                            <?php elseif ($agreement['status'] === 'expired'): ?>
                                <span class="text-gray-600 font-medium">Expired</span>
                            <?php else: ?>
                                <span class="text-red-600 font-medium">Terminated</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold mb-4">Terms and Conditions</h2>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($agreement['terms']); ?></p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold mb-4">Signatures</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-gray-700 mb-2"><span class="font-medium">Landlord/Owner:</span></p>
                                <div class="h-16 border-b border-gray-300"></div>
                                <p class="text-gray-700 mt-2"><?php echo htmlspecialchars($agreement['owner_name']); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-700 mb-2"><span class="font-medium">Tenant:</span></p>
                                <div class="h-16 border-b border-gray-300"></div>
                                <p class="text-gray-700 mt-2"><?php echo htmlspecialchars($agreement['tenant_name']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-between">
                        <?php if ($userId === $agreement['owner_id']): ?>
                            <a href="manage_applications.php?property_id=<?php echo $agreement['property_id']; ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Back to Applications
                            </a>
                        <?php else: ?>
                            <a href="property.php?id=<?php echo $agreement['property_id']; ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Back to Property
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include 'components/footer.php'; ?>
</body>
</html>