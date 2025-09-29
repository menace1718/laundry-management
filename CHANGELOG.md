# Changelog

All notable changes to the Laundry Management System will be documented in this file.

## [1.0.0] - 2025-09-29

### Added
- **Authentication System**
  - Staff and customer login functionality
  - Customer self-registration
  - Role-based access control
  - Session management

- **Staff Dashboard**
  - Complete order management system
  - Customer management with delete protection
  - User account management (create/edit/delete)
  - Pricing configuration with calculator
  - Schedule management
  - Notification system

- **Customer Dashboard**
  - Personal order tracking
  - Schedule viewing
  - Notification center
  - Order status tracking

- **Core Features**
  - RESTful API architecture
  - Real-time order status updates
  - Automatic notifications on status changes
  - Pricing calculator (by weight and item type)
  - Responsive Bootstrap 5 design
  - SQL injection protection
  - Password hashing with bcrypt

- **Database Schema**
  - Users table with role management
  - Customers table with user linking
  - Orders table with status tracking
  - Notifications system
  - Schedule management
  - Configurable pricing

### Technical Details
- PHP 8+ backend with MySQLi
- Bootstrap 5.3.3 frontend
- Font Awesome 6.5.2 icons
- JavaScript ES6+ for dynamic interactions
- XAMPP development environment

### Security Features
- Password hashing (bcrypt)
- SQL injection protection
- Session-based authentication
- Role-based access control
- Input validation (frontend & backend)

### API Endpoints
- `/api/auth.php` - Authentication management
- `/api/customers.php` - Customer operations
- `/api/orders.php` - Order management
- `/api/pricing.php` - Pricing system
- `/api/notifications.php` - Messaging system
- `/api/schedule.php` - Scheduling operations
- `/api/accounts.php` - User account management
