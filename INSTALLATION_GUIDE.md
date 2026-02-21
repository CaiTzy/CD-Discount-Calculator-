# Smart Online Enrollment System - Installation Guide

## Prerequisites
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.2+
- Apache/Nginx web server
- phpMyAdmin (optional, for database management)

## Installation Steps

### Step 1: Database Setup

1. Create a new MySQL database:
```sql
CREATE DATABASE enrollment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import the schema:
```bash
mysql -u root -p enrollment_system < improved_schema.sql
```

Or use phpMyAdmin:
- Go to phpMyAdmin
- Create database `enrollment_system`
- Import `improved_schema.sql`

### Step 2: Configure Database Connection

1. Open `config.php`
2. Update the database credentials:

```php
define('DB_HOST', 'localhost');     // Your database host
define('DB_USER', 'root');          // Your database username
define('DB_PASS', '');              // Your database password
define('DB_NAME', 'enrollment_system'); // Your database name
```

### Step 3: Create Default Admin User

Run this SQL query to create the default admin account:

```sql
INSERT INTO Users (username, password_hash, email, full_name, role, is_active) 
VALUES (
    'admin', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- Password: admin123
    'admin@school.edu', 
    'System Administrator', 
    'Admin', 
    1
);
```

**Default Login Credentials:**
- Username: `admin`
- Password: `admin123`

**⚠️ IMPORTANT:** Change the password after first login!

### Step 4: Set Up File Permissions

Create the uploads directory and set permissions:

```bash
mkdir -p uploads
chmod 755 uploads
```

For Linux/Mac:
```bash
sudo chown -R www-data:www-data uploads/
```

### Step 5: Configure Web Server

#### Apache (.htaccess)

Create `.htaccess` in the root directory:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Protect uploads directory from direct access (optional)
    RewriteRule ^uploads/ - [F,L]
</IfModule>

# Prevent directory listing
Options -Indexes

# PHP settings
php_value upload_max_filesize 5M
php_value post_max_size 5M
php_value max_execution_time 300
```

#### Nginx Configuration

Add to your Nginx site configuration:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}

# Increase upload size
client_max_body_size 5M;
```

### Step 6: Test Installation

1. Navigate to your site in a browser: `http://localhost/enrollment_system/`
2. You should see the index page
3. Click "Admin Login" and use the default credentials
4. Click "Student Enrollment" to test the enrollment form

## File Structure

```
enrollment_system/
├── config.php                  # Database configuration
├── index.php                   # Home page
├── enrollment_form.php         # Student enrollment form
├── upload_documents.php        # Document upload page
├── login.php                   # Admin login
├── admin_dashboard.php         # Admin dashboard
├── view_application.php        # View application details
├── logout.php                  # Logout handler
├── improved_schema.sql         # Database schema
├── SCHEMA_IMPROVEMENTS.md      # Schema documentation
├── INSTALLATION_GUIDE.md       # This file
├── uploads/                    # File uploads directory
│   ├── LRN123456/             # Documents organized by LRN
│   └── LRN789012/
└── .htaccess                   # Apache configuration
```

## Security Recommendations

### 1. Change Default Password
After first login, create a new admin user and delete the default one:

```sql
-- Create new admin
INSERT INTO Users (username, password_hash, email, full_name, role) 
VALUES ('your_username', PASSWORD('your_secure_password'), 'your@email.com', 'Your Name', 'Admin');

-- Delete default admin (after confirming new admin works)
DELETE FROM Users WHERE username = 'admin';
```

### 2. Secure Password Hashing
The system uses PHP's `password_hash()` function with bcrypt. Never store plain text passwords.

To create a password hash:
```php
$hash = password_hash('your_password', PASSWORD_DEFAULT);
```

### 3. Protect Sensitive Files

Create a `.htaccess` file in the uploads directory:

```apache
# uploads/.htaccess
Order Deny,Allow
Deny from all
<FilesMatch "\.(jpg|jpeg|png|pdf)$">
    Allow from all
</FilesMatch>
```

### 4. Enable HTTPS
Always use HTTPS in production. Get a free SSL certificate from Let's Encrypt:

```bash
sudo certbot --apache
```

### 5. Regular Backups

Backup your database regularly:

```bash
# Daily backup script
mysqldump -u root -p enrollment_system > backup_$(date +%Y%m%d).sql
```

## Troubleshooting

### Database Connection Error
- Verify database credentials in `config.php`
- Ensure MySQL service is running
- Check if database exists

### File Upload Not Working
- Check directory permissions: `chmod 755 uploads/`
- Verify `upload_max_filesize` in php.ini
- Ensure `uploads/` directory exists

### Session Issues
- Check that sessions are enabled in php.ini
- Verify session save path is writable
- Clear browser cookies

### Blank Page / White Screen
- Enable error reporting in `config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```
- Check Apache/PHP error logs

## Features

### Student Features
- ✅ Online enrollment form
- ✅ Document upload system
- ✅ Track/Strand selection
- ✅ Application status tracking

### Admin Features
- ✅ Dashboard with statistics
- ✅ Application management
- ✅ Document verification
- ✅ Status updates with history
- ✅ Track enrollment statistics
- ✅ Multi-user support with roles

### Database Features
- ✅ Full ACID compliance
- ✅ Foreign key constraints
- ✅ Data validation
- ✅ Audit trail
- ✅ Automatic timestamps
- ✅ Database views for reports
- ✅ Stored procedures
- ✅ Triggers for business rules

## Customization

### Adding New Tracks
```sql
INSERT INTO Track (strand_course, track_code, description, capacity, tuition_fee, is_active) 
VALUES ('Your Track Name', 'CODE-01', 'Description here', 40, 25000.00, 1);
```

### Adding New Users
```sql
INSERT INTO Users (username, password_hash, email, full_name, role, is_active) 
VALUES ('username', PASSWORD('password'), 'email@example.com', 'Full Name', 'Staff', 1);
```

### Changing Site Name
Edit `config.php`:
```php
define('SITE_NAME', 'Your School Name - Enrollment System');
```

## Support

For issues or questions:
1. Check the troubleshooting section
2. Review the SCHEMA_IMPROVEMENTS.md documentation
3. Verify all installation steps were completed

## License

This system is provided as-is for educational purposes.

## Version History

- v1.0 (2024) - Initial release
  - Complete enrollment system
  - Admin dashboard
  - Document management
  - Improved database schema with validation
