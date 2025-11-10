-- Database schema for All In Packaging Solution (AIPS)
CREATE DATABASE IF NOT EXISTS aips_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aips_db;

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    category VARCHAR(60) NOT NULL DEFAULT 'General',
    image_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO products (name, description, price, category, image_path) VALUES
('EcoShield Paper Cup', 'Biodegradable double-walled cup ideal for hot beverages and branded drinks.', 18.50, 'Cups', 'https://images.unsplash.com/photo-1518131678677-a526eee274d7?auto=format&fit=crop&w=600&q=80'),
('FreshPack Lunch Box', 'Compostable kraft lunch box with secure locking system for takeaway meals.', 24.00, 'Boxes', 'https://images.unsplash.com/photo-1585238342025-78d387f4a707?auto=format&fit=crop&w=600&q=80'),
('CrystalSeal Container', 'Reusable, BPA-free container with airtight lid perfect for cold storage.', 32.75, 'Containers', 'https://images.unsplash.com/photo-1523475472560-d2df97ec485c?auto=format&fit=crop&w=600&q=80');
