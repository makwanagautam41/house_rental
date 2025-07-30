<?php
// Expects a $property variable to be defined before including this file.
// $isFavorite is also expected.
?>

<div class="group rounded-xl overflow-hidden border border-gray-200 bg-white shadow-sm hover:shadow-xl transition-all duration-300">
  <!-- Image & Label -->
  <div class="relative">
    <a href="property.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="block aspect-[4/3] overflow-hidden">
      <img src="<?php echo htmlspecialchars($property['images'][0]); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
    </a>
    <div class="absolute top-3 left-3">
        <span class="inline-block bg-gray-900/75 text-white text-xs font-semibold px-3 py-1 rounded-full">
          <?php echo htmlspecialchars($property['type']); ?>
        </span>
    </div>
    <!-- Favorite Icon -->
    <button class="absolute top-3 right-3 h-9 w-9 flex items-center justify-center rounded-full bg-white/80 hover:bg-white shadow-md transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 <?php echo $isFavorite ? 'fill-red-500 text-red-500' : 'text-gray-500 hover:text-red-500'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" />
      </svg>
    </button>
  </div>

  <!-- Content -->
  <div class="p-5">
    <!-- Title and Price -->
    <div class="flex justify-between items-start mb-3">
      <div class="flex-1">
        <h3 class="text-lg font-semibold text-gray-900 line-clamp-1">
          <a href="property.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="hover:text-gray-700 transition-colors">
            <?php echo htmlspecialchars($property['title']); ?>
          </a>
        </h3>
        <div class="mt-1 flex items-center text-sm text-gray-500">
          <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" /><circle cx="12" cy="10" r="3" /></svg>
          <span><?php echo htmlspecialchars($property['city']); ?>, <?php echo htmlspecialchars($property['state']); ?></span>
        </div>
      </div>
      <div class="text-right flex-shrink-0">
        <p class="text-xl font-bold text-gray-900">$<?php echo number_format($property['price']); ?></p>
        <p class="text-xs text-gray-500">/month</p>
      </div>
    </div>

    <!-- Features -->
    <div class="border-t border-gray-100 pt-3">
        <div class="flex justify-between items-center text-sm text-gray-600">
            <div class="flex items-center gap-4">
                <!-- Beds -->
                <div class="flex items-center gap-1.5">
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0-3.517 2.5-6.5 5.5-6.5S23 7.483 23 11v9h-4v-2a1 1 0 00-1-1h-4a1 1 0 00-1 1v2H9v-2a1 1 0 00-1-1H4a1 1 0 00-1 1v2H1v-9c0-3.517 2.5-6.5 5.5-6.5S12 7.483 12 11z"/></svg>
                  <span><?php echo htmlspecialchars($property['bedrooms']); ?></span>
                </div>
                <!-- Baths -->
                <div class="flex items-center gap-1.5">
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 6l-4 4v10h14V10l-4-4M9 6V4a2 2 0 012-2h2a2 2 0 012 2v2m-6 0h6"/></svg>
                  <span><?php echo htmlspecialchars($property['bathrooms']); ?></span>
                </div>
                <!-- Area -->
                <div class="flex items-center gap-1.5">
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8V4h4m12 4V4h-4M4 16v4h4m12-4v4h-4"/></svg>
                  <span><?php echo htmlspecialchars($property['area']); ?> sqft</span>
                </div>
            </div>
            <span class="inline-block bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full">
                <?php echo htmlspecialchars($property['furnishing']); ?>
            </span>
        </div>
    </div>
  </div>
</div>
