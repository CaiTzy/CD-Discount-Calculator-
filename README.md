# Smart Online Enrollment System

A complete web-based enrollment management system built with PHP and MySQL, featuring an improved database schema with comprehensive validation, security, and audit capabilities.

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

### 1. Clone/Download the Repository
```bash
git clone https://github.com/CaiTzy/CD-Discount-Calculator-.git
cd CD-Discount-Calculator-
```

### 2. Create Database
```bash
mysql -u root -p -e "CREATE DATABASE enrollment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p enrollment_system < improved_schema.sql
```

### 3. Configure Database Connection
Edit `config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'enrollment_system');
```

### 4. Create Admin User
```sql
INSERT INTO Users (username, password_hash, email, full_name, role, is_active) 
VALUES (
    'admin', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin@school.edu', 
    'System Administrator', 
    'Admin', 
    1
);
```

### 5. Set Permissions
```bash
mkdir uploads
chmod 755 uploads
```

### 6. Access the System
- **Homepage**: `http://localhost/index.php`
- **Student Enrollment**: `http://localhost/enrollment_form.php`
- **Admin Login**: `http://localhost/login.php`
  - Username: `admin`
  - Password: `admin123`

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
├── SCHEMA_IMPROVEMENTS.md      # Schema documentation
├── INSTALLATION_GUIDE.md       # Detailed installation guide
├── README.md                   # This file
└── uploads/                    # Document storage directory
```

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
