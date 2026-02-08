# Quick Setup Guide - Smart Online Enrollment System

> **🚨 GETTING 404 ERROR?** See [QUICK_FIX.md](QUICK_FIX.md) or [APACHE_SETUP.md](APACHE_SETUP.md)

---

## ⚡ Quick Start (For XAMPP Users)

### If You're Getting "404 Not Found" Error:

1. **Copy ALL files to:** `C:\xampp\htdocs\enrollment\`
2. **Start Apache & MySQL** in XAMPP Control Panel
3. **Import database:** Open `http://localhost/phpmyadmin`, create `enrollment_system`, import `schema.sql`
4. **Access:** `http://localhost/enrollment/check_system.php` ← This checks everything!
5. **Login:** `http://localhost/enrollment/` with username: `admin`, password: `admin123`

---

## Prerequisites
- XAMPP/WAMP/LAMP or any PHP development environment
- MySQL 5.7+ or MariaDB
- PHP 7.4+
- Modern web browser

## Step-by-Step Installation

### Step 1: Setup Web Server
1. Install XAMPP (recommended for beginners)
   - Download from https://www.apachefriends.org/
   - Install and start Apache and MySQL services

### Step 2: Copy Project Files
1. Copy all project files to your web server directory:
   - For XAMPP: `C:\xampp\htdocs\enrollment\`
   - For Linux: `/var/www/html/enrollment/`

### Step 3: Create Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Click "New" to create a new database
3. Name it: `enrollment_system`
4. Click "Create"

### Step 4: Import Database Schema
1. Select the `enrollment_system` database
2. Click "Import" tab
3. Click "Choose File" and select `schema.sql`
4. Click "Go" to import

**OR** use command line:
```bash
mysql -u root -p enrollment_system < schema.sql
```

### Step 5: Configure Database Connection (if needed)
Edit `db_connection.php` if your MySQL credentials are different:
```php
$host = "localhost";
$username = "root";  // Change if different
$password = "";      // Add your MySQL password
$database = "enrollment_system";
```

### Step 6: Access the System
Open your web browser and navigate to:
- http://localhost/enrollment/

You will be redirected to the login page.

### Step 7: Login with Default Credentials
- **Username**: admin
- **Password**: admin123

## Verification Checklist

✅ Apache server is running
✅ MySQL server is running
✅ Database `enrollment_system` exists
✅ All tables are created (Users, Track, Application, Document)
✅ Sample data is inserted (admin user and 5 tracks)
✅ Can access login page
✅ Can login with admin credentials

## Common Issues & Solutions

### "Connection failed" Error
- Check if MySQL is running
- Verify database credentials in `db_connection.php`
- Ensure `enrollment_system` database exists

### "Call to undefined function mysqli_connect()"
- Enable mysqli extension in php.ini
- Uncomment: `extension=mysqli`
- Restart Apache

### "Access denied" Error
- Check MySQL username and password
- Default XAMPP: username=root, password=(empty)
- Default WAMP: username=root, password=(empty)

### Can't Access Files
- Check file permissions (Linux):
  ```bash
  chmod -R 755 /path/to/enrollment
  ```

## Default Data

### Admin User
- Username: admin
- Password: admin123
- Role: admin

### Available Tracks (with pricing)
1. STEM - ₱6,000.00
2. ABM - ₱5,500.00
3. HUMSS - ₱5,000.00
4. GAS - ₱5,000.00
5. TVL-ICT - ₱5,500.00

## Discount System
- Age < 15: 10% discount
- Age 15-17: 5% discount
- Age > 17: No discount

## Next Steps

1. **Login** to the system
2. **Enroll** a test student
3. **View** the student list
4. **Access** admin dashboard (admin only)
5. **Add** new tracks with custom pricing

## File Structure
```
enrollment/
├── index.php                 # Entry point (redirects to login)
├── login.php                 # Login page
├── logout.php                # Logout handler
├── enrollment_form.php       # Student enrollment form
├── student_list.php          # View all students
├── admin_dashboard.php       # Admin panel
├── db_connection.php         # Database configuration
├── schema.sql                # Database schema
├── README.md                 # Main documentation
└── SETUP.md                  # This file
```

## Security Notes

⚠️ **Important for Production:**
1. Change default admin password immediately
2. Use strong passwords for all users
3. Update database credentials
4. Enable HTTPS
5. Regular backups
6. Keep PHP and MySQL updated

## Support

For detailed documentation, see `README.md`

## Testing the System

1. **Test Enrollment:**
   - Login as admin
   - Go to enrollment form
   - Fill in student details
   - Select a track
   - Submit and verify price calculation

2. **Test Admin Features:**
   - Login as admin
   - Access admin dashboard
   - Add a new track
   - View statistics

3. **Test Student List:**
   - View enrolled students
   - Check payment status
   - Verify revenue calculations

## Congratulations! 🎉

Your Smart Online Enrollment System is now ready to use!
