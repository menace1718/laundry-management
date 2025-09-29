-- Add users table for authentication
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

-- Insert default staff account
INSERT INTO users (username, password, role, full_name, email) VALUES 
('staff', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 'Laundry Staff', 'staff@laundry.com');
-- Password is 'password'

-- Insert sample customer accounts
INSERT INTO users (username, password, role, full_name, email, phone) VALUES 
('john_doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'John Doe', 'john@email.com', '09123456789'),
('jane_smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Jane Smith', 'jane@email.com', '09987654321');
-- Password is 'password' for all accounts

-- Update customers table to link with users
ALTER TABLE customers ADD COLUMN user_id INT NULL;
ALTER TABLE customers ADD CONSTRAINT fk_customers_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;

-- Link existing customers to user accounts (optional)
UPDATE customers SET user_id = (SELECT id FROM users WHERE username = 'john_doe' LIMIT 1) WHERE name = 'John Doe';
UPDATE customers SET user_id = (SELECT id FROM users WHERE username = 'jane_smith' LIMIT 1) WHERE name = 'Jane Smith';
