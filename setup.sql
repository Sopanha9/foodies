-- setup.sql
-- Run this file in your MySQL database (e.g., via phpMyAdmin, Sequel Ace, or command line)
-- It will create the database and tables, and insert initial data.


-- 1. Admins Table
CREATE TABLE  admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Categories Table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Menu Items Table
CREATE TABLE  menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- 4. Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100),
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Cash on Delivery',
    transaction_reference VARCHAR(100) NULL,
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    order_status ENUM('pending', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Existing database migration (run once if orders table already exists):
-- ALTER TABLE orders ADD COLUMN transaction_reference VARCHAR(100) NULL AFTER payment_method;

-- 5. Order Items Table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_time DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

-- ==========================================
-- DUMPING INITIAL DATA
-- ==========================================

-- Default admin: admin / admin123
INSERT IGNORE INTO admins (username, password_hash) VALUES
('admin', '$2y$12$afG.LzbREP0cw.iRBfJAh.jqyQuMJqPkX1FD0GrCUcfCcsmdtM5dS');

-- Categories
INSERT IGNORE INTO categories (id, slug, name) VALUES
(1, 'breakfast', 'Breakfast'),
(2, 'main-dishes', 'Main Dishes'),
(3, 'drinks', 'Drinks'),
(4, 'desserts', 'Desserts');


-- Menu Items
INSERT IGNORE INTO menu_items (id, category_id, name, price, description, image_url) VALUES
(1, 1, 'Fried Eggs', 9.99, 'Made with eggs, lettuce, salt, oil and other ingredients.', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=500&q=80'),
(2, 2, 'Hawaiian Pizza', 15.99, 'Made with eggs, lettuce, salt, oil and other ingredients.', 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500&q=80'),
(3, 3, 'Martinez Cocktail', 7.25, 'Made with eggs, lettuce, salt, oil and other ingredients.', 'https://images.unsplash.com/photo-1551538827-9c037cb4f32a?w=500&q=80'),
(4, 4, 'Butterscotch Cake', 20.99, 'Made with eggs, lettuce, salt, oil and other ingredients.', 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=500&q=80'),
(5, 3, 'Mint Lemonade', 5.89, 'Made with eggs, lettuce, salt, oil and other ingredients.', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&q=80'),
(6, 4, 'Chocolate Icecream', 18.05, 'Made with eggs, lettuce, salt, oil and other ingredients.', 'https://images.unsplash.com/photo-1501443762994-82bd5dace89a?w=500&q=80'),
(7, 2, 'Cheese Burger', 12.55, 'Made with eggs, lettuce, salt, oil and other ingredients.', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&q=80'),
(8, 1, 'Classic Waffles', 12.99, 'Made with eggs, lettuce, salt, oil and other ingredients.', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&q=80');
