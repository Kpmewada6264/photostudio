-- PhotoStudio Pro Database Schema
-- MySQL Database

CREATE DATABASE IF NOT EXISTS photostudio_pro;
USE photostudio_pro;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services table
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Gallery table
CREATE TABLE gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    image VARCHAR(255) NOT NULL,
    category VARCHAR(50) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    message TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password, phone) VALUES 
('Admin', 'admin@photostudio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1234567890');

-- Insert sample services
INSERT INTO services (title, description, price, image) VALUES 
('Wedding Photography', 'Complete wedding photography coverage with candid shots, traditional moments, and artistic portraits. Includes pre-wedding consultation and post-production editing.', 1500.00, 'wedding.jpg'),
('Pre Wedding Shoot', 'Romantic pre-wedding photoshoot at your preferred location. Perfect for save-the-date cards and wedding invitations.', 500.00, 'prewedding.jpg'),
('Birthday Photography', 'Capture your special birthday moments with professional photography. Includes candid shots and group photos.', 300.00, 'birthday.jpg'),
('Event Photography', 'Professional event photography for corporate events, parties, and special occasions. Full coverage with high-quality images.', 400.00, 'event.jpg'),
('Product Photography', 'High-quality product photography for e-commerce and marketing. Clean, professional shots that showcase your products.', 200.00, 'product.jpg'),
('Model Portfolio Shoot', 'Professional portfolio photoshoot for models and actors. Includes multiple outfit changes and retouched images.', 600.00, 'portfolio.jpg');

-- Insert sample gallery images
INSERT INTO gallery (image, category) VALUES 
('wedding1.jpg', 'Wedding'),
('wedding2.jpg', 'Wedding'),
('prewedding1.jpg', 'PreWedding'),
('prewedding2.jpg', 'PreWedding'),
('event1.jpg', 'Events'),
('event2.jpg', 'Events'),
('portrait1.jpg', 'Portrait'),
('portrait2.jpg', 'Portrait'),
('fashion1.jpg', 'Fashion'),
('fashion2.jpg', 'Fashion');
