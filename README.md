# 🧺 Laundry Management System

A comprehensive web-based laundry management system built with PHP, MySQL, and Bootstrap. Features staff and customer portals with complete order tracking, pricing management, scheduling, and notifications.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

## ✨ Features

### 🔐 Authentication System
- **Staff Login**: Full management access
- **Customer Login**: Personal order tracking
- **Customer Registration**: Self-service account creation
- **Role-based Access Control**: Secure permission system

### 👥 Staff Dashboard
- **Account Management**: Create/edit/delete user accounts
- **Customer Management**: Add/remove customers with order protection
- **Order Management**: Create orders, update status, track progress
- **Pricing Configuration**: Editable rates (₱/kg, ₱/shirt, ₱/pants)
- **Quick Calculator**: Instant price calculations
- **Schedule Management**: Pickup/delivery time slots
- **Notifications**: Send messages to customers

### 👤 Customer Dashboard  
- **Personal Orders**: View order history and status
- **Schedule Tracking**: Pickup/delivery appointments
- **Notifications**: Receive updates from staff
- **Order Status**: Real-time tracking (received → washing → ready → delivered)

### 🔧 Technical Features
- **RESTful API**: Clean PHP backend with JSON responses
- **Responsive Design**: Bootstrap 5 with mobile support
- **Real-time Updates**: Dynamic content loading
- **Auto Notifications**: Status change alerts
- **Data Validation**: Frontend and backend validation
- **SQL Injection Protection**: Secure database queries

## 🚀 Quick Setup

### Prerequisites
- XAMPP (Apache + MySQL + PHP 8+)
- Web browser

### Installation Steps

1. **Clone/Download** this repository to your XAMPP htdocs folder:
   ```
   C:/xampp/htdocs/laundry-management/
   ```

2. **Start XAMPP Services**:
   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL**

3. **Create Database**:
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create database: `laundry_db`
   - Import: `sql/complete_setup.sql`

4. **Access Application**:
   - URL: `http://localhost/laundry-management/`
   - Redirects to login page automatically

### 🔑 Demo Accounts

| Role | Username | Password | Access Level |
|------|----------|----------|--------------|
| Staff | `staff` | `password` | Full management |
| Customer | `john_doe` | `password` | Personal orders |
| Customer | `jane_smith` | `password` | Personal orders |

## 📱 Screenshots

### Staff Dashboard
- Complete management interface
- Order tracking and status updates
- Customer and account management
- Pricing configuration

### Customer Portal
- Personal order history
- Schedule tracking
- Notification center
- Clean, user-friendly interface

## 🛠️ Technology Stack

- **Backend**: PHP 8+ with MySQLi
- **Database**: MySQL 8+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Bootstrap 5.3.3
- **Icons**: Font Awesome 6.5.2
- **Server**: Apache (XAMPP)

## 📊 Database Schema

### Core Tables
- `users` - Authentication and user management
- `customers` - Customer information and profiles  
- `orders` - Laundry orders with status tracking
- `notifications` - Message system
- `schedules` - Pickup/delivery appointments
- `price_config` - Pricing configuration

## 🔧 Configuration

### Database Connection
Edit `api/db.php` if needed:
```php
$DB_HOST = 'localhost';
$DB_USER = 'root';        // Change if different
$DB_PASS = '';            // Change if you have a password
$DB_NAME = 'laundry_db';
```

### Default Pricing
- Per Kg: ₱50.00
- Per Shirt: ₱10.00  
- Per Pants: ₱15.00

*Prices are fully configurable through the staff dashboard*

## 🎯 Usage Examples

### For Staff:
1. Login with staff credentials
2. Manage customers and orders
3. Update order status (received → washing → ready → delivered)
4. Configure pricing rates
5. Send notifications to customers
6. Manage user accounts

### For Customers:
1. Register new account or login
2. View personal order history
3. Track order status in real-time
4. Check pickup/delivery schedule
5. Receive notifications from staff

## 🔒 Security Features

- **Password Hashing**: bcrypt encryption
- **SQL Injection Protection**: Prepared statements and escaping
- **Session Management**: Secure PHP sessions
- **Role-based Access**: Staff/customer permission separation
- **Input Validation**: Frontend and backend validation
- **CSRF Protection**: Form token validation

## 🚀 API Endpoints

| Endpoint | Actions | Description |
|----------|---------|-------------|
| `api/auth.php` | login, logout, register, check_session | Authentication |
| `api/customers.php` | create, list, delete | Customer management |
| `api/orders.php` | create, list, update_status, list_by_customer | Order management |
| `api/pricing.php` | get_config, set_config, calculate | Pricing system |
| `api/notifications.php` | send, list_by_customer, list_all | Messaging |
| `api/schedule.php` | create_slot, list_all, update_status | Scheduling |
| `api/accounts.php` | list_all, create_staff, update_user, delete_user | Account management |

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 🐛 Known Issues

- Email/SMS notifications require external service integration
- No file upload functionality for order attachments
- Basic reporting features (can be extended)

## 🔮 Future Enhancements

- [ ] Email/SMS integration for notifications
- [ ] Advanced reporting and analytics
- [ ] File upload for order photos
- [ ] Payment integration
- [ ] Mobile app development
- [ ] Multi-language support

## 📞 Support

If you encounter any issues or have questions:

1. Check the [Issues](../../issues) section
2. Create a new issue with detailed description
3. Include error messages and steps to reproduce

## 🙏 Acknowledgments

- Bootstrap team for the excellent CSS framework
- Font Awesome for the comprehensive icon library
- XAMPP team for the easy development environment
- PHP and MySQL communities for robust backend technologies

---

**Made with ❤️ for laundry businesses worldwide**
