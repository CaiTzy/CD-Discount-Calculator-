# 404 Error - Visual Guide

## Why You're Getting 404 Error

```
┌─────────────────────────────────────────────────────────────────┐
│  Browser tries to access:                                       │
│  http://localhost/enrollment/                                   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Apache looks in:                                               │
│  C:\xampp\htdocs\enrollment\                                    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                     ┌────────┴────────┐
                     │                 │
                ✅ Found            ❌ Not Found
                     │                 │
                     ▼                 ▼
              Shows website      404 ERROR!
```

---

## The Problem

```
❌ WRONG LOCATION:
   C:\Users\YourName\Downloads\enrollment\
   C:\Users\YourName\Desktop\enrollment\
   C:\Users\YourName\Documents\enrollment\
   
   ↓ Apache CANNOT find files here! ↓

✅ CORRECT LOCATION:
   C:\xampp\htdocs\enrollment\
   
   ↓ Apache CAN find files here! ↓
```

---

## The Solution - Copy Files

### Before (404 Error):

```
📁 C:\Users\YourName\Downloads\
   └── 📁 enrollment\
       ├── 📄 index.php
       ├── 📄 login.php
       └── 📄 ...

❌ Apache searches here but files are in Downloads
📁 C:\xampp\htdocs\
   └── (empty)
```

### After (Works!):

```
📁 C:\Users\YourName\Downloads\
   └── 📁 enrollment\  ← Keep original for backup
   
✅ Apache finds files here!
📁 C:\xampp\htdocs\
   └── 📁 enrollment\
       ├── 📄 index.php
       ├── 📄 login.php
       ├── 📄 enrollment_form.php
       ├── 📄 check_system.php
       └── 📄 ...
```

---

## File Structure You Need

```
C:\xampp\
├── 📁 htdocs\                    ← Apache web root
│   └── 📁 enrollment\             ← Your project folder
│       ├── 📄 .htaccess           ← Important for Apache!
│       ├── 📄 index.php           ← Entry point
│       ├── 📄 login.php
│       ├── 📄 enrollment_form.php
│       ├── 📄 student_list.php
│       ├── 📄 admin_dashboard.php
│       ├── 📄 db_connection.php
│       ├── 📄 logout.php
│       ├── 📄 schema.sql
│       ├── 📄 check_system.php    ← Run this to verify!
│       ├── 📄 README.md
│       ├── 📄 SETUP.md
│       ├── 📄 APACHE_SETUP.md
│       └── 📄 QUICK_FIX.md
│
└── 📁 mysql\                      ← MySQL data
    └── 📁 data\
        └── 📁 enrollment_system\  ← Your database
```

---

## URL Mapping

```
URL in Browser              →    File Location on Disk
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

http://localhost/           →    C:\xampp\htdocs\index.php

http://localhost/enrollment/  →  C:\xampp\htdocs\enrollment\index.php

http://localhost/enrollment/login.php
                            →    C:\xampp\htdocs\enrollment\login.php

http://localhost/enrollment/check_system.php
                            →    C:\xampp\htdocs\enrollment\check_system.php
```

---

## Step-by-Step Visual Guide

### Step 1: Install XAMPP
```
┌─────────────────────┐
│   Download XAMPP    │
│  from Apache.org    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│   Install XAMPP     │
│  C:\xampp\          │
└──────────┬──────────┘
           │
           ▼
      ✅ Installed
```

### Step 2: Copy Files
```
┌─────────────────────┐
│  Extract ZIP or     │
│  Download files     │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  Copy ALL files to  │
│  C:\xampp\htdocs\   │
│      enrollment\    │
└──────────┬──────────┘
           │
           ▼
      ✅ Files Copied
```

### Step 3: Start Services
```
┌─────────────────────┐
│   XAMPP Control     │
│      Panel          │
└──────────┬──────────┘
           │
           ├──► Start Apache  ──► 🟢 Running
           │
           └──► Start MySQL   ──► 🟢 Running
           
      ✅ Services Running
```

### Step 4: Create Database
```
┌─────────────────────┐
│  Open phpMyAdmin    │
│  localhost/phpmyadmin│
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  Create Database    │
│  enrollment_system  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  Import schema.sql  │
└──────────┬──────────┘
           │
           ▼
      ✅ Database Ready
```

### Step 5: Access Website
```
┌─────────────────────┐
│   Open Browser      │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│      Type URL:      │
│  localhost/         │
│    enrollment/      │
│  check_system.php   │
└──────────┬──────────┘
           │
           ▼
      ✅ All Green!
           │
           ▼
┌─────────────────────┐
│   Access Main App   │
│  localhost/         │
│    enrollment/      │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│   Login Page        │
│  admin / admin123   │
└─────────────────────┘
```

---

## Common Mistakes

### ❌ Mistake 1: Wrong URL
```
Typing: file:///C:/xampp/htdocs/enrollment/index.php
Should be: http://localhost/enrollment/
```

### ❌ Mistake 2: Files in Wrong Location
```
Files in: C:\Downloads\enrollment\
Should be: C:\xampp\htdocs\enrollment\
```

### ❌ Mistake 3: Apache Not Running
```
XAMPP Control: Apache shows ⭕ (red/stopped)
Should be: Apache shows 🟢 (green/running)
```

### ❌ Mistake 4: Wrong Port
```
Trying: http://localhost:8080/enrollment/
Should be: http://localhost/enrollment/ (port 80)
```

---

## Verification Flow

```
Run: http://localhost/enrollment/check_system.php

├─ ✅ PHP Version OK
├─ ✅ Extensions Loaded
├─ ✅ Files Found
├─ ✅ Database Connected
└─ ✅ All Systems Ready!
     │
     └─► Go to: http://localhost/enrollment/
          │
          └─► Should redirect to login page
               │
               └─► Login: admin / admin123
                    │
                    └─► 🎉 SUCCESS!
```

---

## Quick Reference Card

```
╔═══════════════════════════════════════════════════════════════╗
║                        QUICK REFERENCE                        ║
╠═══════════════════════════════════════════════════════════════╣
║ Files Location:  C:\xampp\htdocs\enrollment\                 ║
║ Main URL:        http://localhost/enrollment/                ║
║ System Check:    http://localhost/enrollment/check_system.php║
║ phpMyAdmin:      http://localhost/phpmyadmin                 ║
║ Database:        enrollment_system                           ║
║ Username:        admin                                       ║
║ Password:        admin123                                    ║
╚═══════════════════════════════════════════════════════════════╝
```

---

## Still Not Working?

```
1. Check XAMPP Control Panel
   → Apache: 🟢 Running?
   → MySQL: 🟢 Running?

2. Check File Location
   → Files in: C:\xampp\htdocs\enrollment\

3. Check URL
   → Using: http://localhost/enrollment/

4. Run System Check
   → Visit: http://localhost/enrollment/check_system.php
   → All checks green? ✅

5. Check Logs
   → Error Log: C:\xampp\apache\logs\error.log
   → Access Log: C:\xampp\apache\logs\access.log

6. Read Guides
   → QUICK_FIX.md
   → APACHE_SETUP.md
   → SETUP.md
```

---

**Remember:** 90% of 404 errors = files in wrong location!

**Solution:** Copy to `C:\xampp\htdocs\enrollment\` and use `http://localhost/enrollment/`
