<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Property Details - HomeHaven</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">
  <?php include 'components/header.php'; ?>

  <a href="properties.php" class="flex items-center gap-1 text-md text-gray-700 font-medium mx-5 mt-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Go back
  </a>

  <main class="min-h-screen pt-8 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <?php
      include 'config.php';

      // Amenity icons
      $amenityIcons = [
        'WiFi' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" /></svg>',
        'Air Conditioning' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
        'Pool' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9 3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>',
        'Parking' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'Gym' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" /></svg>',
        'Security' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>',
        'Laundry' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>',
        'Balcony' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>',
        'Pet Friendly' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" /></svg>',
        'Elevator' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>',
        'Garden' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>'
      ];

      $propertyId = $_GET['id'] ?? null;
      $property = null;
      $isOwner = false;
      $isRented = false;
      $currentUserId = $_SESSION['userId'] ?? $_SESSION['user_id'] ?? $_SESSION['id'] ?? null;
      $isLoggedIn = $currentUserId !== null;

      if ($propertyId && is_numeric($propertyId)) {
        $stmt = $conn->prepare("
          SELECT p.*, u.name as owner_name, u.email as owner_email, u.phone as owner_phone, u.avatar as owner_avatar, u.userId as owner_id
          FROM properties p
          JOIN users u ON p.owner_id = u.userId
          WHERE p.id = ?
        ");
        $stmt->bind_param("i", $propertyId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          $property = $result->fetch_assoc();
          $isOwner = $isLoggedIn && (string)$currentUserId === (string)$property['owner_id'];

          // Check if property is currently rented based on rental_agreements table
          $rentalCheckStmt = $conn->prepare("
            SELECT COUNT(*) as active_rentals 
            FROM rental_agreements 
            WHERE property_id = ? 
            AND status = 'active' 
            AND start_date <= CURDATE() 
            AND (end_date IS NULL OR end_date >= CURDATE())
          ");
          $rentalCheckStmt->bind_param("i", $propertyId);
          $rentalCheckStmt->execute();
          $rentalResult = $rentalCheckStmt->get_result();
          $rentalData = $rentalResult->fetch_assoc();
          $isRented = $rentalData['active_rentals'] > 0;

          // Property images
          $stmt = $conn->prepare("SELECT image_url FROM property_images WHERE property_id = ?");
          $stmt->bind_param("i", $propertyId);
          $stmt->execute();
          $imagesResult = $stmt->get_result();
          $property['images'] = [];
          while ($img = $imagesResult->fetch_assoc()) $property['images'][] = $img['image_url'];
          if (empty($property['images'])) $property['images'][] = 'https://via.placeholder.com/800x600?text=No+Image+Available';

          // Amenities
          $stmt = $conn->prepare("
            SELECT a.name
            FROM property_amenities pa
            JOIN amenities a ON pa.amenity_id = a.id
            WHERE pa.property_id = ?
          ");
          $stmt->bind_param("i", $propertyId);
          $stmt->execute();
          $amenitiesResult = $stmt->get_result();
          $property['amenities'] = [];
          while ($amenity = $amenitiesResult->fetch_assoc()) $property['amenities'][] = $amenity['name'];
        }
      }

      if (!$property) {
        echo '<div class="text-center py-24"><h2 class="text-2xl font-semibold text-gray-500">Property not found.</h2></div>';
      } else {
      ?>

        <!-- Property Image -->
        <div class="mb-10">
          <img src="<?php echo htmlspecialchars($property['images'][0]); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="w-full h-[400px] md:h-[500px] object-cover rounded-xl shadow-sm" />
        </div>

        <!-- Property Info Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Header -->
            <div>
              <div class="flex items-center gap-2 mb-2">
                <span class="inline-block bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full">
                  <?php echo htmlspecialchars($property['type']); ?>
                </span>
                <?php if ($isRented): ?>
                  <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">
                    🏠 RENTED
                  </span>
                <?php else: ?>
                  <span class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                    ✅ AVAILABLE
                  </span>
                <?php endif; ?>
              </div>
              <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                <?php echo htmlspecialchars($property['title']); ?>
              </h1>
              <p class="flex items-center text-gray-600 mb-4 text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <?php echo htmlspecialchars($property['address'] . ', ' . $property['city'] . ', ' . $property['state']); ?>
              </p>
              <div class="flex flex-wrap items-center gap-6 border-y border-gray-200 py-4 text-gray-700 text-sm">
                <div class="flex items-center gap-2">🛏 <?php echo htmlspecialchars($property['bedrooms']); ?> Bedrooms</div>
                <div class="flex items-center gap-2">🛁 <?php echo htmlspecialchars($property['bathrooms']); ?> Bathrooms</div>
                <div class="flex items-center gap-2">📏 <?php echo htmlspecialchars($property['area']); ?> sq ft</div>
              </div>
            </div>

            <!-- Rental Status Alert -->
            <?php if ($isRented): ?>
              <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                <div class="flex items-center gap-3">
                  <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-red-800">Property Currently Rented</h3>
                    <p class="text-red-700">This property is not available for new rental applications or visits at this time. Please check back later or browse other available properties.</p>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <!-- Description -->
            <section class="bg-white p-6 rounded-xl shadow border">
              <h2 class="text-xl font-semibold mb-4">About this property</h2>
              <p class="leading-relaxed text-gray-700 break-words whitespace-pre-wrap">
                <?php echo htmlspecialchars($property['description']); ?>
              </p>
            </section>

            <!-- Amenities -->
            <?php if (!empty($property['amenities'])): ?>
              <section class="bg-white p-6 rounded-xl shadow border">
                <h2 class="text-xl font-semibold mb-4">Amenities</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                  <?php foreach ($property['amenities'] as $amenity): ?>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                      <?php echo $amenityIcons[$amenity] ?? '🏠'; ?>
                      <span><?php echo htmlspecialchars($amenity); ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              </section>
            <?php endif; ?>

            <!-- Owner Info -->
            <div class="bg-white p-6 rounded-xl shadow border">
              <h3 class="text-lg font-semibold mb-4">Property Owner</h3>
              <div class="flex items-center gap-4 mb-4">
                <img src="<?php echo !empty($property['owner_avatar']) ? htmlspecialchars($property['owner_avatar']) : 'images/default-avatar.png'; ?>" alt="<?php echo htmlspecialchars($property['owner_name']); ?>" class="w-14 h-14 rounded-full object-cover" />
                <div>
                  <h4 class="font-semibold"><?php echo htmlspecialchars($property['owner_name']); ?></h4>
                  <p class="text-sm text-gray-500">Owner</p>
                </div>
              </div>

              <?php if ($isOwner): ?>
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                  <p class="text-blue-800 text-sm font-medium">✅ This is your property listing</p>
                </div>
              <?php elseif ($isLoggedIn && !$isRented): ?>
                <div class="space-y-2">
                  <?php if (!empty($property['owner_phone'])): ?>
                    <a href="tel:<?php echo htmlspecialchars($property['owner_phone']); ?>" class="w-full flex items-center justify-center py-2 border border-gray-300 rounded-md hover:bg-gray-100 text-sm transition-colors">
                      📞 Call Now
                    </a>
                  <?php endif; ?>
                  <a href="mailto:<?php echo htmlspecialchars($property['owner_email']); ?>?subject=Inquiry about <?php echo urlencode($property['title']); ?>" class="w-full flex items-center justify-center py-2 border border-gray-300 rounded-md hover:bg-gray-100 text-sm transition-colors">
                    ✉️ Send Message
                  </a>
                </div>
              <?php elseif ($isRented): ?>
                <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                  <p class="text-gray-700 text-sm">Property is currently rented. Contact information not available.</p>
                </div>
              <?php else: ?>
                <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                  <p class="text-gray-700 text-sm mb-2">Please log in to contact the property owner</p>
                  <a href="login.php" class="inline-block px-4 py-2 bg-gray-900 text-white text-sm rounded-md hover:bg-gray-800 transition-colors">
                    Log In
                  </a>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Sidebar -->
          <aside class="space-y-6 lg:col-span-1 lg:self-start">
            <div class="lg:sticky lg:top-24 space-y-6">
              <!-- Booking / Schedule Visit -->
              <div class="bg-white p-6 rounded-xl shadow border">
                <div class="text-center mb-6">
                  <p class="text-3xl font-bold text-gray-900">$<?php echo number_format($property['price']); ?><span class="text-sm font-medium text-gray-500"> / month</span></p>
                  <?php if (!empty($property['furnishing'])): ?>
                    <span class="inline-block mt-2 bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded-full">
                      <?php echo htmlspecialchars($property['furnishing']); ?>
                    </span>
                  <?php endif; ?>
                </div>

                <?php if ($isOwner): ?>
                  <div class="bg-blue-50 border border-blue-200 p-4 rounded-md text-center">
                    <p class="text-gray-700 mb-3">✅ This is your property listing</p>
                    <a href="edit_property.php?id=<?php echo $propertyId; ?>" class="inline-block w-full px-4 py-3 bg-gray-900 text-white font-semibold rounded-md hover:bg-gray-800 transition-colors mb-2">
                      Edit Property
                    </a>
                    <a href="manage_applications.php?property_id=<?php echo $propertyId; ?>" class="inline-block w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors mb-2">
                      Manage Rental Applications
                    </a>
                    <?php if ($isRented): ?>
                      <a href="manage_rentals.php?property_id=<?php echo $propertyId; ?>" class="inline-block w-full px-4 py-3 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition-colors">
                        Manage Current Rental
                      </a>
                    <?php endif; ?>
                  </div>
                <?php elseif ($isRented): ?>
                  <!-- Property is rented - show unavailable message -->
                  <div class="bg-red-50 border border-red-200 p-4 rounded-md text-center">
                    <div class="flex items-center justify-center mb-3">
                      <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                      </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-red-800 mb-2">Property Not Available</h3>
                    <p class="text-red-700 text-sm mb-4">This property is currently rented and not accepting new applications or visit requests.</p>
                    <a href="properties.php" class="inline-block w-full px-4 py-3 bg-gray-600 text-white font-semibold rounded-md hover:bg-gray-700 transition-colors">
                      Browse Other Properties
                    </a>
                  </div>
                <?php elseif ($isLoggedIn): ?>
                  <!-- Property is available for logged-in users -->
                  <a href="apply_rental.php?property_id=<?php echo $propertyId; ?>" class="block w-full px-4 py-3 bg-blue-600 text-white text-center font-semibold rounded-md hover:bg-blue-700 transition-colors mb-2">
                    Apply for Rental
                  </a>
                  <form action="schedule_visit.php" method="POST" class="space-y-4">
                    <input type="hidden" name="property_id" value="<?php echo $propertyId; ?>">
                    <div>
                      <label for="visit-date" class="block text-sm font-medium text-gray-700 mb-1">Visit Date</label>
                      <input type="date" id="visit-date" name="visit_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900" min="<?php echo date('Y-m-d'); ?>" />
                    </div>
                    <div>
                      <label for="visit-time" class="block text-sm font-medium text-gray-700 mb-1">Preferred Time</label>
                      <select id="visit-time" name="visit_time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                        <option value="">Select time</option>
                        <option value="09:00">9:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="17:00">5:00 PM</option>
                      </select>
                    </div>
                    <div>
                      <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message (Optional)</label>
                      <textarea id="message" name="message" rows="3" placeholder="Any specific questions or requirements..." class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 bg-gray-900 text-white font-semibold rounded-md hover:bg-gray-800 transition-colors">
                      Schedule Visit
                    </button>
                  </form>
                <?php else: ?>
                  <!-- Not logged in -->
                  <div class="bg-gray-100 p-4 rounded-md text-center">
                    <p class="text-gray-700 mb-3">Please log in to apply for rental or schedule a visit</p>
                    <a href="login.php?redirect=property.php?id=<?php echo $propertyId; ?>"
                      class="inline-block w-full px-4 py-3 bg-gray-900 text-white font-semibold rounded-md hover:bg-gray-800 transition-colors">
                      Log In to Continue
                    </a>
                  </div>

                <?php endif; ?>
              </div>

              <!-- Additional Info -->
              <div class="bg-white p-6 rounded-xl shadow border">
                <h3 class="text-lg font-semibold mb-4">Property Details</h3>
                <div class="space-y-3 text-sm">
                  <div class="flex justify-between">
                    <span class="text-gray-600">Property Type</span>
                    <span class="font-medium"><?php echo htmlspecialchars($property['type'] ?? 'N/A'); ?></span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Status</span>
                    <span class="font-medium <?php echo $isRented ? 'text-red-600' : 'text-green-600'; ?>">
                      <?php echo $isRented ? 'Rented' : 'Available'; ?>
                    </span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Furnishing</span>
                    <span class="font-medium"><?php echo htmlspecialchars($property['furnishing'] ?? 'N/A'); ?></span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Area</span>
                    <span class="font-medium"><?php echo htmlspecialchars($property['area']); ?> sq ft</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Bedrooms</span>
                    <span class="font-medium"><?php echo htmlspecialchars($property['bedrooms']); ?></span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Bathrooms</span>
                    <span class="font-medium"><?php echo htmlspecialchars($property['bathrooms']); ?></span>
                  </div>
                </div>
              </div>

              <!-- Current Rental Info (if rented and owner) -->
              <?php if ($isRented && $isOwner): ?>
                <div class="bg-orange-50 border border-orange-200 p-4 rounded-xl">
                  <h3 class="text-lg font-semibold mb-3 text-orange-800">Current Rental Status</h3>
                  <?php
                  // Get current rental details
                  $currentRentalStmt = $conn->prepare("
                    SELECT ra.*, u.name as tenant_name, u.email as tenant_email, u.phone as tenant_phone
                    FROM rental_agreements ra
                    JOIN users u ON ra.tenant_id = u.userId
                    WHERE ra.property_id = ? 
                    AND ra.status = 'active' 
                    AND ra.start_date <= CURDATE() 
                    AND (ra.end_date IS NULL OR ra.end_date >= CURDATE())
                    ORDER BY ra.start_date DESC
                    LIMIT 1
                  ");
                  $currentRentalStmt->bind_param("i", $propertyId);
                  $currentRentalStmt->execute();
                  $currentRental = $currentRentalStmt->get_result()->fetch_assoc();

                  if ($currentRental):
                  ?>
                    <div class="space-y-2 text-sm">
                      <div class="flex justify-between">
                        <span class="text-gray-600">Tenant:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($currentRental['tenant_name']); ?></span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-600">Start Date:</span>
                        <span class="font-medium"><?php echo date('M j, Y', strtotime($currentRental['start_date'])); ?></span>
                      </div>
                      <?php if ($currentRental['end_date']): ?>
                        <div class="flex justify-between">
                          <span class="text-gray-600">End Date:</span>
                          <span class="font-medium"><?php echo date('M j, Y', strtotime($currentRental['end_date'])); ?></span>
                        </div>
                      <?php endif; ?>
                      <div class="flex justify-between">
                        <span class="text-gray-600">Monthly Rent:</span>
                        <span class="font-medium">$<?php echo number_format($currentRental['monthly_rent']); ?></span>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </aside>
        </div>
      <?php } ?>
    </div>
  </main>

  <?php include 'components/footer.php'; ?>
</body>

</html>