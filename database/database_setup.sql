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
select * from rental_payments;
select * from rental_agreements;
select * from rental_applications;

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

-- Table for rental applications
CREATE TABLE rental_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    user_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    monthly_budget DECIMAL(10,2) NOT NULL,
    occupants INT NOT NULL,
    message TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(userId) ON DELETE CASCADE
);

-- Table for rental agreements
CREATE TABLE rental_agreements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    property_id INT NOT NULL,
    tenant_id INT NOT NULL,
    owner_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    monthly_rent DECIMAL(10,2) NOT NULL,
    security_deposit DECIMAL(10,2) NOT NULL,
    terms TEXT NOT NULL,
    status ENUM('active', 'expired', 'terminated') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES rental_applications(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (owner_id) REFERENCES users(userId) ON DELETE CASCADE
);

-- Table for rental payments
CREATE TABLE rental_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agreement_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (agreement_id) REFERENCES rental_agreements(id) ON DELETE CASCADE
);