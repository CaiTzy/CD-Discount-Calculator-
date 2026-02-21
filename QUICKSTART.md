# Quick Start Guide
## Smart Online Enrollment System

Get up and running in 5 minutes!

## Prerequisites
- XAMPP/WAMP/LAMP/MAMP installed
- Web browser

## Installation Steps

### Step 1: Setup Web Files
Copy all project files to your web server directory:
- **XAMPP:** `C:\xampp\htdocs\enrollment\`
- **WAMP:** `C:\wamp64\www\enrollment\`
- **Linux:** `/var/www/html/enrollment/`

### Step 2: Create Database
Open your MySQL command line or phpMyAdmin and run:

```bash
# Using MySQL command line
mysql -u root -p
```

Then execute:
```sql
CREATE DATABASE enrollment_system;
USE enrollment_system;
SOURCE /path/to/schema.sql;
SOURCE /path/to/sample_data.sql;
exit;
```

**OR using single command:**
```bash
mysql -u root -p enrollment_system < schema.sql
mysql -u root -p enrollment_system < sample_data.sql
```

**OR using phpMyAdmin:**
1. Create database: `enrollment_system`
2. Import `schema.sql`
3. Import `sample_data.sql` (optional, for test data)

### Step 3: Configure Database Connection
Edit `config.php` if your MySQL password is not empty:
```php
define('DB_PASS', 'your_mysql_password');
```

### Step 4: Set Permissions
Make sure the uploads directory is writable:
```bash
chmod 755 uploads/
```

### Step 5: Access the System
Open your browser and go to:
```
http://localhost/enrollment/
```

Login with:
- **Username:** admin
- **Password:** admin123

## What's Included with Sample Data?

✅ **15 Sample Students** with various statuses:
- 5 Enrolled
- 3 Approved  
- 3 Pending
- 2 Under Review
- 1 Rejected
- 1 Withdrawn

✅ **8 Document Records** (for enrolled/approved students)

✅ **35+ History Records** showing complete audit trail

✅ **5 Educational Tracks:**
- STEM (Science, Technology, Engineering, Mathematics)
- ABM (Accountancy, Business, and Management)
- HUMSS (Humanities and Social Sciences)
- GAS (General Academic Strand)
- TVL (Technical-Vocational-Livelihood)

## First Steps After Login

1. **View Dashboard** (Admin only)
   - Click "Dashboard" in navigation
   - See comprehensive statistics

2. **Browse Students**
   - Click "Students" → "View All Students"
   - Try search and filters

3. **Create New Application**
   - Click "Students" → "New Application"
   - Fill in the form (age auto-calculates!)

4. **Upload Documents**
   - Click "Documents" → "Upload Documents"
   - Enter a student LRN (e.g., 100123456791)

5. **View Application Details**
   - Click any student's "eye" icon
   - See complete information and history

## Testing the System

### Test Scenarios

**1. New Enrollment**
```
LRN: 100123456804
Name: Test Student
Birthdate: 2006-01-01
Track: STEM
```

**2. Search Functionality**
- Search: "Juan"
- Filter by Status: "Enrolled"
- Filter by Track: "STEM"

**3. Document Upload**
- Use LRN: 100123456791 (has no documents yet)
- Upload any PDF/JPG files

**4. Status Update** (Admin only)
- View student: 100123456791
- Change status from "Pending" to "Under Review"
- Add notes
- Check history to see it was logged

## Troubleshooting

### Can't Connect to Database
- Check MySQL is running
- Verify credentials in `config.php`
- Check database name is correct

### Page Not Found (404)
- Verify files are in correct web directory
- Check Apache/Nginx is running

### Upload Fails
- Check `uploads/` directory exists
- Verify directory permissions (755)
- Check file size (max 5MB)

### Login Doesn't Work
- Verify `schema.sql` was imported (includes admin user)
- Default password is `admin123`
- Clear browser cookies/cache

## Directory Structure
```
enrollment/
├── config.php              # Database & app config
├── db_connection.php       # Database helpers
├── schema.sql             # Database structure
├── sample_data.sql        # Test data
├── login.php              # Login page
├── index.php              # Homepage
├── enrollment_form.php    # New enrollment
├── student_list.php       # Student management
├── view_application.php   # Student details
├── upload_documents.php   # Document upload
├── admin_dashboard.php    # Admin panel
├── header.php             # Navigation
├── footer.php             # Footer
└── uploads/               # Uploaded files
```

## Default Users

| Username | Password | Role |
|----------|----------|------|
| admin | admin123 | Admin |

**Security Note:** Change default password immediately in production!

## Features to Try

✅ **Auto-Age Calculation**
- Enter birthdate in enrollment form
- Watch age calculate automatically

✅ **Duplicate Detection**
- Try to enroll student with existing LRN
- System prevents duplicates

✅ **Advanced Search**
- Search by LRN or name
- Filter by status and track
- Navigate through pages

✅ **Document Tracking**
- Upload documents for students
- See upload status on student list

✅ **Status Workflow**
- Pending → Under Review → Approved → Enrolled

✅ **Complete History**
- Every status change is logged
- View complete audit trail

## Next Steps

1. ✅ Change admin password
2. ✅ Add more users (Staff, Registrar)
3. ✅ Customize tracks/tuition fees
4. ✅ Set up for production (secure config)
5. ✅ Add more students

## Support

- Check **README.md** for detailed documentation
- Review **schema.sql** for database structure
- Inspect PHP files for code examples

---

**Ready to go!** 🚀

If you followed all steps, your Smart Online Enrollment System should now be running at:
**http://localhost/enrollment/**
