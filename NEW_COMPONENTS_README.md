# New Enrollment System Components

This document describes the newly added simplified enrollment system components.

## 📦 New Files Added

### 1. `db_connection.php` - Database Connection
Simple mysqli database connection file that:
- Connects to MySQL database
- Provides `sanitize_input()` function for SQL injection protection
- Auto-configures utf8mb4 charset
- Includes connection error handling

**Configuration:**
```php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'enrollment_system';
```

**Usage:**
```php
require_once("db_connection.php");
// $conn is now available
$result = $conn->query("SELECT * FROM Track");
```

### 2. `header.php` - Reusable Header
Responsive navigation header with Bootstrap 5 that:
- Shows different menus for logged-in vs. logged-out users
- Displays user name and role badge when logged in
- Includes responsive mobile menu
- Links to all major pages (Home, Enroll, Dashboard, Student List, Login/Logout)

**Usage:**
```php
$page_title = "My Page Title";
require_once("header.php");
// Your page content here
require_once("footer.php");
```

**Features:**
- Fixed-top navigation bar
- User info dropdown with logout option
- Bootstrap Icons integration
- Mobile-responsive hamburger menu

### 3. `footer.php` - Reusable Footer
Simple footer component that:
- Displays copyright information
- Includes Bootstrap 5 JavaScript
- Maintains consistent site-wide footer

### 4. `student_list.php` - Student Management
Comprehensive student list page with:

**Features:**
- ✅ Display all student applications in a table
- ✅ Search by LRN, name, or email
- ✅ Filter by application status
- ✅ Pagination (15 students per page)
- ✅ Statistics cards showing counts for:
  - Total Students
  - Pending Applications
  - Enrolled Students
  - Approved Applications
- ✅ Status badges with color coding:
  - Pending (Yellow)
  - Under Review (Blue)
  - Approved (Green)
  - Enrolled (Primary)
  - Rejected (Red)
  - Withdrawn (Gray)
- ✅ View application button for each student
- ✅ Responsive table design

**Access:** Requires login (redirects to login.php if not authenticated)

**URL Parameters:**
- `search` - Search term
- `status` - Filter by status
- `page` - Page number for pagination

### 5. `enrollment_page.php` - Student Enrollment Form
Simplified enrollment form that:

**Features:**
- ✅ Clean, user-friendly form layout
- ✅ Auto-calculates age from birthdate
- ✅ Form validation (client & server-side)
- ✅ Success/error messages with color coding
- ✅ Duplicate LRN detection
- ✅ Track selection from database
- ✅ Creates document record automatically
- ✅ Responsive design

**Form Fields:**
- **Personal Information:**
  - LRN (Learner Reference Number) *
  - Birthdate *
  - First Name *
  - Last Name *
  - Address *
  - Gender *

- **Guardian Information:**
  - Guardian Name and Contact (format: Name - Number)

- **Academic Information:**
  - Track/Strand Selection *

**Form Submission:**
1. Validates all required fields
2. Calculates age from birthdate
3. Checks for duplicate LRN
4. Inserts into Application table with 'Pending' status
5. Creates document record
6. Shows success/error message

**Error Handling:**
- Duplicate LRN: "This LRN is already registered"
- Invalid Track: "Invalid Track ID"
- Missing fields: "Please fill in all required fields"
- Database errors: Shows specific error message

## 🎨 UI Design

All pages use:
- **Bootstrap 5** for responsive design
- **Bootstrap Icons** for visual elements
- **Gradient backgrounds** for modern look
- **Card-based layouts** for content organization
- **Color-coded badges** for status indicators

### Color Scheme:
- Primary: Blue (#0d6efd)
- Success: Green (#198754)
- Warning: Yellow (#ffc107)
- Danger: Red (#dc3545)
- Info: Light Blue (#0dcaf0)

## 🔐 Authentication Flow

### For Guests (Not Logged In):
- Can access: Home, Enrollment Form
- Navigation shows: Home, Enroll Now, Login

### For Logged In Users:
- Can access: Dashboard, Student List, New Enrollment
- Navigation shows: Dashboard, Student List, New Enrollment, User dropdown with Logout

## 📊 Database Schema Used

The system uses three main tables:

### Track Table
```sql
- track_id (PK)
- strand_course
- track_code
- is_active
```

### Application Table
```sql
- lrn (PK)
- first_name
- last_name
- address
- age
- birthdate
- gender
- guardian_name
- guardian_contact
- track_id (FK)
- application_status
- application_date
```

### Document Table
```sql
- document_id (PK)
- lrn (FK)
- birth_certificate_url
- diploma_url
- good_moral_url
- report_card_url
```

## 🚀 Setup Instructions

1. **Import Database:**
   ```bash
   mysql -u root -p enrollment_system < improved_schema.sql
   mysql -u root -p enrollment_system < complete_sample_data.sql
   ```

2. **Configure Database Connection:**
   Edit `db_connection.php`:
   ```php
   $db_host = 'localhost';
   $db_user = 'root';
   $db_pass = 'your_password';
   $db_name = 'enrollment_system';
   ```

3. **Access the System:**
   - Enrollment Form: `http://localhost/enrollment_page.php`
   - Student List: `http://localhost/student_list.php` (requires login)
   - Login: `http://localhost/login.php`

## 📸 Screenshots

### Enrollment Page
![Enrollment Form](screenshots/enrollment_page.png)
- Clean form layout
- Required field indicators (*)
- Success/error messages
- Responsive design

### Student List
![Student List](screenshots/student_list.png)
- Searchable table
- Filter by status
- Pagination
- Statistics cards
- Action buttons

### Navigation Header
![Header](screenshots/header.png)
- User info display
- Role badge
- Dropdown menu
- Responsive design

## 🧪 Testing

### Test Enrollment:
1. Go to `enrollment_page.php`
2. Fill in the form:
   - LRN: 999888777666555
   - First Name: Test
   - Last Name: Student
   - Birthdate: 2008-01-01
   - Address: Test Address
   - Gender: Male
   - Guardian: Test Parent - 09171234567
   - Track: Select any available track
3. Submit
4. Should see success message

### Test Student List:
1. Login first (admin/admin123)
2. Go to `student_list.php`
3. Should see all enrolled students
4. Try search function
5. Try status filter
6. Try pagination

### Test Search:
1. In student list, enter LRN or name in search
2. Select status filter
3. Click Search
4. Results should update

## 🔧 Customization

### Change Colors:
Edit the `<style>` section in `header.php`:
```css
.navbar-brand {
    color: #667eea !important; /* Change brand color */
}
```

### Change Pagination:
Edit `student_list.php`:
```php
$records_per_page = 20; // Change from 15 to 20
```

### Add New Fields:
1. Update form in `enrollment_page.php`
2. Update INSERT query
3. Update database schema

## 📋 Code Quality

All files include:
- ✅ SQL injection protection (prepared statements)
- ✅ XSS prevention (htmlspecialchars)
- ✅ Input validation
- ✅ Error handling
- ✅ Responsive design
- ✅ Clean, commented code
- ✅ Bootstrap 5 best practices

## 🎯 Key Features

1. **Simplified Architecture**
   - No complex OOP classes
   - Direct mysqli usage
   - Easy to understand and modify

2. **User-Friendly**
   - Clean interfaces
   - Clear error messages
   - Intuitive navigation

3. **Secure**
   - Prepared statements
   - Input sanitization
   - Session management

4. **Responsive**
   - Works on mobile, tablet, desktop
   - Bootstrap 5 grid system
   - Mobile-first design

## 📝 Notes

- All files follow PHP best practices
- Session management integrated
- Compatible with existing system
- Can coexist with advanced features (config.php, admin_dashboard.php, etc.)
- Uses the same database schema as the complete system

## 🆘 Troubleshooting

### "Database connection error"
- Check MySQL is running
- Verify credentials in db_connection.php
- Ensure database 'enrollment_system' exists

### "This LRN is already registered"
- LRN must be unique
- Check existing records in database
- Use a different LRN

### "Please fill in all required fields"
- All fields marked with * are required
- Ensure track is selected
- Birthdate must be valid

### Page not loading
- Check PHP errors in browser console
- Verify file permissions
- Ensure all files are in same directory

## 🎓 Educational Value

These components are designed to be:
- **Learning-friendly** - Simple code, clear structure
- **Well-commented** - Easy to understand
- **Modifiable** - Easy to customize and extend
- **Production-ready** - Secure and tested

Perfect for:
- Educational projects
- Learning PHP/MySQL
- Understanding MVC basics
- Building on top of existing code

---

**Created by:** GitHub Copilot Agent
**Date:** February 21, 2026
**Version:** 1.0
