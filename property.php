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
        require 'data/mockData.php';
        $propertyId = $_GET['id'] ?? null;
        $property = null;
        if ($propertyId) {
          foreach ($mockProperties as $p) {
            if ($p['id'] == $propertyId) {
              $property = $p;
              break;
            }
          }
        }

        if (!$property) {
          echo '<div class="text-center py-24"><h2 class="text-2xl font-semibold text-gray-500">Property not found.</h2></div>';
        } else {
          // Amenity icons declared here
      ?>


      <!-- Property Image Gallery -->
      <div class="mb-10">
        <img src="<?php echo htmlspecialchars($property['images'][0]); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="w-full h-[400px] md:h-[500px] object-cover rounded-xl shadow-sm" />
      </div>

      <!-- Property Info -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Header -->
          <div>
            <span class="inline-block bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full mb-2">
              <?php echo htmlspecialchars($property['type']); ?>
            </span>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
              <?php echo htmlspecialchars($property['title']); ?>
            </h1>
            <p class="flex items-center text-gray-600 mb-4 text-sm">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
              <?php echo htmlspecialchars($property['address'] . ', ' . $property['city'] . ', ' . $property['state']); ?>
            </p>
            <div class="flex flex-wrap items-center gap-6 border-y border-gray-200 py-4 text-gray-700 text-sm">
              <div class="flex items-center gap-2">
                🛏 <?php echo htmlspecialchars($property['bedrooms']); ?> Bedrooms
              </div>
              <div class="flex items-center gap-2">
                🛁 <?php echo htmlspecialchars($property['bathrooms']); ?> Bathrooms
              </div>
              <div class="flex items-center gap-2">
                📏 <?php echo htmlspecialchars($property['area']); ?> sq ft
              </div>
            </div>
          </div>

          <!-- Description -->
          <section class="bg-white p-6 rounded-xl shadow border">
            <h2 class="text-xl font-semibold mb-4">About this property</h2>
            <p class="leading-relaxed text-gray-700">
              <?php echo htmlspecialchars($property['description']); ?>
            </p>
          </section>

          <!-- Amenities -->
          <section class="bg-white p-6 rounded-xl shadow border">
            <h2 class="text-xl font-semibold mb-4">Amenities</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
              <?php foreach ($property['amenities'] as $amenity): ?>
              <div class="flex items-center gap-3 text-sm text-gray-600">
                <?php echo $amenityIcons[$amenity] ?? ''; ?>
                <span><?php echo htmlspecialchars($amenity); ?></span>
              </div>
              <?php endforeach; ?>
            </div>
          </section>

          <!-- Owner Info -->
          <div class="bg-white p-6 rounded-xl shadow border">
            <h3 class="text-lg font-semibold mb-4">Property Owner</h3>
            <div class="flex items-center gap-4 mb-4">
              <img src="<?php echo htmlspecialchars($property['owner']['avatar']); ?>" alt="<?php echo htmlspecialchars($property['owner']['name']); ?>" class="w-14 h-14 rounded-full" />
              <div>
                <h4 class="font-semibold"><?php echo htmlspecialchars($property['owner']['name']); ?></h4>
                <p class="text-sm text-gray-500">Owner</p>
              </div>
            </div>
            <div class="space-y-2">
              <a href="tel:<?php echo htmlspecialchars($property['owner']['phone']); ?>" class="w-full flex items-center justify-center py-2 border border-gray-300 rounded-md hover:bg-gray-100 text-sm">
                📞 Call Now
              </a>
              <a href="mailto:<?php echo htmlspecialchars($property['owner']['email']); ?>" class="w-full flex items-center justify-center py-2 border border-gray-300 rounded-md hover:bg-gray-100 text-sm">
                ✉️ Send Message
              </a>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <aside class="space-y-6">
          <!-- Booking -->
          <div class="bg-white p-6 rounded-xl shadow border sticky top-24">
            <div class="text-center mb-6">
              <p class="text-3xl font-bold text-gray-900">$<?php echo number_format($property['price']); ?><span class="text-sm font-medium text-gray-500"> / month</span></p>
              <span class="inline-block mt-2 bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded-full">
                <?php echo htmlspecialchars($property['furnishing']); ?>
              </span>
            </div>
            <form action="#" class="space-y-4">
              <div>
                <label for="visit-date" class="block text-sm font-medium text-gray-700 mb-1">Visit Date</label>
                <input type="date" id="visit-date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900" />
              </div>
              <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea id="message" rows="4" placeholder="Your message..." class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900"></textarea>
              </div>
              <button type="submit" class="w-full py-3 bg-gray-900 text-white font-semibold rounded-md hover:bg-gray-800 transition">
                Schedule Visit
              </button>
            </form>
          </div>

          
        </aside>
      </div>
      <?php } ?>
    </div>
  </main>

  <?php include 'components/footer.php'; ?>
</body>
</html>
