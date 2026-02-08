# Smart Online Enrollment System

A comprehensive web-based student enrollment system with authentication, pricing management, and discount calculations.

## Features

- 🔐 **User Authentication**: Secure login system with session management
- 👥 **Role-Based Access**: Admin and Student roles with different permissions
- 📝 **Student Enrollment**: Complete enrollment form with validation
- 💰 **Dynamic Pricing**: Track-based enrollment fees with automatic discount calculations
- 📊 **Dashboard**: Admin dashboard for managing tracks and viewing statistics
- 📋 **Student Management**: View and manage all enrolled students
- 🎯 **Discount System**: Age-based discounts (10% for under 15, 5% for 15-17)

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

## Installation

### 1. Database Setup

```bash
# Create the database
mysql -u root -p
CREATE DATABASE enrollment_system;
exit;

# Import the schema
mysql -u root -p enrollment_system < schema.sql
```

### 2. Configure Database Connection

Edit `db_connection.php` and update the database credentials:

```php
$host = "localhost";
$username = "root";
$password = "your_password";
$database = "enrollment_system";
```

### 3. Deploy Files

Copy all PHP files to your web server's document root (e.g., `/var/www/html/` or `htdocs/`)

### 4. Set Permissions

```bash
chmod 755 *.php
```

## Usage

### Default Admin Credentials

- **Username**: admin
- **Password**: admin123

### Access Points

1. **Login Page**: `http://localhost/login.php`
2. **Enrollment Form**: `http://localhost/enrollment_form.php` (requires login)
3. **Student List**: `http://localhost/student_list.php` (requires login)
4. **Admin Dashboard**: `http://localhost/admin_dashboard.php` (admin only)

## Database Schema

### Tables

1. **Users**: User authentication and role management
2. **Track**: Educational tracks/strands with pricing
3. **Application**: Student enrollment applications
4. **Document**: Student documents (future feature)

### Pricing Structure

- STEM: ₱6,000.00
- ABM: ₱5,500.00
- HUMSS: ₱5,000.00
- GAS: ₱5,000.00
- TVL-ICT: ₱5,500.00

### Discount Rules

- **Age < 15**: 10% discount (Early bird)
- **Age 15-17**: 5% discount (Standard)
- **Age > 17**: No discount

## Features Breakdown

### 1. Login System (`login.php`)
- Secure password verification with PHP password_hash
- Session-based authentication
- Role-based redirection

### 2. Enrollment Form (`enrollment_form.php`)
- Real-time price calculation
- Discount preview
- Form validation
- Track selection with pricing

### 3. Student List (`student_list.php`)
- Comprehensive student roster
- Payment status tracking
- Revenue statistics
- Sortable data table

### 4. Admin Dashboard (`admin_dashboard.php`)
- Track management
- Enrollment statistics
- Revenue tracking
- Track-wise analytics

## Security Features

- ✅ SQL Injection prevention (Prepared statements)
- ✅ XSS protection (htmlspecialchars)
- ✅ Session management
- ✅ Password hashing
- ✅ Role-based access control

## Troubleshooting

### Connection Issues

If you see "Connection failed" error:
1. Check database credentials in `db_connection.php`
2. Verify MySQL service is running
3. Ensure database `enrollment_system` exists

### Login Issues

If login fails:
1. Verify the Users table exists
2. Check if admin user was created during schema import
3. Try re-running the schema.sql file

### Permission Issues

If you get permission errors:
```bash
chmod -R 755 /path/to/project
chown -R www-data:www-data /path/to/project  # For Apache on Linux
```

## Future Enhancements

- [ ] Document upload functionality
- [ ] Payment gateway integration
- [ ] Email notifications
- [ ] PDF enrollment certificate generation
- [ ] Student portal for application tracking
- [ ] Multi-language support

## Support

For issues and questions, please refer to the documentation or contact the system administrator.

## License

This project is for educational purposes.
