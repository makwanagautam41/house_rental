<?php

$mockProperties = [
  [
    'id' => '1',
    'title' => 'Modern Downtown Apartment',
    'address' => '123 Main Street',
    'city' => 'San Francisco',
    'state' => 'CA',
    'price' => 2500,
    'bedrooms' => 2,
    'bathrooms' => 2,
    'area' => 1200,
    'images' => [
      'https://thumbs.dreamstime.com/b/apartment-building-modern-buildings-new-westminster-british-columbia-canada-40351928.jpg?w=768',
      'https://thumbs.dreamstime.com/b/apartment-building-modern-buildings-new-westminster-british-columbia-canada-40351928.jpg?w=768',
      'https://thumbs.dreamstime.com/b/apartment-building-modern-buildings-new-westminster-british-columbia-canada-40351928.jpg?w=768'
    ],
    'type' => 'apartment',
    'furnishing' => 'furnished',
    'amenities' => ['WiFi', 'Parking', 'Pool', 'Gym', 'AC'],
    'description' => 'Beautiful modern apartment in the heart of downtown with stunning city views.',
    'owner' => [
      'name' => 'Sarah Johnson',
      'avatar' => '/assets/placeholder.svg',
      'phone' => '+1 555-0123',
      'email' => 'sarah@email.com'
    ],
    'featured' => true,
    'createdAt' => '2024-01-15'
  ],
  [
    'id' => '2',
    'title' => 'Cozy Family House',
    'address' => '456 Oak Avenue',
    'city' => 'Los Angeles',
    'state' => 'CA',
    'price' => 3200,
    'bedrooms' => 3,
    'bathrooms' => 2,
    'area' => 1800,
    'images' => [
      'https://imagecdn.99acres.com/media1/29224/1/584481707M-1744031946117.webp',
      'https://imagecdn.99acres.com/media1/29224/1/584481707M-1744031946117.webp',
      'https://imagecdn.99acres.com/media1/29224/1/584481707M-1744031946117.webp'
    ],
    'type' => 'house',
    'furnishing' => 'semi-furnished',
    'amenities' => ['WiFi', 'Parking', 'Garden', 'AC', 'Fireplace'],
    'description' => 'Charming family house with a beautiful garden and quiet neighborhood.',
    'owner' => [
      'name' => 'Michael Chen',
      'avatar' => '/assets/placeholder.svg',
      'phone' => '+1 555-0456',
      'email' => 'michael@email.com'
    ],
    'featured' => true,
    'createdAt' => '2024-01-20'
  ],
  [
    'id' => '3',
    'title' => 'Luxury Villa with Pool',
    'address' => '789 Hillside Drive',
    'city' => 'Miami',
    'state' => 'FL',
    'price' => 4500,
    'bedrooms' => 4,
    'bathrooms' => 3,
    'area' => 2500,
    'images' => [
      'https://plus.unsplash.com/premium_photo-1661883982941-50af7720a6ff?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YmVhdXRpZnVsJTIwaG91c2V8ZW58MHx8MHx8fDA%3D',
      'https://plus.unsplash.com/premium_photo-1661883982941-50af7720a6ff?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YmVhdXRpZnVsJTIwaG91c2V8ZW58MHx8MHx8fDA%3D',
      'https://plus.unsplash.com/premium_photo-1661883982941-50af7720a6ff?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YmVhdXRpZnVsJTIwaG91c2V8ZW58MHx8MHx8fDA%3D'
    ],
    'type' => 'villa',
    'furnishing' => 'furnished',
    'amenities' => ['WiFi', 'Parking', 'Pool', 'Gym', 'AC', 'Security'],
    'description' => 'Stunning luxury villa with private pool and ocean views.',
    'owner' => [
      'name' => 'Emily Rodriguez',
      'avatar' => '/assets/placeholder.svg',
      'phone' => '+1 555-0789',
      'email' => 'emily@email.com'
    ],
    'featured' => false,
    'createdAt' => '2024-01-25'
  ],
  [
    'id' => '4',
    'title' => 'Minimalist Studio',
    'address' => '321 Design Street',
    'city' => 'New York',
    'state' => 'NY',
    'price' => 1800,
    'bedrooms' => 1,
    'bathrooms' => 1,
    'area' => 600,
    'images' => [
      'https://plus.unsplash.com/premium_photo-1661883982941-50af7720a6ff?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YmVhdXRpZnVsJTIwaG91c2V8ZW58MHx8MHx8fDA%3D',
      'https://plus.unsplash.com/premium_photo-1661883982941-50af7720a6ff?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YmVhdXRpZnVsJTIwaG91c2V8ZW58MHx8MHx8fDA%3D',
      'https://plus.unsplash.com/premium_photo-1661883982941-50af7720a6ff?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YmVhdXRpZnVsJTIwaG91c2V8ZW58MHx8MHx8fDA%3D'
    ],
    'type' => 'Luxury house',
    'furnishing' => 'furnished',
    'amenities' => ['WiFi', 'AC', 'Laundry'],
    'description' => 'Clean, minimalist studio perfect for young professionals.',
    'owner' => [
      'name' => 'David Kim',
      'avatar' => '/assets/placeholder.svg',
      'phone' => '+1 555-0321',
      'email' => 'david@email.com'
    ],
    'featured' => true,
    'createdAt' => '2024-02-01'
  ],
  [
    'id' => '5',
    'title' => 'Spacious Loft',
    'address' => '654 Industrial Blvd',
    'city' => 'Chicago',
    'state' => 'IL',
    'price' => 2200,
    'bedrooms' => 2,
    'bathrooms' => 2,
    'area' => 1400,
    'images' => [
      'https://media.gettyimages.com/id/1463785062/photo/germany-bavaria-munich-residential-garden-in-front-of-modern-apartment-building-at-sunset.jpg?s=612x612&w=gi&k=20&c=8kFYzJGdVPkRY6gPiGCjvgUYGlQVrKLWGT9-RKN8eNQ=',
      'https://media.gettyimages.com/id/1463785062/photo/germany-bavaria-munich-residential-garden-in-front-of-modern-apartment-building-at-sunset.jpg?s=612x612&w=gi&k=20&c=8kFYzJGdVPkRY6gPiGCjvgUYGlQVrKLWGT9-RKN8eNQ=',
      'https://media.gettyimages.com/id/1463785062/photo/germany-bavaria-munich-residential-garden-in-front-of-modern-apartment-building-at-sunset.jpg?s=612x612&w=gi&k=20&c=8kFYzJGdVPkRY6gPiGCjvgUYGlQVrKLWGT9-RKN8eNQ='
    ],
    'type' => 'apartment',
    'furnishing' => 'unfurnished',
    'amenities' => ['WiFi', 'Parking', 'High Ceilings', 'Exposed Brick'],
    'description' => 'Unique loft space with industrial charm and modern amenities.',
    'owner' => [
      'name' => 'Lisa Wang',
      'avatar' => '/assets/placeholder.svg',
      'phone' => '+1 555-0654',
      'email' => 'lisa@email.com'
    ],
    'featured' => false,
    'createdAt' => '2024-02-05'
  ],
  [
    'id' => '6',
    'title' => 'Beachfront Condo',
    'address' => '987 Ocean Drive',
    'city' => 'San Diego',
    'state' => 'CA',
    'price' => 3800,
    'bedrooms' => 3,
    'bathrooms' => 2,
    'area' => 1600,
    'images' => [
      'https://media.gettyimages.com/id/1463785062/photo/germany-bavaria-munich-residential-garden-in-front-of-modern-apartment-building-at-sunset.jpg?s=612x612&w=gi&k=20&c=8kFYzJGdVPkRY6gPiGCjvgUYGlQVrKLWGT9-RKN8eNQ=',
      'https://media.gettyimages.com/id/1463785062/photo/germany-bavaria-munich-residential-garden-in-front-of-modern-apartment-building-at-sunset.jpg?s=612x612&w=gi&k=20&c=8kFYzJGdVPkRY6gPiGCjvgUYGlQVrKLWGT9-RKN8eNQ=',
      'https://media.gettyimages.com/id/1463785062/photo/germany-bavaria-munich-residential-garden-in-front-of-modern-apartment-building-at-sunset.jpg?s=612x612&w=gi&k=20&c=8kFYzJGdVPkRY6gPiGCjvgUYGlQVrKLWGT9-RKN8eNQ='
    ],
    'type' => 'apartment',
    'furnishing' => 'furnished',
    'amenities' => ['WiFi', 'Parking', 'Pool', 'Beach Access', 'AC'],
    'description' => 'Beautiful beachfront condo with panoramic ocean views.',
    'owner' => [
      'name' => 'James Martinez',
      'avatar' => '/assets/placeholder.svg',
      'phone' => '+1 555-0987',
      'email' => 'james@email.com'
    ],
    'featured' => true,
    'createdAt' => '2024-02-10'
  ]
];

$testimonials = [
  [
    'id' => '1',
    'name' => 'Yuvraj',
    'avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQBNPTY7m-4nOzKjX6eY-soNqNuIh29VP9Dkg&s',
    'text' => 'Found my dream apartment in just 2 days! The process was seamless and the support team was incredibly helpful.',
    'rating' => 4
  ],
  [
    'id' => '2',
    'name' => 'Dhruvraj Zala',
    'avatar' => 'https://avatars.githubusercontent.com/u/177622474?v=4',
    'text' => 'HomeHaven made house hunting so much easier. The search filters are perfect and I love the detailed property information.',
    'rating' => 5
  ],
  [
    'id' => '3',
    'name' => 'Gautam Makwana',
    'avatar' => 'https://gautammakwana.vercel.app/assets/profile-CVZS-JQz.jpg',
    'text' => 'As a property owner, I appreciate how easy it is to manage my listings. Great platform for both renters and owners.',
    'rating' => 5
  ]
];
