# Smart Online Enrollment System

A comprehensive web-based enrollment management system built with PHP and MySQL. This system streamlines the student enrollment process with modern features including document management, application tracking, and administrative dashboards.

## Features

### Core Functionality
- ✅ **Student Enrollment** - Complete enrollment form with auto-age calculation
- ✅ **Document Management** - Upload and track student documents (birth certificate, diploma, good moral, report card)
- ✅ **Application Tracking** - Track application status through the enrollment workflow
- ✅ **Advanced Search & Filter** - Search by LRN/name, filter by status/track with pagination
- ✅ **User Authentication** - Secure login system with role-based access control
- ✅ **Admin Dashboard** - Comprehensive statistics and management tools
- ✅ **Enrollment History** - Complete audit trail of all application changes

### Improved Features (Based on Requirements)
- 🎯 **Auto-Age Calculation** - Age is automatically calculated from birthdate
- 🎯 **Duplicate Detection** - Prevents duplicate LRN entries
- 🎯 **Input Validation** - Client and server-side validation
- 🎯 **Responsive Design** - Bootstrap 5 UI works on all devices
- 🎯 **Database Triggers** - Auto-update age on insert/update
- 🎯 **Stored Procedures** - Efficient status updates with history logging
- 🎯 **Views** - Simplified data querying with vw_student_overview

## Database Schema

The system uses 5 main tables:
1. **Track** - Educational tracks/strands (STEM, ABM, HUMSS, GAS, TVL)
2. **Application** - Student enrollment applications
3. **Document** - Student document uploads
4. **Users** - System users with role-based access
5. **Enrollment_History** - Complete audit trail

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Web browser

### Quick Setup

1. **Clone or Download the Repository**
   ```bash
   cd /path/to/webserver/htdocs  # or public_html
   ```

2. **Create Database**
   ```bash
   mysql -u root -p
   ```
   Then run:
   ```sql
   CREATE DATABASE enrollment_system;
   exit;
   ```

3. **Import Database Schema**
   ```bash
   mysql -u root -p enrollment_system < schema.sql
   ```

4. **Configure Database Connection**
   Edit `config.php` and update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'enrollment_system');
   ```

5. **Set Permissions**
   ```bash
   chmod 755 uploads/
   ```

6. **Access the System**
   Open your web browser and navigate to:
   ```
   http://localhost/index.php
   ```

## Default Credentials

- **Username:** admin
- **Password:** admin123

**Important:** Change the default password after first login!

## System Structure

```
Smart Online Enrollment System/
├── config.php              # Configuration settings
├── db_connection.php       # Database connection & helper functions
├── schema.sql             # Database schema with sample data
├── header.php             # Common header with navigation
├── footer.php             # Common footer
├── login.php              # User authentication
├── logout.php             # User logout
├── index.php              # Homepage
├── enrollment_form.php    # New student enrollment
├── student_list.php       # Student list with search/filter
├── view_application.php   # View student details
├── upload_documents.php   # Document upload interface
├── admin_dashboard.php    # Admin dashboard
└── uploads/               # Document storage directory
```

## Usage Guide

### For Staff/Registrar

1. **Login** with your credentials
2. **Enroll New Student**
   - Click "New Application" from navigation
   - Fill in student details (age auto-calculates from birthdate)
   - Select track/strand
   - Submit application

3. **Search Students**
   - Use search bar to find by LRN or name
   - Filter by status or track
   - Navigate through pages

4. **Upload Documents**
   - Enter student LRN
   - Upload required documents
   - Track upload status

5. **View Application Details**
   - Click "View" icon on any student
   - See complete student information
   - View document status
   - Check enrollment history

### For Administrators

1. **Access Admin Dashboard**
   - Click "Dashboard" in navigation
   - View comprehensive statistics
   - Monitor track performance
   - Track revenue

2. **Update Application Status**
   - Open student application
   - Select new status from dropdown
   - Add optional notes
   - Submit update (automatically logged in history)

## Database Improvements

### Original Schema (from requirements)
```sql
- Track (track_id, strand_course, previous_school_records, student_photo_url)
- Application (lrn, first_name, last_name, address, age, birthdate, gender, guardian_name_contact, track_id)
- Document (document_id, lrn, birth_certificate_url, diploma_url, good_moral_url, report_card_url)
```

### Enhanced Schema (implemented)
**Added Features:**
- ✅ Status tracking (Pending, Under Review, Approved, Rejected, Enrolled, Withdrawn)
- ✅ Timestamps (created_at, updated_at, application_date)
- ✅ Tuition fee tracking
- ✅ Users table for authentication
- ✅ Enrollment_History for audit trail
- ✅ Indexes for better performance
- ✅ Database triggers for auto-age calculation
- ✅ Stored procedures for status updates
- ✅ Views for simplified querying

## PHP Code Improvements

### Original PHP Code (from requirements)
```php
// Basic SQL query with no pagination or filtering
$sql = "SELECT s.student_id, s.firstname, s.lastname, s.grade_level,
        COUNT(e.subject_id) AS total_subjects
        FROM students s LEFT JOIN enrollments e ...";
```

### Enhanced PHP Implementation
**Added Features:**
- ✅ Prepared statements (SQL injection prevention)
- ✅ Search functionality
- ✅ Status and track filtering
- ✅ Pagination with page navigation
- ✅ Responsive Bootstrap 5 UI
- ✅ Auto-age calculation via JavaScript
- ✅ File upload validation
- ✅ Session management
- ✅ Role-based access control
- ✅ Error handling

## Security Features

- ✅ Password hashing with bcrypt
- ✅ Prepared statements (SQL injection prevention)
- ✅ Session management
- ✅ File upload validation
- ✅ XSS protection with htmlspecialchars()
- ✅ Role-based access control
- ✅ HTTP-only cookies

## Technologies Used

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript
- **UI Framework:** Bootstrap 5.1.3
- **Icons:** Bootstrap Icons 1.8.1

## Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)

## Troubleshooting

### Database Connection Error
- Check database credentials in `config.php`
- Ensure MySQL service is running
- Verify database exists

### Upload Directory Error
- Ensure `uploads/` directory exists
- Check directory permissions (755)

### Session Issues
- Clear browser cookies
- Check PHP session configuration

## License

This project is open source and available for educational purposes.

## Support

For issues or questions, please contact the system administrator.

---

**Version:** 1.0.0  
**Last Updated:** February 2026
