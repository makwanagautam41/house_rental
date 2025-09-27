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
            include 'config.php';

            // Get all available property types from database
            $propertyTypesQuery = "SELECT DISTINCT type FROM properties WHERE type IS NOT NULL AND type != ''";
            $propertyTypesResult = $conn->query($propertyTypesQuery);
            $propertyTypes = [];

            if ($propertyTypesResult && $propertyTypesResult->num_rows > 0) {
                while ($row = $propertyTypesResult->fetch_assoc()) {
                    $propertyTypes[] = $row['type'];
                }
            } else {
                // Fallback if no types in database
                $propertyTypes = ['Apartment', 'House', 'Villa', 'Studio'];
            }

            // Get all available amenities for filtering
            $amenitiesQuery = "SELECT id, name FROM amenities ORDER BY name";
            $amenitiesResult = $conn->query($amenitiesQuery);
            $amenities = [];

            if ($amenitiesResult && $amenitiesResult->num_rows > 0) {
                while ($row = $amenitiesResult->fetch_assoc()) {
                    $amenities[$row['id']] = $row['name'];
                }
            }

            // Build the query with filters
            $query = "SELECT p.*, 
                      (SELECT COUNT(*) FROM rental_agreements ra WHERE ra.property_id = p.id AND ra.status = 'active') as is_rented,
                      (SELECT COUNT(*) FROM rental_applications ra WHERE ra.property_id = p.id AND ra.status = 'pending') as has_pending_applications,
                      (SELECT image_url FROM property_images WHERE property_id = p.id LIMIT 1) as main_image
                      FROM properties p
                      WHERE 1=1";
            $params = [];
            $types = "";

            // Location filter
            if (!empty($_GET['location'])) {
                $location = "%" . $_GET['location'] . "%";
                $query .= " AND (p.city LIKE ? OR p.state LIKE ? OR p.address LIKE ?)";
                $params[] = $location;
                $params[] = $location;
                $params[] = $location;
                $types .= "sss";
            }

            // Price range filter
            if (!empty($_GET['price_range'])) {
                $priceRange = explode('-', $_GET['price_range']);
                $minPrice = (int)$priceRange[0];
                $maxPrice = isset($priceRange[1]) ? (int)$priceRange[1] : PHP_INT_MAX;

                $query .= " AND p.price >= ? AND p.price <= ?";
                $params[] = $minPrice;
                $params[] = $maxPrice;
                $types .= "dd";
            }

            // Property type filter
            if (!empty($_GET['property_type']) && is_array($_GET['property_type'])) {
                $typeParams = [];
                $query .= " AND (";

                foreach ($_GET['property_type'] as $index => $type) {
                    if ($index > 0) {
                        $query .= " OR ";
                    }
                    $query .= "p.type = ?";
                    $params[] = $type;
                    $types .= "s";
                }

                $query .= ")";
            }

            // Amenities filter
            if (!empty($_GET['amenities']) && is_array($_GET['amenities'])) {
                $amenityCount = count($_GET['amenities']);
                $query .= " AND p.id IN (
                            SELECT property_id 
                            FROM property_amenities 
                            WHERE amenity_id IN (" . implode(',', array_fill(0, $amenityCount, '?')) . ")
                            GROUP BY property_id
                            HAVING COUNT(DISTINCT amenity_id) = ?)";

                foreach ($_GET['amenities'] as $amenityId) {
                    $params[] = $amenityId;
                    $types .= "i";
                }

                $params[] = $amenityCount;
                $types .= "i";
            }

            // Add sorting
            $query .= " ORDER BY p.featured DESC, p.created_at DESC";

            // Prepare and execute the query
            $stmt = $conn->prepare($query);

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $properties = [];

            // Fetch all properties
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Set default image if none exists
                    if (empty($row['main_image'])) {
                        $row['main_image'] = 'https://via.placeholder.com/800x600?text=No+Image+Available';
                    }

                    // Set rental status
                    $rental_status = 'available';
                    if ($row['is_rented'] > 0) {
                        $rental_status = 'rented';
                    } elseif ($row['has_pending_applications'] > 0) {
                        $rental_status = 'pending';
                    }

                    // Format for property card component
                    $properties[] = [
                        'id' => $row['id'],
                        'title' => $row['title'],
                        'address' => $row['address'],
                        'city' => $row['city'],
                        'state' => $row['state'],
                        'price' => $row['price'],
                        'bedrooms' => $row['bedrooms'],
                        'bathrooms' => $row['bathrooms'],
                        'area' => $row['area'],
                        'type' => $row['type'],
                        'furnishing' => $row['furnishing'],
                         'rental_status' => $rental_status,
                         'images' => [$row['main_image']]
                     ];
                 }
             }
            ?>

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Properties</h1>
                    <p class="text-gray-600"><?php echo count($properties); ?> properties found</p>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Desktop Filters -->
                <aside class="hidden lg:block w-full lg:w-80">
                    <div class="sticky top-24 rounded-lg border border-gray-200 bg-white p-6">
                        <div class="flex items-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 mr-2 text-gray-700">
                                <polygon points="22 3 2 3 10 12.46V19l4 2v-8.54L22 3z"></polygon>
                            </svg>
                            <h3 class="font-semibold text-lg text-gray-900">Filters</h3>
                        </div>
                        <form class="space-y-6" method="GET">
                            <!-- Location input (hidden, handled by search bar) -->
                            <?php if (!empty($_GET['location'])): ?>
                                <input type="hidden" name="location" value="<?php echo htmlspecialchars($_GET['location']); ?>">
                            <?php endif; ?>

                            <!-- Property Type -->
                            <div>
                                <label class="text-sm font-medium text-gray-900 mb-3 block">Property Type</label>
                                <div class="space-y-2">
                                    <?php foreach ($propertyTypes as $type): ?>
                                        <?php
                                        $checked = !empty($_GET['property_type']) &&
                                            is_array($_GET['property_type']) &&
                                            in_array($type, $_GET['property_type']) ? 'checked' : '';
                                        ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="property_type[]" value="<?php echo htmlspecialchars($type); ?>"
                                                id="<?php echo htmlspecialchars(strtolower($type)); ?>"
                                                class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                                <?php echo $checked; ?>>
                                            <label for="<?php echo htmlspecialchars(strtolower($type)); ?>" class="ml-2 text-sm text-gray-600">
                                                <?php echo htmlspecialchars($type); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <label class="text-sm font-medium text-gray-900 mb-3 block">Price Range</label>
                                <select name="price_range" class="w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm focus:border-gray-900 focus:outline-none focus:ring-gray-900">
                                    <option value="">Any Price</option>
                                    <option value="0-1000" <?php echo (!empty($_GET['price_range']) && $_GET['price_range'] == '0-1000') ? 'selected' : ''; ?>>$0 - $1,000</option>
                                    <option value="1000-2000" <?php echo (!empty($_GET['price_range']) && $_GET['price_range'] == '1000-2000') ? 'selected' : ''; ?>>$1,000 - $2,000</option>
                                    <option value="2000-3000" <?php echo (!empty($_GET['price_range']) && $_GET['price_range'] == '2000-3000') ? 'selected' : ''; ?>>$2,000 - $3,000</option>
                                    <option value="3000-5000" <?php echo (!empty($_GET['price_range']) && $_GET['price_range'] == '3000-5000') ? 'selected' : ''; ?>>$3,000 - $5,000</option>
                                    <option value="5000-10000" <?php echo (!empty($_GET['price_range']) && $_GET['price_range'] == '5000-10000') ? 'selected' : ''; ?>>$5,000 - $10,000</option>
                                    <option value="10000-999999" <?php echo (!empty($_GET['price_range']) && $_GET['price_range'] == '10000-999999') ? 'selected' : ''; ?>>$10,000+</option>
                                </select>
                            </div>

                            <!-- Amenities -->
                            <?php if (!empty($amenities)): ?>
                                <div>
                                    <label class="text-sm font-medium text-gray-900 mb-3 block">Amenities</label>
                                    <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                                        <?php foreach ($amenities as $id => $name): ?>
                                            <?php
                                            $checked = !empty($_GET['amenities']) &&
                                                is_array($_GET['amenities']) &&
                                                in_array($id, $_GET['amenities']) ? 'checked' : '';
                                            ?>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="amenities[]" value="<?php echo $id; ?>"
                                                    id="amenity-<?php echo $id; ?>"
                                                    class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                                    <?php echo $checked; ?>>
                                                <label for="amenity-<?php echo $id; ?>" class="ml-2 text-sm text-gray-600">
                                                    <?php echo htmlspecialchars($name); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <button type="submit" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-10 px-4 transition-colors">
                                Apply Filters
                            </button>

                            <?php if (!empty($_GET)): ?>
                                <a href="properties.php" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 h-10 px-4 transition-colors">
                                    Clear Filters
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </aside>

                <!-- Properties Grid -->
                <div class="flex-1">
                    <?php if (count($properties) > 0): ?>
                        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
                            <?php
                            foreach ($properties as $property) {
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
</div>
</div>
</div>
</main>

<?php include 'components/footer.php'; ?>
</body>

</html>