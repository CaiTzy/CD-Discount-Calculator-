# 🚨 QUICK FIX: 404 Not Found Error

## Problem
You see: **"Not Found - The requested URL was not found on this server."**

---

## ✅ Solution (5 Steps)

### Step 1: Check File Location

**XAMPP Users (Windows):**
```
Your files MUST be in: C:\xampp\htdocs\enrollment\

NOT in: C:\Users\YourName\Downloads\
NOT in: C:\xampp\
NOT in: Desktop or Documents
```

**Copy ALL project files to that folder!**

---

### Step 2: Start Apache & MySQL

1. Open **XAMPP Control Panel**
2. Click **Start** next to Apache (should turn green)
3. Click **Start** next to MySQL (should turn green)

---

### Step 3: Import Database

1. Open: `http://localhost/phpmyadmin`
2. Click **"New"** on left side
3. Create database: `enrollment_system`
4. Click **Import** tab
5. Choose file: `schema.sql`
6. Click **Go**

---

### Step 4: Use Correct URL

**TRY THIS URL:**
```
http://localhost/enrollment/check_system.php
```

This will show you what's wrong!

**Then access:**
```
http://localhost/enrollment/
```

**NOT these:**
- ❌ `http://localhost/` (missing /enrollment/)
- ❌ `file:///C:/xampp/...` (wrong protocol)
- ❌ `C:\xampp\htdocs\...` (not a URL)

---

### Step 5: Login

**Default credentials:**
- Username: `admin`
- Password: `admin123`

---

## 🔍 Still Not Working?

### Check Apache Port
Maybe another program is using port 80:
1. Close Skype, IIS, or other web servers
2. Restart Apache in XAMPP
3. Try: `http://localhost/` - Should show XAMPP page

### Check Error Logs
**Apache Error Log:**
```
C:\xampp\apache\logs\error.log
```
**Apache Access Log:**
```
C:\xampp\apache\logs\access.log
```

### Run System Check
Visit: `http://localhost/enrollment/check_system.php`

This page will tell you exactly what's wrong!

---

## 📁 Correct Folder Structure

Your folder should look like this:

```
C:\xampp\htdocs\enrollment\
├── .htaccess              ← Important!
├── index.php
├── login.php
├── enrollment_form.php
├── student_list.php
├── admin_dashboard.php
├── db_connection.php
├── logout.php
├── schema.sql
├── check_system.php       ← Run this first!
└── APACHE_SETUP.md        ← Read this for help
```

---

## 🆘 Emergency Checklist

- [ ] Files in `C:\xampp\htdocs\enrollment\` folder
- [ ] Apache is running (green in XAMPP)
- [ ] MySQL is running (green in XAMPP)
- [ ] Database `enrollment_system` exists
- [ ] Used URL: `http://localhost/enrollment/`
- [ ] Not using file:// or C:\ paths
- [ ] .htaccess file is present
- [ ] check_system.php shows all green ✅

---

## 📞 More Help

1. **Detailed Guide:** See `APACHE_SETUP.md`
2. **Installation:** See `SETUP.md`
3. **Features:** See `README.md`

---

## 💡 Pro Tip

**Always access through browser as:**
```
http://localhost/enrollment/check_system.php
```

**This will diagnose all issues automatically!**

---

**90% of 404 errors = wrong file location!**

**Make sure files are in:**
```
C:\xampp\htdocs\enrollment\
```

**Then use URL:**
```
http://localhost/enrollment/
```

🎉 **That's it! Your system should work now!**
