<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_active($path)
{
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === $path;
}

$nav_items = [
    ['label' => 'Home', 'path' => 'index.php'],
    ['label' => 'Properties', 'path' => 'properties.php'],
];
?>

<header class="sticky top-0 z-50 w-full border-b border-gray-200 bg-white shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo -->
            <a href="index.php" class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-gray-900 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900">HomeHaven</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-6">
                <?php foreach ($nav_items as $item) : ?>
                    <a href="<?php echo $item['path']; ?>" class="text-sm font-medium transition-colors hover:text-gray-900 <?php echo is_active($item['path']) ? 'text-gray-900' : 'text-gray-500'; ?>">
                        <?php echo $item['label']; ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Desktop Actions -->
            <div class="hidden md:flex items-center gap-4">
                <button class="h-10 w-10 rounded-md hover:bg-gray-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-gray-500">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </button>
                <button class="h-10 w-10 rounded-md hover:bg-gray-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-gray-500">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                    </svg>
                </button>
                <?php include 'theme_toggle.php'; ?>
                <?php


                if (isset($_SESSION['user_id'])) {
                    echo '
                    <a href="profile.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-10 px-4">
                        Profile
                    </a>
                    ';
                } else {
                    echo '
                    <a href="login.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-10 px-4">
                        Sign In
                    </a>
                    ';
                }
                ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="h-10 w-10 rounded-md hover:bg-gray-100 flex items-center justify-center">
                    <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                        <line x1="4" x2="20" y1="12" y2="12"></line>
                        <line x1="4" x2="20" y1="6" y2="6"></line>
                        <line x1="4" x2="20" y1="18" y2="18"></line>
                    </svg>
                    <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 hidden">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Slide-in Menu -->
    <div id="mobile-menu" class="fixed inset-y-0 left-0 z-40 w-80 transform -translate-x-full transition-transform duration-300 bg-white border-r border-gray-200 md:hidden shadow-lg">
        <nav class="flex flex-col gap-4 p-4">
            <?php foreach ($nav_items as $item) : ?>
                <a href="<?php echo $item['path']; ?>" class="text-base font-medium <?php echo is_active($item['path']) ? 'text-gray-900' : 'text-gray-500'; ?> hover:text-gray-900">
                    <?php echo $item['label']; ?>
                </a>
            <?php endforeach; ?>
            <div class="border-t border-gray-200 pt-4">
                <a href="login.php" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-10 px-4">
                    Sign In
                </a>
            </div>
        </nav>
    </div>
</header>

<script>
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');

    let menuOpen = false;

    mobileMenuButton.addEventListener('click', () => {
        menuOpen = !menuOpen;
        if (menuOpen) {
            mobileMenu.classList.remove('-translate-x-full');
            mobileMenu.classList.add('translate-x-0');
            menuIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
        } else {
            mobileMenu.classList.add('-translate-x-full');
            mobileMenu.classList.remove('translate-x-0');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });
</script>