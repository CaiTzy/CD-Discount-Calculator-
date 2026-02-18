# Apache Server Setup Guide

## Fixing "404 Not Found" Error

If you're getting a "404 Not Found" error when accessing the enrollment system, follow these steps:

---

## Solution 1: Verify File Location (Most Common)

### For XAMPP on Windows:

1. **Locate your XAMPP htdocs directory:**
   - Default: `C:\xampp\htdocs\`

2. **Create a project folder:**
   ```
   C:\xampp\htdocs\enrollment\
   ```

3. **Copy ALL project files into this folder:**
   - Copy all `.php` files
   - Copy `schema.sql`
   - Copy `.htaccess` file
   - Copy all `.md` documentation files

4. **Your folder structure should look like:**
   ```
   C:\xampp\htdocs\enrollment\
   ├── .htaccess
   ├── index.php
   ├── login.php
   ├── enrollment_form.php
   ├── student_list.php
   ├── admin_dashboard.php
   ├── db_connection.php
   ├── logout.php
   ├── schema.sql
   └── ... (other files)
   ```

5. **Access the application at:**
   ```
   http://localhost/enrollment/
   ```
   OR
   ```
   http://localhost/enrollment/index.php
   ```

---

## Solution 2: Check Apache Configuration

### Verify Apache is Running:

1. **Open XAMPP Control Panel**
2. **Check if Apache shows "Running" status** (green)
3. **If not, click "Start" next to Apache**

### Verify Port 80 is Available:

1. **Check if another application is using port 80:**
   - Skype, IIS, or other web servers might block port 80
   
2. **Test Apache status:**
   - Visit `http://localhost/`
   - You should see the XAMPP welcome page

---

## Solution 3: Enable Required Apache Modules

Make sure these modules are enabled in Apache:

### For XAMPP:

1. **Open:** `C:\xampp\apache\conf\httpd.conf`

2. **Ensure these lines are NOT commented (no # at start):**
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   LoadModule headers_module modules/mod_headers.so
   LoadModule deflate_module modules/mod_deflate.so
   LoadModule expires_module modules/mod_expires.so
   LoadModule mime_module modules/mod_mime.so
   ```

3. **Find this section and ensure AllowOverride is set to All:**
   ```apache
   <Directory "C:/xampp/htdocs">
       Options Indexes FollowSymLinks Includes ExecCGI
       AllowOverride All
       Require all granted
   </Directory>
   ```

4. **Restart Apache** after any config changes

---

## Solution 4: Verify PHP is Working

### Test PHP Installation:

1. **Create a test file:** `C:\xampp\htdocs\test.php`
   ```php
   <?php
   phpinfo();
   ?>
   ```

2. **Access:** `http://localhost/test.php`

3. **You should see PHP information page**

4. **Delete the test file after verification**

---

## Solution 5: Check File Permissions (Linux/Mac)

If on Linux or Mac:

```bash
# Navigate to your web directory
cd /var/www/html/enrollment/

# Set proper ownership (adjust user as needed)
sudo chown -R www-data:www-data .

# Set proper permissions
sudo chmod -R 755 .
sudo chmod 644 *.php
sudo chmod 600 db_connection.php
```

---

## Solution 6: Database Connection Issues

If files load but database errors occur:

1. **Ensure MySQL is running in XAMPP**

2. **Verify database exists:**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Check if `enrollment_system` database exists
   - If not, import `schema.sql`

3. **Check db_connection.php settings:**
   ```php
   $host = "localhost";
   $username = "root";
   $password = "";  // Usually empty for XAMPP
   $database = "enrollment_system";
   ```

---

## Troubleshooting Steps

### Step 1: Verify Apache Access Log

**XAMPP Location:** `C:\xampp\apache\logs\access.log`

Check for 404 errors to see what URL Apache is trying to access.

### Step 2: Check Apache Error Log

**XAMPP Location:** `C:\xampp\apache\logs\error.log`

Look for PHP errors or module loading issues.

### Step 3: Test Direct File Access

Try accessing files directly:
- `http://localhost/enrollment/login.php`
- `http://localhost/enrollment/index.php`

If these work but `http://localhost/enrollment/` doesn't:
- The .htaccess file might not be working
- Check AllowOverride settings in httpd.conf

### Step 4: Disable .htaccess Temporarily

Rename `.htaccess` to `.htaccess.bak` and test if files load.

If they do, the issue is in the .htaccess configuration.

---

## Quick Fix Checklist

- [ ] Apache is running (green in XAMPP Control Panel)
- [ ] Files are in correct directory (htdocs/enrollment/)
- [ ] Port 80 is available (no conflicts)
- [ ] PHP is working (`http://localhost/test.php`)
- [ ] MySQL is running
- [ ] Database `enrollment_system` exists
- [ ] db_connection.php has correct credentials
- [ ] .htaccess file is present
- [ ] AllowOverride All is set in httpd.conf
- [ ] Required Apache modules are enabled

---

## Common Error Messages & Solutions

### "403 Forbidden"
**Cause:** Permission issues or directory listing disabled
**Fix:** Check file permissions and Directory configuration in httpd.conf

### "500 Internal Server Error"
**Cause:** .htaccess syntax error or PHP error
**Fix:** Check Apache error log, verify .htaccess syntax

### "Connection refused"
**Cause:** Apache not running
**Fix:** Start Apache in XAMPP Control Panel

### "Database connection failed"
**Cause:** MySQL not running or wrong credentials
**Fix:** Start MySQL, verify db_connection.php settings

---

## Correct URL Formats

✅ **Correct:**
- `http://localhost/enrollment/`
- `http://localhost/enrollment/index.php`
- `http://localhost/enrollment/login.php`

❌ **Incorrect:**
- `http://localhost/` (if files are in subfolder)
- `file:///C:/xampp/htdocs/enrollment/index.php` (local file path)
- `http://localhost:8080/` (wrong port if Apache uses 80)

---

## After Fixing the Issue

Once you can access the application:

1. **Login with default credentials:**
   - Username: `admin`
   - Password: `admin123`

2. **Change the default password immediately**

3. **Configure db_connection.php for production** (set proper password)

---

## Still Having Issues?

1. **Restart your computer** - Fixes many port conflicts
2. **Reinstall XAMPP** - Ensure clean installation
3. **Check Windows Firewall** - May block Apache
4. **Run XAMPP as Administrator** - Permission issues
5. **Review complete SETUP.md** - Complete installation guide

---

## Testing Your Setup

After fixing, test these URLs:

1. **Home/Login:** `http://localhost/enrollment/`
   - Should redirect to login page

2. **Login Page:** `http://localhost/enrollment/login.php`
   - Should show login form

3. **Direct Access Test:** `http://localhost/enrollment/enrollment_form.php`
   - Should redirect to login (if not logged in)

4. **phpMyAdmin:** `http://localhost/phpmyadmin`
   - Should show database management interface

---

## Production Deployment

For production environments:

1. Use a proper domain name
2. Enable HTTPS with SSL certificate
3. Set strong database passwords
4. Enable production error logging
5. Disable debug mode
6. Set proper file permissions
7. Regular security updates

---

For more help, see:
- **SETUP.md** - Complete installation guide
- **README.md** - System documentation
- **FEATURES.md** - Feature list

---

**Need more help?** Check the Apache error logs and share the exact error message for more specific guidance.
