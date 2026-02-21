# System Architecture - New Enrollment Components

## 📐 Component Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    ENROLLMENT SYSTEM                             │
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │                    HEADER.PHP                          │    │
│  │  • Navigation Bar                                      │    │
│  │  • User Info Display                                   │    │
│  │  • Login/Logout Links                                  │    │
│  └────────────────────────────────────────────────────────┘    │
│                          ↓                                       │
│  ┌────────────────────────────────────────────────────────┐    │
│  │              DB_CONNECTION.PHP                         │    │
│  │  • MySQL Connection                                    │    │
│  │  • sanitize_input() function                          │    │
│  │  • Error Handling                                      │    │
│  └────────────────────────────────────────────────────────┘    │
│                          ↓                                       │
│  ┌──────────────────┬───────────────────┬──────────────────┐   │
│  │                  │                   │                  │   │
│  │ ENROLLMENT_PAGE  │   STUDENT_LIST    │   LOGIN.PHP      │   │
│  │                  │                   │                  │   │
│  │ • Form Display   │ • List Students   │ • Authentication │   │
│  │ • Validation     │ • Search/Filter   │ • Session Start  │   │
│  │ • Submit Data    │ • Pagination      │ • User Verify    │   │
│  │ • Error Msgs     │ • Statistics      │ • Redirect       │   │
│  │                  │                   │                  │   │
│  └──────────────────┴───────────────────┴──────────────────┘   │
│                          ↓                                       │
│  ┌────────────────────────────────────────────────────────┐    │
│  │                    FOOTER.PHP                          │    │
│  │  • Copyright Info                                      │    │
│  │  • Bootstrap JS                                        │    │
│  └────────────────────────────────────────────────────────┘    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
                          ↓
        ┌────────────────────────────────────┐
        │      DATABASE (MySQL)              │
        │                                    │
        │  ┌──────────┐  ┌──────────────┐  │
        │  │  Track   │  │ Application  │  │
        │  │  Table   │  │   Table      │  │
        │  └──────────┘  └──────────────┘  │
        │                                    │
        │  ┌──────────┐  ┌──────────────┐  │
        │  │ Document │  │    Users     │  │
        │  │  Table   │  │   Table      │  │
        │  └──────────┘  └──────────────┘  │
        └────────────────────────────────────┘
```

## 🔄 Data Flow

### Enrollment Process Flow:

```
┌─────────────┐
│   Student   │
└──────┬──────┘
       │
       ↓
┌────────────────────────┐
│ enrollment_page.php    │
│ • Displays Form        │
└──────┬─────────────────┘
       │
       ↓ [Submit Form]
┌────────────────────────┐
│ Form Validation        │
│ • Check Required       │
│ • Calculate Age        │
└──────┬─────────────────┘
       │
       ↓
┌────────────────────────┐
│ db_connection.php      │
│ • Connect to DB        │
│ • Sanitize Input       │
└──────┬─────────────────┘
       │
       ↓
┌────────────────────────┐
│ Check Duplicate LRN    │
│ SELECT FROM Application│
└──────┬─────────────────┘
       │
       ├──→ [Exists] ──→ Error Message
       │
       ↓ [Not Exists]
┌────────────────────────┐
│ Insert Application     │
│ • INSERT INTO          │
│ • Status: Pending      │
└──────┬─────────────────┘
       │
       ↓
┌────────────────────────┐
│ Create Document Record │
│ INSERT INTO Document   │
└──────┬─────────────────┘
       │
       ↓
┌────────────────────────┐
│ Success Message        │
│ "Application Submitted"│
└────────────────────────┘
```

### Student List View Flow:

```
┌─────────────┐
│    Admin    │
└──────┬──────┘
       │
       ↓ [Login Required]
┌────────────────────────┐
│   login.php            │
│ • Check Credentials    │
│ • Start Session        │
└──────┬─────────────────┘
       │
       ↓ [Authenticated]
┌────────────────────────┐
│ student_list.php       │
│ • Load Header          │
└──────┬─────────────────┘
       │
       ↓
┌────────────────────────┐
│ Get Query Parameters   │
│ • Search Term          │
│ • Status Filter        │
│ • Page Number          │
└──────┬─────────────────┘
       │
       ↓
┌────────────────────────┐
│ Build SQL Query        │
│ • WHERE Conditions     │
│ • LIMIT/OFFSET         │
└──────┬─────────────────┘
       │
       ↓
┌────────────────────────┐
│ Fetch from Database    │
│ SELECT Application     │
│ JOIN Track             │
└──────┬─────────────────┘
       │
       ↓
┌────────────────────────┐
│ Display Table          │
│ • Student Rows         │
│ • Pagination           │
│ • Statistics Cards     │
└────────────────────────┘
```

## 🗂️ File Dependencies

### enrollment_page.php
```
├── db_connection.php (Required)
├── header.php (Required)
├── footer.php (Required)
└── Database Tables:
    ├── Track (Read)
    ├── Application (Insert)
    └── Document (Insert)
```

### student_list.php
```
├── db_connection.php (Required)
├── header.php (Required)
├── footer.php (Required)
├── Session (Required - must be logged in)
└── Database Tables:
    ├── Application (Read)
    └── Track (Read)
```

### header.php
```
├── Session (Auto-start if not started)
└── Bootstrap 5 CSS (CDN)
```

### footer.php
```
└── Bootstrap 5 JS (CDN)
```

### db_connection.php
```
├── mysqli extension (Required)
└── MySQL Database (Required)
```

## 🎯 User Interactions

### Guest User Journey:
```
┌─────────┐     ┌──────────────┐     ┌─────────────────┐
│  Home   │ ──→ │ Enroll Now   │ ──→ │ Submit Form     │
│ Page    │     │ (enrollment_ │     │ Success!        │
│         │     │  page.php)   │     │ LRN: XXXXXX     │
└─────────┘     └──────────────┘     └─────────────────┘
                        ↓
                   [Or Login]
                        ↓
                ┌──────────────┐
                │  login.php   │
                └──────────────┘
```

### Logged-In User Journey:
```
┌─────────┐     ┌──────────────┐     ┌─────────────────┐
│ Login   │ ──→ │  Dashboard   │ ──→ │ Student List    │
└─────────┘     └──────────────┘     │ (student_list   │
                        │             │  .php)          │
                        │             └─────────────────┘
                        ↓                      ↓
                ┌──────────────┐     ┌─────────────────┐
                │ New Enroll   │     │ View Student    │
                │ (enrollment_ │     │ Details         │
                │  page.php)   │     │ (view_app.php)  │
                └──────────────┘     └─────────────────┘
```

## 🔒 Security Features

### Input Sanitization
```
User Input
    ↓
sanitize_input()
    ↓ • trim()
    ↓ • stripslashes()
    ↓ • htmlspecialchars()
    ↓ • real_escape_string()
    ↓
Clean Data → Database
```

### SQL Injection Prevention
```
User Input → Prepared Statement → Bind Parameters → Execute
    ↓              ↓                    ↓              ↓
  "123"      $stmt->prepare()    bind_param()    execute()
                                                      ↓
                                              Safe Query
```

### Session Management
```
Login
  ↓
session_start()
  ↓
$_SESSION['user_id'] = ...
$_SESSION['role'] = ...
  ↓
Page Access Check
  ↓
if (!isset($_SESSION['user_id'])) redirect to login
```

## 📊 Database Relationships

```
┌──────────┐         ┌──────────────┐         ┌──────────┐
│  Track   │         │ Application  │         │ Document │
│          │         │              │         │          │
│ track_id │◄────────│ track_id (FK)│         │          │
│          │  1:N    │ lrn (PK)     │────────►│ lrn (FK) │
│          │         │              │  1:1    │          │
└──────────┘         └──────────────┘         └──────────┘
                            │
                            │ 1:N
                            ↓
                    ┌─────────────────┐
                    │ Enrollment_     │
                    │ History         │
                    │ lrn (FK)        │
                    └─────────────────┘
```

## 🎨 UI Component Hierarchy

```
<html>
  <head>
    ├── Bootstrap 5 CSS
    └── Bootstrap Icons
  </head>
  <body>
    ├── <nav> Navigation Bar
    │   ├── Brand Logo
    │   ├── Menu Items
    │   └── User Dropdown
    │
    ├── <div class="main-content">
    │   ├── Container
    │   ├── Cards
    │   ├── Forms/Tables
    │   └── Alerts
    │
    └── <footer> Copyright
        └── Bootstrap 5 JS
  </body>
</html>
```

## 🚦 Status Flow

```
New Application
      ↓
   [Pending] ──→ [Under Review] ──→ [Approved] ──→ [Enrolled]
      │              │                 │
      └──────────────┴─────────────────┴──→ [Rejected]
                     │
                     └──→ [Withdrawn]
```

---

This architecture provides a clean, maintainable, and scalable enrollment system with clear separation of concerns and proper data flow.
