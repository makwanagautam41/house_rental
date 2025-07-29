<?php
// Expects a $property variable to be defined before including this file.
// $property = [
//     'id' => '1',
//     'images' => ['/assets/placeholder.svg'],
//     'title' => 'Cozy Apartment in Downtown',
//     'type' => 'Apartment',
//     'price' => 1200,
//     'city' => 'Metropolis',
//     'state' => 'NY',
//     'bedrooms' => 2,
//     'bathrooms' => 1,
//     'area' => 800,
//     'furnishing' => 'Furnished',
// ];
// $isFavorite = false;
?>

<div class="group overflow-hidden hover:shadow-lg transition-all duration-300 animate-fade-in rounded-lg border bg-card text-card-foreground shadow-sm">
    <div class="relative aspect-[4/3] overflow-hidden">
        <img src="<?php echo htmlspecialchars($property['images'][0]); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        <div class="absolute top-4 left-4">
            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 bg-black/50 text-white">
                <?php echo htmlspecialchars($property['type']); ?>
            </span>
        </div>
        <button class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/80 hover:bg-white inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 <?php echo $isFavorite ? 'fill-red-500 text-red-500' : 'text-gray-600'; ?>">
                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
            </svg>
        </button>
    </div>
    
    <div class="p-4">
        <div class="flex items-start justify-between mb-2">
            <a href="/property.php?id=<?php echo htmlspecialchars($property['id']); ?>">
                <h3 class="font-semibold text-lg hover:text-primary transition-colors line-clamp-1">
                    <?php echo htmlspecialchars($property['title']); ?>
                </h3>
            </a>
            <div class="text-right">
                <p class="text-xl font-bold text-primary">$<?php echo htmlspecialchars($property['price']); ?></p>
                <p class="text-sm text-muted-foreground">/month</p>
            </div>
        </div>
        
        <div class="flex items-center text-sm text-muted-foreground mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <span><?php echo htmlspecialchars($property['city']); ?>, <?php echo htmlspecialchars($property['state']); ?></span>
        </div>
        
        <div class="flex items-center justify-between text-sm text-muted-foreground">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><path d="M2 21v-2a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4v2"></path><path d="M16 8a4 4 0 1 0-8 0"></path></svg>
                    <span><?php echo htmlspecialchars($property['bedrooms']); ?></span>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-2.12 0L2 6"></path><path d="m2 18 2.5 2.5a1.5 1.5 0 0 0 2.12 0L9 18"></path><path d="M15 6l2.5-2.5a1.5 1.5 0 0 1 2.12 0L22 6"></path><path d="m22 18-2.5-2.5a1.5 1.5 0 0 0-2.12 0L15 18"></path><path d="M2 12h20"></path><path d="M6.5 3.5C9 6 9 18 6.5 20.5"></path><path d="M17.5 3.5C15 6 15 18 17.5 20.5"></path></svg>
                    <span><?php echo htmlspecialchars($property['bathrooms']); ?></span>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><rect width="18" height="18" x="3" y="3" rx="2"></rect></svg>
                    <span><?php echo htmlspecialchars($property['area']); ?> sq ft</span>
                </div>
            </div>
            <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-xs">
                <?php echo htmlspecialchars($property['furnishing']); ?>
            </span>
        </div>
    </div>
</div>
