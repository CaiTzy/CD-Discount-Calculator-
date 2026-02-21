# Smart Online Enrollment System

A complete web-based enrollment management system built with PHP and MySQL, featuring an improved database schema with comprehensive validation, security, and audit capabilities.

## ✨ NEW: Complete Sample Data Included!

This system now comes with **complete, ready-to-use sample data**:
- 🎓 **15 realistic student applications** with various statuses
- 👥 **6 system users** (Admin, Staff, Registrar, Principal, Teacher)
- 📚 **5 academic tracks** (STEM, ABM, HUMSS, GAS, TVL-ICT)
- 📄 **Complete document records** with verification statuses
- 📊 **Full enrollment history** (34 audit trail records)

**One-command setup:** Run `./setup.sh` (Linux/Mac) or `setup.bat` (Windows) and you're ready to go!

See [QUICKSTART.md](QUICKSTART.md) for instant setup instructions.

## 🎯 Features

### For Students
- **Online Enrollment Form** - Fill out enrollment applications from anywhere
- **Document Upload System** - Submit required documents digitally (Birth Certificate, Diploma, Good Moral, Report Card, 2x2 Photo)
- **Multiple Track Options** - Choose from STEM, ABM, HUMSS, GAS, and TVL-ICT
- **Application Tracking** - Monitor application status in real-time
- **Mobile Responsive** - Works on any device

### For Administrators
- **Comprehensive Dashboard** - View statistics and recent applications
- **Application Management** - Review, approve, or reject applications
- **Document Verification** - Verify submitted documents with status tracking
- **Track Statistics** - Monitor enrollment by track/strand with capacity tracking
- **User Management** - Multi-user support with role-based access (Admin, Staff, Registrar, etc.)
- **Audit Trail** - Complete history of all status changes
- **Reports & Analytics** - Real-time enrollment statistics and reports

### Database Features
- ✅ **Improved Schema** with comprehensive indexes for performance
- ✅ **Data Validation** via CHECK constraints
- ✅ **Foreign Key Constraints** for referential integrity
- ✅ **Audit Fields** (created_at, updated_at) on all tables
- ✅ **Database Views** for complex queries
- ✅ **Stored Procedures** for business logic
- ✅ **Triggers** for automatic validation
- ✅ **utf8mb4** character set for full Unicode support
- ✅ **Transaction Support** with InnoDB engine

## 📋 System Requirements

- **PHP** 7.4 or higher
- **MySQL** 5.7+ or MariaDB 10.2+
- **Apache/Nginx** web server
- **Minimum 50MB** disk space
- **Modern web browser** (Chrome, Firefox, Safari, Edge)

## 🚀 Quick Start

### Automated Setup (Recommended)

**For Linux/Mac:**
```bash
git clone https://github.com/CaiTzy/CD-Discount-Calculator-.git
cd CD-Discount-Calculator-
chmod +x setup.sh
./setup.sh
```

**For Windows:**
```batch
git clone https://github.com/CaiTzy/CD-Discount-Calculator-.git
cd CD-Discount-Calculator-
setup.bat
```

That's it! The script automatically:
- ✅ Creates the database
- ✅ Imports the schema
- ✅ Loads complete sample data (15 students, 6 users)
- ✅ Sets up uploads directory

**See [QUICKSTART.md](QUICKSTART.md) for detailed instructions.**

### Manual Setup (Alternative)

<details>
<summary>Click to expand manual setup steps</summary>

#### 1. Clone Repository
```bash
git clone https://github.com/CaiTzy/CD-Discount-Calculator-.git
cd CD-Discount-Calculator-
```

#### 2. Create Database
```bash
mysql -u root -p -e "CREATE DATABASE enrollment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p enrollment_system < improved_schema.sql
mysql -u root -p enrollment_system < complete_sample_data.sql
```

#### 3. Configure Database Connection
Edit `config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'enrollment_system');
```

#### 4. Set Permissions
```bash
mkdir uploads
chmod 755 uploads
```

</details>

### Access the System
- **Homepage**: `http://localhost/index.php`
- **Student Enrollment**: `http://localhost/enrollment_form.php`
- **Admin Login**: `http://localhost/login.php`

**Default Login:**
- Username: `admin`
- Password: `admin123`

**Additional Test Users:**
- `staff1` / `staff123` (Staff)
- `registrar` / `registrar123` (Registrar)
- `principal` / `principal123` (Principal)

## 📂 File Structure

```
├── config.php                  # Database & system configuration
├── index.php                   # Homepage/landing page
├── enrollment_form.php         # Student enrollment form
├── upload_documents.php        # Document upload interface
├── login.php                   # Admin authentication
├── admin_dashboard.php         # Admin control panel
├── view_application.php        # Application details view
├── logout.php                  # Logout handler
├── improved_schema.sql         # Enhanced database schema
├── complete_sample_data.sql    # ⭐ Complete sample data (NEW!)
├── setup.sh                    # ⭐ Automated setup for Linux/Mac (NEW!)
├── setup.bat                   # ⭐ Automated setup for Windows (NEW!)
├── QUICKSTART.md               # ⭐ Quick start guide (NEW!)
├── SCHEMA_IMPROVEMENTS.md      # Schema documentation
├── INSTALLATION_GUIDE.md       # Detailed installation guide
├── PROJECT_SUMMARY.md          # Complete project summary
├── README.md                   # This file
└── uploads/                    # Document storage directory
```

## 📊 Sample Data Overview

The `complete_sample_data.sql` file includes:

### System Users (6)
- **admin** / admin123 - System Administrator
- **staff1** / staff123 - Maria Santos (Staff)
- **staff2** / staff123 - Juan Dela Cruz (Staff)
- **registrar** / registrar123 - Ana Reyes (Registrar)
- **principal** / principal123 - Dr. Roberto Garcia (Principal)
- **teacher1** / teacher123 - Elena Cruz (Teacher)

### Student Applications (15)
Realistic applications with various statuses:
- ✅ **6 Enrolled** - Fully enrolled students
- ⏳ **3 Approved** - Approved, awaiting enrollment
- 🔍 **2 Under Review** - Being reviewed by staff
- 📝 **2 Pending** - Newly submitted
- ❌ **1 Rejected** - Did not meet requirements
- 🚫 **1 Withdrawn** - Student withdrew

### Academic Tracks (5)
- **STEM** - Science, Technology, Engineering, Mathematics (₱25,000)
- **ABM** - Accountancy, Business, Management (₱24,000)
- **HUMSS** - Humanities and Social Sciences (₱23,000)
- **GAS** - General Academic Strand (₱22,000)
- **TVL-ICT** - Technical-Vocational-Livelihood ICT (₱26,000)

### Document Records (15)
Complete document tracking with verification statuses:
- Birth certificates, diplomas, good moral certificates
- Report cards, 2x2 photos, transfer credentials
- Various verification statuses (Verified, Pending, Incomplete, Rejected)

### Enrollment History (34 records)
Full audit trail showing:
- All status changes with timestamps
- Who made each change
- Reasons for status updates
- Complete compliance history

## 🗄️ Database Schema

The system uses 5 main tables:

1. **Track** - Academic tracks/strands (STEM, ABM, HUMSS, etc.)
2. **Application** - Student enrollment applications
3. **Document** - Uploaded documents and verification status
4. **Users** - System users (admin, staff, etc.)
5. **Enrollment_History** - Audit trail of status changes

### Key Improvements Over Original Schema

| Feature | Original | Improved |
|---------|----------|----------|
| Tables | 3 | 5 (+Users, +History) |
| Indexes | 2 | 15+ |
| Constraints | 2 FK | 10+ (FK, CHECK, UNIQUE) |
| Validation | Minimal | Comprehensive |
| Audit Trail | None | Full history |
| Views | 0 | 2 |
| Procedures | 0 | 1 |
| Triggers | 0 | 1 |
| Application Fields | 9 | 25+ |
| Document Tracking | Basic | Full verification workflow |

See [SCHEMA_IMPROVEMENTS.md](SCHEMA_IMPROVEMENTS.md) for detailed documentation.

## 🔒 Security Features

- **Password Hashing** - Uses bcrypt via PHP's `password_hash()`
- **SQL Injection Protection** - Prepared statements throughout
- **XSS Prevention** - Input sanitization and output escaping
- **Session Management** - Secure session handling
- **File Upload Validation** - Type and size restrictions
- **Access Control** - Role-based permissions
- **Audit Logging** - Complete change history
- **Database Constraints** - Validation at DB level

## 📊 Screenshots

### Student Enrollment Form
Modern, user-friendly form with validation and real-time feedback.

### Admin Dashboard
Comprehensive overview with statistics, recent applications, and track enrollment data.

### Document Upload Interface
Easy document submission with progress tracking and verification status.

## 🛠️ Customization

### Adding New Tracks
```sql
INSERT INTO Track (strand_course, track_code, description, capacity, tuition_fee) 
VALUES ('New Track', 'NEW-01', 'Description', 40, 25000.00);
```

### Creating Additional Users
```sql
INSERT INTO Users (username, password_hash, email, full_name, role) 
VALUES ('staff1', PASSWORD('secure_password'), 'staff@school.edu', 'Staff Name', 'Staff');
```

### Changing Site Branding
Edit `config.php`:
```php
define('SITE_NAME', 'Your School Name');
```

## 📖 Documentation

- [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) - Detailed setup instructions
- [SCHEMA_IMPROVEMENTS.md](SCHEMA_IMPROVEMENTS.md) - Database schema documentation
- Inline code comments throughout all PHP files

## 🐛 Troubleshooting

### Database Connection Issues
- Verify credentials in `config.php`
- Ensure MySQL service is running
- Check if database exists

### File Upload Not Working
- Verify `uploads/` directory exists and is writable
- Check PHP upload settings (`upload_max_filesize`, `post_max_size`)

### Blank/White Page
- Enable error reporting in PHP
- Check Apache/PHP error logs
- Verify all files are uploaded correctly

See [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) for more troubleshooting tips.

## 🔄 Version History

**v1.0.0** (Current)
- Initial release
- Complete enrollment system with admin dashboard
- Improved database schema with validation
- Document upload and verification
- Comprehensive audit trail
- Multi-user support with roles
- Mobile-responsive design

## 📝 License

This project is provided as-is for educational purposes.

## 👥 Contributing

Contributions are welcome! Please feel free to submit issues or pull requests.

## 📧 Support

For questions or issues:
1. Check the [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)
2. Review the [SCHEMA_IMPROVEMENTS.md](SCHEMA_IMPROVEMENTS.md)
3. Create an issue on GitHub

## 🙏 Acknowledgments

- Bootstrap 5 for the UI framework
- Bootstrap Icons for icons
- MySQL for the database engine

---

**Made with ❤️ for modern education**
