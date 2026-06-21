/* ================================
   DATABASE : kokanmeva
   PURPOSE  : Konkan Meva Online Store
   ================================ */

-- Database create (जर आधी नसेल तर)
CREATE DATABASE IF NOT EXISTS kokanmeva;
USE kokanmeva;

-- =================================
-- PRODUCTS TABLE
-- Main products (उदा. आंबा, काजू)
-- =================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Product unique ID
    product_name VARCHAR(255) NOT NULL,     -- Product नाव
    product_image VARCHAR(255),              -- Product image path
    description TEXT,                        -- Product description
    unit_type VARCHAR(50),                   -- Unit (kg, dozen, packet)
    stock INT DEFAULT 0,                     -- Available stock
    price DECIMAL(10,2) DEFAULT 0,            -- Base price
    availability ENUM('Available','Not Available') 
        DEFAULT 'Available',                 -- Product availability
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =================================
-- SUB PRODUCTS TABLE
-- Variants (उदा. 500gm, 1kg)
-- =================================
CREATE TABLE IF NOT EXISTS sub_products (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Sub-product ID
    product_id INT NOT NULL,                 -- Main product reference
    sub_name VARCHAR(255) NOT NULL,           -- Variant name
    sub_image VARCHAR(255),                  -- Variant image
    price DECIMAL(10,2) DEFAULT 0,             -- Variant price
    stock INT DEFAULT 0,                     -- Variant stock
    availability ENUM('Available','Not Available') 
        DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Foreign key relation
    FOREIGN KEY (product_id) 
        REFERENCES products(id) 
        ON DELETE CASCADE
);

-- =================================
-- ORDERS TABLE
-- Customer order details
-- =================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Order ID
    name VARCHAR(255) NOT NULL,               -- Customer name
    email VARCHAR(255) NOT NULL,              -- Customer email
    address TEXT NOT NULL,                    -- Delivery address
    district VARCHAR(100),                    -- District
    taluka VARCHAR(100),                      -- Taluka
    delivery_charges DECIMAL(10,2) DEFAULT 0, -- Delivery charges
    final_total DECIMAL(10,2) DEFAULT 0,      -- Final bill amount
    payment_method ENUM('COD','UPI','Debit Card','Credit Card') 
        DEFAULT 'COD',                        -- Payment method
    order_status ENUM('Pending','Confirmed','Delivered','Cancelled') 
        DEFAULT 'Pending',                    -- Order status
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =================================
-- ORDER ITEMS TABLE
-- Products inside an order
-- =================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,                   -- Order reference
    product_id INT NOT NULL,                 -- Product reference
    sub_product_id INT DEFAULT NULL,         -- Variant reference
    quantity INT DEFAULT 1,                  -- Quantity ordered
    price DECIMAL(10,2) NOT NULL,             -- Price per item
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id) 
        REFERENCES orders(id) 
        ON DELETE CASCADE,

    FOREIGN KEY (product_id) 
        REFERENCES products(id),

    FOREIGN KEY (sub_product_id) 
        REFERENCES sub_products(id)
);

-- =================================
-- CONTACT MESSAGES TABLE
-- Customer inquiries / messages
-- =================================
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Message ID
    name VARCHAR(255),                        -- Sender name
    email VARCHAR(255),                       -- Sender email
    message TEXT,                             -- Message content
    reply TEXT,                               -- Admin reply
    status ENUM('New','Replied') DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =================================
-- ADMINS TABLE
-- Admin login users
-- =================================
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Admin ID
    username VARCHAR(50) UNIQUE NOT NULL,     -- Admin username
    password VARCHAR(255) NOT NULL,           -- Hashed password
    role ENUM('Super Admin','Admin') 
        DEFAULT 'Admin',                      -- Admin role
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =================================
-- DEFAULT ADMIN (username: admin | password: admin123)
-- Password hash generated using PHP password_hash()
-- =================================
INSERT INTO admins (username, password, role)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Super Admin'
);