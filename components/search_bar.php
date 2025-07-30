<?php
// Expects an optional $className variable to be defined before including this file.
$className = isset($className) ? $className : '';
?>
<div class="p-4 rounded-lg border border-gray-200 bg-white <?php echo htmlspecialchars($className); ?>">
    <form action="properties.php" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-center">
        <div class="relative">
            <label for="location" class="sr-only">Location</label>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <input type="text" id="location" name="location" placeholder="Enter location" class="w-full pl-10 pr-4 py-2 h-11 rounded-md border border-gray-300 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors">
        </div>
        
        <div class="relative">
            <label for="price_range" class="sr-only">Price Range</label>
            <select id="price_range" name="price_range" class="w-full appearance-none bg-transparent pl-4 pr-8 py-2 h-11 rounded-md border border-gray-300 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors">
                <option value="" disabled selected>Price Range</option>
                <option value="0-1000">$0 - $1,000</option>
                <option value="1000-2000">$1,000 - $2,000</option>
                <option value="2000-3000">$2,000 - $3,000</option>
                <option value="3000-5000">$3,000 - $5,000</option>
                <option value="5000+">$5,000+</option>
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
        
        <div class="relative">
            <label for="property_type" class="sr-only">Property Type</label>
            <select id="property_type" name="property_type" class="w-full appearance-none bg-transparent pl-4 pr-8 py-2 h-11 rounded-md border border-gray-300 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition-colors">
                <option value="" disabled selected>Property Type</option>
                <option value="apartment">Apartment</option>
                <option value="house">House</option>
                <option value="villa">Villa</option>
                <option value="studio">Studio</option>
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
        
        <button type="submit" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-11 px-4 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 mr-2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            Search
        </button>
    </form>
</div>
