CREATE DATABASE house_rental;

USE house_rental;

CREATE TABLE users(
	userId INT,
    name VARCHAR(50),
    email VARCHAR(50),
    password VARCHAR(50)
);

SELECT * FROM users;
SELECT * FROM properties;
SELECT * FROM property_amenities;
SELECT * FROM amenities;
SELECT * FROM property_images;
SELECT * FROM testimonials;

ALTER TABLE users
MODIFY COLUMN userId INT AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE users ADD phone VARCHAR(10);
ALTER TABLE users ADD avatar VARCHAR(256);

ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL;

CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,        -- the user who added the property
    title VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    bedrooms INT NOT NULL,
    bathrooms INT NOT NULL,
    area INT NOT NULL,
    type VARCHAR(50),
    furnishing VARCHAR(50),
    description TEXT,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(userId) ON DELETE CASCADE
);

CREATE TABLE property_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

CREATE TABLE amenities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE property_amenities (
    property_id INT NOT NULL,
    amenity_id INT NOT NULL,
    PRIMARY KEY (property_id, amenity_id),
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
);

CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,                 -- the user who wrote the testimonial
    property_id INT,             -- optional, the property it refers to
    text TEXT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(userId),
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);



