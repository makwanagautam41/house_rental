<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
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
                    require 'data/mockData.php';
                    $featuredProperties = array_filter($mockProperties, function ($p) {
                        return $p['featured'];
                    });
                    foreach ($featuredProperties as $property) {
                        $isFavorite = false; // Example value
                        include 'components/property_card.php';
                    }
                    ?>
                </div>

                <div class="text-center">
                    <a href="properties.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-gray-300 bg-white hover:bg-gray-100 h-11 px-8">
                        View All Properties
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                    </a>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-gray-700"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Prime Locations</h3>
                        <p class="text-gray-600">
                            Properties in the most desirable neighborhoods with great amenities.
                        </p>
                    </div>

                    <div class="p-6 text-center bg-white rounded-lg border border-gray-200 hover:shadow-lg transition-shadow">
                        <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-gray-700"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Trusted Community</h3>
                        <p class="text-gray-600">
                            Join thousands of satisfied renters and property owners in our platform.
                        </p>
                    </div>

                    <div class="p-6 text-center bg-white rounded-lg border border-gray-200 hover:shadow-lg transition-shadow">
                        <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-gray-700"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path></svg>
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-yellow-400"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>
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
