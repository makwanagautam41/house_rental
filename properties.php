<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php include 'components/header.php'; ?>

    <main class="min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Search Bar -->
            <div class="mb-8">
                <?php include 'components/search_bar.php'; ?>
            </div>

            <?php
            require 'data/mockData.php';

            // Filtering logic
            $filteredProperties = $mockProperties;

            if (!empty($_GET['location'])) {
                $location = strtolower($_GET['location']);
                $filteredProperties = array_filter($filteredProperties, function ($p) use ($location) {
                    return strpos(strtolower($p['city']), $location) !== false || strpos(strtolower($p['state']), $location) !== false || strpos(strtolower($p['address']), $location) !== false;
                });
            }

            if (!empty($_GET['price_range'])) {
                $priceRange = explode('-', $_GET['price_range']);
                $minPrice = (int)$priceRange[0];
                $maxPrice = isset($priceRange[1]) ? (int)$priceRange[1] : PHP_INT_MAX;
                $filteredProperties = array_filter($filteredProperties, function ($p) use ($minPrice, $maxPrice) {
                    return $p['price'] >= $minPrice && $p['price'] <= $maxPrice;
                });
            }
            
            if (!empty($_GET['property_type']) && is_array($_GET['property_type'])) {
                $propertyTypes = array_map('strtolower', $_GET['property_type']);
                $filteredProperties = array_filter($filteredProperties, function ($p) use ($propertyTypes) {
                    return in_array(strtolower($p['type']), $propertyTypes);
                });
            }

            $propertyTypes = ['Apartment', 'House', 'Villa', 'Studio'];
            ?>

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Properties</h1>
                    <p class="text-gray-600"><?php echo count($filteredProperties); ?> properties found</p>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Desktop Filters -->
                <aside class="hidden lg:block w-full lg:w-80">
                    <div class="sticky top-24 rounded-lg border border-gray-200 bg-white p-6">
                        <div class="flex items-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 mr-2 text-gray-700"><polygon points="22 3 2 3 10 12.46V19l4 2v-8.54L22 3z"></polygon></svg>
                            <h3 class="font-semibold text-lg text-gray-900">Filters</h3>
                        </div>
                        <form class="space-y-6">
                            <div>
                                <label class="text-sm font-medium text-gray-900 mb-3 block">Property Type</label>
                                <div class="space-y-2">
                                    <?php foreach ($propertyTypes as $type): ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="property_type[]" value="<?php echo strtolower($type); ?>" id="<?php echo strtolower($type); ?>" class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                            <label for="<?php echo strtolower($type); ?>" class="ml-2 text-sm text-gray-600"><?php echo $type; ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-10 px-4 transition-colors">Apply Filters</button>
                        </form>
                    </div>
                </aside>

                <!-- Properties Grid -->
                <div class="flex-1">
                    <?php if (count($filteredProperties) > 0): ?>
                        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
                            <?php
                            foreach ($filteredProperties as $property) {
                                $isFavorite = false; // Example value
                                include 'components/property_card.php';
                            }
                            ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-16">
                            <p class="text-gray-600">No properties found matching your criteria.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>
</html>
