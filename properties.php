<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="src/index.css">
</head>
<body class="bg-background text-foreground">
    <?php include 'components/header.php'; ?>

    <div class="min-h-screen bg-background">
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
            
            if (!empty($_GET['property_type'])) {
                $propertyType = $_GET['property_type'];
                $filteredProperties = array_filter($filteredProperties, function ($p) use ($propertyType) {
                    return $p['type'] === $propertyType;
                });
            }

            $propertyTypes = ['apartment', 'house', 'villa', 'studio'];
            $bedroomOptions = ['1', '2', '3', '4+'];
            $amenities = ['WiFi', 'Parking', 'Pool', 'Gym', 'AC', 'Garden', 'Security', 'Laundry'];
            ?>

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Properties</h1>
                    <p class="text-muted-foreground"><?php echo count($filteredProperties); ?> properties found</p>
                </div>
            </div>

            <div class="flex gap-8">
                <!-- Desktop Filters -->
                <div class="hidden lg:block w-80">
                    <div class="sticky top-24 rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 mr-2"><polygon points="22 3 2 3 10 12.46V19l4 2v-8.54L22 3z"></polygon></svg>
                                <h3 class="font-semibold">Filters</h3>
                            </div>
                            <form class="space-y-6">
                                <div>
                                    <label class="text-sm font-medium mb-3 block">Property Type</label>
                                    <div class="space-y-2">
                                        <?php foreach ($propertyTypes as $type): ?>
                                            <div class="flex items-center space-x-2">
                                                <input type="checkbox" name="property_type[]" value="<?php echo $type; ?>" id="<?php echo $type; ?>" class="h-4 w-4 shrink-0 rounded-sm border border-primary ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                                <label for="<?php echo $type; ?>" class="text-sm capitalize"><?php echo $type; ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <button type="submit" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">Apply Filters</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Properties Grid -->
                <div class="flex-1">
                    <div class="grid gap-6 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
                        <?php
                        if (count($filteredProperties) > 0) {
                            foreach ($filteredProperties as $property) {
                                $isFavorite = false; // Example value
                                include 'components/property_card.php';
                            }
                        } else {
                            echo '<p class="text-muted-foreground col-span-full text-center">No properties found matching your criteria.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>
