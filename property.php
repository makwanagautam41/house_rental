<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details - HomeHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="src/index.css">
</head>
<body class="bg-background text-foreground">
    <?php include 'components/header.php'; ?>

    <div class="min-h-screen bg-background">
        <div class="container mx-auto px-4 py-8">
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
                echo '<div class="container mx-auto px-4 py-8">Property not found</div>';
            } else {
                $amenityIcons = [
                    'WiFi' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M5 12.55a8 8 0 0 1 14.08 0"></path><path d="M1.42 9a16 16 0 0 1 21.16 0"></path><path d="M8.53 16.11a4 4 0 0 1 6.95 0"></path><line x1="12" x2="12.01" y1="20" y2="20"></line></svg>',
                    'Parking' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1 .4-1 1v12c0 .6.4 1 1 1h3v-2z"></path><circle cx="7" cy="17" r="2"></circle><path d="M9 17h6"></path><circle cx="17" cy="17" r="2"></circle></svg>',
                    'Pool' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M2.24 13.8a2.5 2.5 0 0 0 3.52 0l7.5-7.5a2.5 2.5 0 0 1 3.52 0l3.52 3.52a2.5 2.5 0 0 1 0 3.52l-7.5 7.5a2.5 2.5 0 0 1-3.52 0l-3.52-3.52a2.5 2.5 0 0 0 0-3.52Z"></path><path d="m6.35 11.65 5.15-5.15"></path><path d="m12.5 17.8-5.15-5.15"></path></svg>',
                    'Gym' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m6.5 6.5 11 11"></path><path d="m21 21-1-1"></path><path d="m3 3 1 1"></path><path d="m18 22 4-4"></path><path d="m6 8-4 4"></path><path d="m2 6 4 4"></path><path d="m22 18-4-4"></path><path d="m16 6 4-4"></path></svg>',
                    'AC' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M12 3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2s2-.9 2-2V5c0-1.1-.9-2-2-2z"></path><path d="M19 3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2s2-.9 2-2V5c0-1.1-.9-2-2-2z"></path><path d="M5 3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2s2-.9 2-2V5c0-1.1-.9-2-2-2z"></path></svg>',
                ];
            ?>
            <!-- Image Gallery -->
            <div class="relative mb-8">
                <div class="relative aspect-[16/9] md:aspect-[21/9] rounded-lg overflow-hidden">
                    <img src="<?php echo htmlspecialchars($property['images'][0]); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Property Header -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 mb-2">
                                <?php echo htmlspecialchars($property['type']); ?>
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($property['title']); ?></h1>
                        <div class="flex items-center text-muted-foreground mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span><?php echo htmlspecialchars($property['address']); ?>, <?php echo htmlspecialchars($property['city']); ?>, <?php echo htmlspecialchars($property['state']); ?></span>
                        </div>
                        <div class="flex items-center space-x-6 text-sm text-muted-foreground">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><path d="M2 21v-2a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4v2"></path><path d="M16 8a4 4 0 1 0-8 0"></path></svg>
                                <span><?php echo htmlspecialchars($property['bedrooms']); ?> Bedrooms</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-2.12 0L2 6"></path><path d="m2 18 2.5 2.5a1.5 1.5 0 0 0 2.12 0L9 18"></path><path d="M15 6l2.5-2.5a1.5 1.5 0 0 1 2.12 0L22 6"></path><path d="m22 18-2.5-2.5a1.5 1.5 0 0 0-2.12 0L15 18"></path><path d="M2 12h20"></path><path d="M6.5 3.5C9 6 9 18 6.5 20.5"></path><path d="M17.5 3.5C15 6 15 18 17.5 20.5"></path></svg>
                                <span><?php echo htmlspecialchars($property['bathrooms']); ?> Bathrooms</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><rect width="18" height="18" x="3" y="3" rx="2"></rect></svg>
                                <span><?php echo htmlspecialchars($property['area']); ?> sq ft</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="text-2xl font-semibold leading-none tracking-tight">About this property</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <p class="text-muted-foreground"><?php echo htmlspecialchars($property['description']); ?></p>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="text-2xl font-semibold leading-none tracking-tight">Amenities</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php foreach ($property['amenities'] as $amenity): ?>
                                    <div class="flex items-center space-x-2">
                                        <?php echo $amenityIcons[$amenity] ?? '<div class="h-4 w-4"></div>'; ?>
                                        <span class="text-sm"><?php echo htmlspecialchars($amenity); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Price & Contact -->
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <div class="text-3xl font-bold text-primary mb-1">
                                    $<?php echo htmlspecialchars($property['price']); ?>
                                </div>
                                <div class="text-sm text-muted-foreground">per month</div>
                                <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 mt-2">
                                    <?php echo htmlspecialchars($property['furnishing']); ?>
                                </span>
                            </div>
                            
                            <form class="space-y-4">
                                <div>
                                    <label for="visit-date" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Preferred Visit Date</label>
                                    <input type="date" id="visit-date" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                </div>
                                
                                <div>
                                    <label for="message" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Message (optional)</label>
                                    <textarea id="message" placeholder="Any specific requirements or questions..." class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                                </div>
                                
                                <button type="submit" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                                    Schedule Visit
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Owner Info -->
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="text-2xl font-semibold leading-none tracking-tight">Property Owner</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="flex items-center space-x-3 mb-4">
                                <img src="<?php echo htmlspecialchars($property['owner']['avatar']); ?>" alt="<?php echo htmlspecialchars($property['owner']['name']); ?>" class="w-12 h-12 rounded-full">
                                <div>
                                    <h4 class="font-semibold"><?php echo htmlspecialchars($property['owner']['name']); ?></h4>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <button class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    <?php echo htmlspecialchars($property['owner']['phone']); ?>
                                </button>
                                <button class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>
