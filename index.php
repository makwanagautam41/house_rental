<?php
// index.php

// Static testimonials data
$testimonials = [
    [
        'name' => 'Sarah Johnson',
        'avatar' => 'https://images.unsplash.com/photo-1494790108755-2616b612b647?w=100&h=100&fit=crop&crop=face',
        'rating' => 5,
        'text' => 'HomeHaven made finding my dream apartment so easy! The platform is user-friendly and the property owners are very responsive.'
    ],
    [
        'name' => 'Michael Chen',
        'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face',
        'rating' => 5,
        'text' => 'As a property owner, I love how simple it is to manage my listings and connect with potential tenants. Highly recommended!'
    ],
    [
        'name' => 'Emily Rodriguez',
        'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face',
        'rating' => 5,
        'text' => 'The verification process gives me confidence in the platform. I found a great place to live within just a few days!'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HomeHaven - Find Your Perfect Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800">
    <?php include 'components/header.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="relative bg-gray-50 py-20">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center mb-12">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                        Find Your Perfect
                        <span class="text-gray-700">Home</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8">
                        Discover amazing places to live, work, and invest in with our comprehensive rental platform.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="properties.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-11 px-8">
                            Browse Properties
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="dashboard.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-gray-300 bg-white hover:bg-gray-100 h-11 px-8">
                            List Your Property
                        </a>
                    </div>
                </div>

                <div class="max-w-4xl mx-auto">
                    <?php
                    $className = 'shadow-lg';
                    include 'components/search_bar.php';
                    ?>
                </div>
            </div>
        </section>

        <!-- Featured Properties -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Featured Properties</h2>
                    <p class="text-lg text-gray-600">
                        Handpicked properties for you
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <?php
                    include 'config.php';

                    // Fetch featured or latest properties from DB with rental status check
                    $query = "SELECT p.*, 
                      (SELECT image_url FROM property_images WHERE property_id = p.id LIMIT 1) as main_image,
                      CASE 
                        WHEN EXISTS (
                          SELECT 1 FROM rental_agreements ra 
                          WHERE ra.property_id = p.id 
                          AND ra.status = 'active' 
                          AND ra.start_date <= CURDATE() 
                          AND (ra.end_date IS NULL OR ra.end_date >= CURDATE())
                        ) THEN 1 
                        ELSE 0 
                      END as is_rented
                    FROM properties p
                    ORDER BY p.featured DESC, p.created_at DESC
                    LIMIT 6"; // show only a few on homepage
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $property = [
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
                                'is_rented' => $row['is_rented'],
                                'images' => [!empty($row['main_image']) ? $row['main_image'] : 'https://via.placeholder.com/800x600?text=No+Image']
                            ];
                            $isFavorite = false; // Example placeholder

                            // Display property card with rental status
                            echo '<div class="bg-white rounded-xl shadow border hover:shadow-lg transition-shadow duration-300 overflow-hidden relative">';

                            // Rental status badge
                            if ($property['is_rented']) {
                                echo '<div class="absolute top-4 left-4 z-10">
                                        <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full border border-red-200">
                                          🏠 RENTED
                                        </span>
                                      </div>';
                            } else {
                                echo '<div class="absolute top-4 left-4 z-10">
                                        <span class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full border border-green-200">
                                          ✅ AVAILABLE
                                        </span>
                                      </div>';
                            }

                            // Property image
                            echo '<div class="relative">';
                            if ($property['is_rented']) {
                                echo '<div class="absolute inset-0 bg-gray-900 bg-opacity-40 z-5"></div>';
                            }
                            echo '<img src="' . htmlspecialchars($property['images'][0]) . '" 
                                      alt="' . htmlspecialchars($property['title']) . '" 
                                      class="w-full h-64 object-cover" />';
                            echo '</div>';

                            // Property details
                            echo '<div class="p-6">';
                            echo '<div class="flex items-center justify-between mb-2">';
                            echo '<span class="inline-block bg-gray-100 text-gray-700 text-xs font-medium px-2 py-1 rounded">';
                            echo htmlspecialchars($property['type']);
                            echo '</span>';
                            if ($property['is_rented']) {
                                echo '<span class="text-red-600 font-semibold text-sm">Not Available</span>';
                            } else {
                                echo '<span class="text-green-600 font-semibold text-sm">Available</span>';
                            }
                            echo '</div>';

                            echo '<h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">';
                            echo htmlspecialchars($property['title']);
                            echo '</h3>';

                            echo '<p class="flex items-center text-gray-600 text-sm mb-3">';
                            echo '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                            echo '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>';
                            echo '<circle cx="12" cy="10" r="3"></circle>';
                            echo '</svg>';
                            echo htmlspecialchars($property['address'] . ', ' . $property['city'] . ', ' . $property['state']);
                            echo '</p>';

                            echo '<div class="flex items-center justify-between text-sm text-gray-600 mb-4">';
                            echo '<span>🛏 ' . htmlspecialchars($property['bedrooms']) . ' bed</span>';
                            echo '<span>🛁 ' . htmlspecialchars($property['bathrooms']) . ' bath</span>';
                            echo '<span>📏 ' . htmlspecialchars($property['area']) . ' sqft</span>';
                            echo '</div>';

                            echo '<div class="flex items-center justify-between">';
                            echo '<div class="text-2xl font-bold text-gray-900">';
                            echo '$' . number_format($property['price']);
                            echo '<span class="text-sm font-normal text-gray-500"> /month</span>';
                            echo '</div>';

                            if ($property['is_rented']) {
                                echo '<div class="px-4 py-2 bg-gray-100 text-gray-500 rounded-md text-sm font-medium cursor-not-allowed">';
                                echo 'Rented';
                                echo '</div>';
                            } else {
                                echo '<a href="property.php?id=' . $property['id'] . '" ';
                                echo 'class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">';
                                echo 'View Details';
                                echo '</a>';
                            }
                            echo '</div>';

                            echo '</div>'; // Close property details
                            echo '</div>'; // Close property card
                        }
                    } else {
                        echo '<p class="text-center text-gray-600 col-span-full">No featured properties available right now.</p>';
                    }
                    ?>
                </div>

                <div class="text-center">
                    <a href="properties.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-gray-300 bg-white hover:bg-gray-100 h-11 px-8">
                        View All Properties
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Property Statistics -->
        <section class="py-12 bg-gray-900 text-white">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <?php
                    // Get property statistics
                    $statsQuery = "SELECT 
                        COUNT(*) as total_properties,
                        COUNT(CASE WHEN EXISTS (
                            SELECT 1 FROM rental_agreements ra 
                            WHERE ra.property_id = p.id 
                            AND ra.status = 'active' 
                            AND ra.start_date <= CURDATE() 
                            AND (ra.end_date IS NULL OR ra.end_date >= CURDATE())
                        ) THEN 1 END) as rented_properties,
                        COUNT(CASE WHEN NOT EXISTS (
                            SELECT 1 FROM rental_agreements ra 
                            WHERE ra.property_id = p.id 
                            AND ra.status = 'active' 
                            AND ra.start_date <= CURDATE() 
                            AND (ra.end_date IS NULL OR ra.end_date >= CURDATE())
                        ) THEN 1 END) as available_properties,
                        COUNT(DISTINCT owner_id) as total_owners
                        FROM properties p";
                    $statsResult = $conn->query($statsQuery);
                    $stats = $statsResult->fetch_assoc();
                    ?>

                    <div>
                        <div class="text-3xl font-bold text-white mb-2"><?php echo number_format($stats['total_properties']); ?></div>
                        <div class="text-gray-300">Total Properties</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-green-400 mb-2"><?php echo number_format($stats['available_properties']); ?></div>
                        <div class="text-gray-300">Available</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-red-400 mb-2"><?php echo number_format($stats['rented_properties']); ?></div>
                        <div class="text-gray-300">Currently Rented</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-blue-400 mb-2"><?php echo number_format($stats['total_owners']); ?></div>
                        <div class="text-gray-300">Property Owners</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Why Choose HomeHaven?</h2>
                    <p class="text-lg text-gray-600">
                        Experience the best in rental property management
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-6 text-center bg-white rounded-lg border border-gray-200 hover:shadow-lg transition-shadow">
                        <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0116 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Prime Locations</h3>
                        <p class="text-gray-600">
                            Properties in the most desirable neighborhoods with great amenities.
                        </p>
                    </div>

                    <div class="p-6 text-center bg-white rounded-lg border border-gray-200 hover:shadow-lg transition-shadow">
                        <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 00-3-3.87" />
                                <path d="M16 3.13a4 4 0 010 7.75" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Trusted Community</h3>
                        <p class="text-gray-600">
                            Join thousands of satisfied renters and property owners in our platform.
                        </p>
                    </div>

                    <div class="p-6 text-center bg-white rounded-lg border border-gray-200 hover:shadow-lg transition-shadow">
                        <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure & Verified</h3>
                        <p class="text-gray-600">
                            All properties and users are verified for your safety and peace of mind.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">What Our Users Say</h2>
                    <p class="text-lg text-gray-600">
                        Real experiences from our community
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($testimonials as $testimonial): ?>
                        <div class="p-6 rounded-lg border border-gray-200 bg-white">
                            <div class="flex items-center mb-4">
                                <img src="<?php echo htmlspecialchars($testimonial['avatar']); ?>" alt="<?php echo htmlspecialchars($testimonial['name']); ?>" class="w-12 h-12 rounded-full mr-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                    <div class="flex items-center">
                                        <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600"><?php echo htmlspecialchars($testimonial['text']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-gray-900 text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Find Your Home?</h2>
                <p class="text-lg mb-8 opacity-90">
                    Join thousands of happy renters and start your journey today.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="properties.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-white text-gray-900 hover:bg-gray-200 h-11 px-8">
                        Start Searching
                    </a>
                    <a href="login.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-gray-300 text-white hover:bg-gray-800 h-11 px-8">
                        Sign Up Now
                    </a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>