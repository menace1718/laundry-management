-- Complete database setup for Laundry Management System
-- Run this in phpMyAdmin after creating laundry_db database

-- Create customers table
CREATE TABLE IF NOT EXISTS customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(50) NULL,
  email VARCHAR(100) NULL,
  user_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create users table for authentication
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('staff', 'customer') NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NULL,
  phone VARCHAR(50) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_active BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  type VARCHAR(50) NOT NULL,
  weight DECIMAL(10,2) NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  status VARCHAR(30) NOT NULL DEFAULT 'received',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create notifications table
CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  to_customer_id INT NOT NULL,
  message VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_notif_customer FOREIGN KEY (to_customer_id) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create schedules table
CREATE TABLE IF NOT EXISTS schedules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  type VARCHAR(30) NOT NULL, -- pickup | delivery
  slot_time DATETIME NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'scheduled',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_sched_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create price_config table
CREATE TABLE IF NOT EXISTS price_config (
  id INT AUTO_INCREMENT PRIMARY KEY,
  per_kg DECIMAL(10,2) NOT NULL DEFAULT 50.00,
  per_shirt DECIMAL(10,2) NOT NULL DEFAULT 10.00,
  per_pants DECIMAL(10,2) NOT NULL DEFAULT 15.00,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add foreign key constraint to customers table
ALTER TABLE customers ADD CONSTRAINT fk_customers_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;

-- Insert default staff account (password is 'password')
INSERT INTO users (username, password, role, full_name, email) VALUES 
('staff', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 'Laundry Staff', 'staff@laundry.com');

-- Insert sample customer accounts (password is 'password')
INSERT INTO users (username, password, role, full_name, email, phone) VALUES 
('john_doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'John Doe', 'john@email.com', '09123456789'),
('jane_smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Jane Smith', 'jane@email.com', '09987654321');

-- Insert sample customers
INSERT INTO customers (name, phone, email, user_id) VALUES 
('John Doe', '09123456789', 'john@email.com', (SELECT id FROM users WHERE username = 'john_doe')),
('Jane Smith', '09987654321', 'jane@email.com', (SELECT id FROM users WHERE username = 'jane_smith'));

-- Insert default pricing config
INSERT INTO price_config (per_kg, per_shirt, per_pants) VALUES (50.00, 10.00, 15.00);

-- Insert sample orders
INSERT INTO orders (customer_id, type, weight, price, status) VALUES 
((SELECT id FROM customers WHERE name = 'John Doe'), 'Mixed Clothes', 5.0, 250.00, 'washing'),
((SELECT id FROM customers WHERE name = 'Jane Smith'), 'Shirts Only', 2.5, 125.00, 'ready');

-- Insert sample notifications
INSERT INTO notifications (to_customer_id, message) VALUES 
((SELECT id FROM customers WHERE name = 'John Doe'), 'Welcome to our laundry service!'),
((SELECT id FROM customers WHERE name = 'Jane Smith'), 'Your order #2 is now Ready. Total: ₱125.00');

-- Insert sample schedules
INSERT INTO schedules (customer_id, type, slot_time, status) VALUES 
((SELECT id FROM customers WHERE name = 'John Doe'), 'pickup', '2025-09-30 10:00:00', 'scheduled'),
((SELECT id FROM customers WHERE name = 'Jane Smith'), 'delivery', '2025-09-30 14:00:00', 'scheduled');
