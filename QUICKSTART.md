# Quick Start Guide - Smart Online Enrollment System

Get your enrollment system up and running in **5 minutes**!

## 🚀 One-Command Setup

### For Linux/Mac Users:
```bash
chmod +x setup.sh
./setup.sh
```

### For Windows Users:
```batch
setup.bat
```

That's it! The script will:
1. ✅ Create the database
2. ✅ Import the schema with all tables
3. ✅ Load sample data (15 students, 6 users, 5 tracks)
4. ✅ Set up the uploads directory

## 📋 Manual Setup (Alternative)

If you prefer manual setup:

### Step 1: Create Database
```bash
mysql -u root -p -e "CREATE DATABASE enrollment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 2: Import Schema
```bash
mysql -u root -p enrollment_system < improved_schema.sql
```

### Step 3: Import Sample Data
```bash
mysql -u root -p enrollment_system < complete_sample_data.sql
```

### Step 4: Configure Database Connection
Edit `config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'enrollment_system');
```

## 🔑 Default Login Credentials

After setup, you can log in with these accounts:

| Username | Password | Role |
|----------|----------|------|
| **admin** | **admin123** | Administrator |
| staff1 | staff123 | Staff |
| registrar | registrar123 | Registrar |
| principal | principal123 | Principal |
| teacher1 | teacher123 | Teacher |

⚠️ **IMPORTANT:** Change these passwords after first login!

## 🎓 Sample Data Included

The system comes with complete sample data:

### **6 System Users**
- 1 Administrator
- 2 Staff members
- 1 Registrar
- 1 Principal
- 1 Teacher

### **5 Academic Tracks**
- **STEM** - Science, Technology, Engineering, Mathematics
- **ABM** - Accountancy, Business, Management
- **HUMSS** - Humanities and Social Sciences
- **GAS** - General Academic Strand
- **TVL-ICT** - Technical-Vocational-Livelihood (ICT)

### **15 Student Applications**
With various statuses:
- ✅ **6 Enrolled** - Fully enrolled students
- ⏳ **3 Approved** - Approved, awaiting enrollment
- 🔍 **2 Under Review** - Being reviewed by staff
- 📝 **2 Pending** - Newly submitted applications
- ❌ **1 Rejected** - Did not meet requirements
- 🚫 **1 Withdrawn** - Student withdrew

### **Complete Enrollment History**
- 34+ status change records
- Full audit trail for all applications
- Documented reasons for each change

## 🌐 Accessing the System

### Student Portal
```
http://localhost/index.php
```
- Click "Enroll Now" to submit new application
- Upload required documents
- Track application status

### Admin Portal
```
http://localhost/login.php
```
- Login with admin credentials
- View dashboard with statistics
- Manage applications and documents
- Verify student documents
- View enrollment history

## 📊 What You'll See

After setup, the **Admin Dashboard** will show:

### Statistics Cards
- 📊 **15 Total Applications**
- ⏳ **2 Pending Review**
- ✅ **3 Approved**
- 🎓 **6 Enrolled**

### Track Enrollment
Real-time capacity monitoring:
- STEM: 3/50 students (6% filled)
- ABM: 2/45 students (4.4% filled)
- HUMSS: 1/40 students (2.5% filled)
- And more...

### Recent Applications
View complete list of all 15 sample students with:
- Student names and LRN
- Chosen track/strand
- Application status
- Document verification status
- Quick action buttons

## ✅ Verify Installation

Check if everything is working:

### 1. Database Check
```sql
USE enrollment_system;

-- Should return 6 users
SELECT COUNT(*) FROM Users;

-- Should return 5 tracks
SELECT COUNT(*) FROM Track;

-- Should return 15 applications
SELECT COUNT(*) FROM Application;

-- Should return 15 documents
SELECT COUNT(*) FROM Document;

-- Should return 30+ history records
SELECT COUNT(*) FROM Enrollment_History;
```

### 2. Login Test
1. Go to `http://localhost/login.php`
2. Enter: admin / admin123
3. You should see the dashboard

### 3. Student Form Test
1. Go to `http://localhost/enrollment_form.php`
2. Form should display with track options
3. All 5 tracks should be visible

## 🔧 Troubleshooting

### Database Connection Error
```
Error: Database connection failed
```
**Solution:** Check `config.php` - update DB credentials

### Setup Script Fails
```
Error: MySQL not found
```
**Solution:** Install MySQL or add to system PATH

### Cannot Login
```
Invalid username or password
```
**Solution:** 
1. Verify you imported `complete_sample_data.sql`
2. Use exact credentials: `admin` / `admin123`
3. Check Users table has records

### No Sample Data
```
Dashboard shows 0 applications
```
**Solution:** Import sample data:
```bash
mysql -u root -p enrollment_system < complete_sample_data.sql
```

## 📁 File Structure

After setup, you should have:

```
enrollment_system/
├── config.php                    # ✅ Database config
├── improved_schema.sql           # ✅ Database schema
├── complete_sample_data.sql      # ✅ Sample data
├── setup.sh                      # ✅ Linux/Mac setup
├── setup.bat                     # ✅ Windows setup
├── index.php                     # ✅ Homepage
├── enrollment_form.php           # ✅ Student form
├── login.php                     # ✅ Admin login
├── admin_dashboard.php           # ✅ Dashboard
├── upload_documents.php          # ✅ Document upload
├── view_application.php          # ✅ Application viewer
├── uploads/                      # ✅ Upload directory
└── Documentation files...
```

## 🎯 Next Steps

After setup:

1. ✅ **Login as Admin**
   - Username: admin
   - Password: admin123

2. ✅ **Explore the Dashboard**
   - View statistics
   - Check sample applications
   - Review track enrollment

3. ✅ **Test Student Application**
   - Submit a new enrollment
   - Upload documents
   - Track the status

4. ✅ **Manage Applications**
   - Update application status
   - Verify documents
   - View history

5. ✅ **Customize**
   - Add/edit tracks
   - Create new users
   - Configure settings

## 🔐 Security Reminder

**Before going to production:**

1. ❗ Change all default passwords
2. ❗ Update database credentials in config.php
3. ❗ Enable HTTPS
4. ❗ Set proper file permissions
5. ❗ Configure firewall rules

## 📚 Documentation

For more details, see:
- `README.md` - Complete documentation
- `INSTALLATION_GUIDE.md` - Detailed installation
- `SCHEMA_IMPROVEMENTS.md` - Database details
- `PROJECT_SUMMARY.md` - Project overview

## 💡 Tips

- **Password Format**: All sample passwords follow pattern: `{role}123`
- **LRN Format**: 15-digit numbers for student identification
- **File Uploads**: Stored in `uploads/{LRN}/` directories
- **Audit Trail**: Every status change is logged automatically

## 🎉 You're Ready!

Your Smart Online Enrollment System is now fully configured with:
- ✅ Complete database schema
- ✅ Sample users and students
- ✅ Working admin dashboard
- ✅ Functional enrollment forms
- ✅ Document management
- ✅ Full audit trail

**Start enrolling students today!** 🎓

---

**Need Help?** Check the troubleshooting section or review the full documentation.
