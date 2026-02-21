# Complete Data Verification Guide

This document helps you verify that all sample data has been successfully imported and is error-free.

## 📊 Expected Record Counts

After importing both `improved_schema.sql` and `complete_sample_data.sql`, you should have:

| Table | Records | Description |
|-------|---------|-------------|
| **Users** | 6 | System users with different roles |
| **Track** | 5 | Academic tracks/strands |
| **Application** | 15 | Student enrollment applications |
| **Document** | 15 | Document records for each student |
| **Enrollment_History** | 34+ | Status change history records |

## ✅ Verification Queries

Run these SQL queries to verify your data:

### 1. Check All Table Counts
```sql
USE enrollment_system;

SELECT 'Users' as Table_Name, COUNT(*) as Record_Count FROM Users
UNION ALL
SELECT 'Tracks', COUNT(*) FROM Track
UNION ALL
SELECT 'Applications', COUNT(*) FROM Application
UNION ALL
SELECT 'Documents', COUNT(*) FROM Document
UNION ALL
SELECT 'Enrollment History', COUNT(*) FROM Enrollment_History;
```

**Expected Output:**
```
+--------------------+--------------+
| Table_Name         | Record_Count |
+--------------------+--------------+
| Users              |            6 |
| Tracks             |            5 |
| Applications       |           15 |
| Documents          |           15 |
| Enrollment History |           34 |
+--------------------+--------------+
```

### 2. Verify User Roles
```sql
SELECT role, COUNT(*) as count, GROUP_CONCAT(username) as users
FROM Users
GROUP BY role
ORDER BY count DESC;
```

**Expected Output:**
```
+-----------+-------+----------------------------+
| role      | count | users                      |
+-----------+-------+----------------------------+
| Staff     |     2 | staff1,staff2              |
| Admin     |     1 | admin                      |
| Registrar |     1 | registrar                  |
| Principal |     1 | principal                  |
| Teacher   |     1 | teacher1                   |
+-----------+-------+----------------------------+
```

### 3. Check Application Status Distribution
```sql
SELECT application_status, COUNT(*) as count
FROM Application
GROUP BY application_status
ORDER BY count DESC;
```

**Expected Output:**
```
+--------------------+-------+
| application_status | count |
+--------------------+-------+
| Enrolled           |     6 |
| Approved           |     3 |
| Under Review       |     2 |
| Pending            |     2 |
| Rejected           |     1 |
| Withdrawn          |     1 |
+--------------------+-------+
```

### 4. Verify Track Enrollment
```sql
SELECT 
    t.track_code,
    t.strand_course,
    COUNT(a.lrn) as enrolled,
    t.capacity,
    CONCAT(ROUND((COUNT(a.lrn) / t.capacity * 100), 1), '%') as filled
FROM Track t
LEFT JOIN Application a ON t.track_id = a.track_id AND a.application_status = 'Enrolled'
GROUP BY t.track_id
ORDER BY t.track_code;
```

**Expected Output:**
```
+------------+---------------------------------------------+----------+----------+-------+
| track_code | strand_course                               | enrolled | capacity | filled|
+------------+---------------------------------------------+----------+----------+-------+
| ABM-01     | ABM - Accountancy, Business and Management  |        2 |       45 | 4.4%  |
| GAS-01     | GAS - General Academic Strand               |        0 |       40 | 0.0%  |
| HUMSS-01   | HUMSS - Humanities and Social Sciences      |        0 |       40 | 0.0%  |
| STEM-01    | STEM - Science, Technology, Engineering...  |        3 |       50 | 6.0%  |
| TVL-ICT-01 | TVL-ICT - Technical-Vocational-Livelihood...  |      2 |       35 | 5.7%  |
+------------+---------------------------------------------+----------+----------+-------+
```

### 5. Check Document Verification Status
```sql
SELECT verification_status, COUNT(*) as count
FROM Document
GROUP BY verification_status
ORDER BY count DESC;
```

**Expected Output:**
```
+---------------------+-------+
| verification_status | count |
+---------------------+-------+
| Verified            |     8 |
| Pending             |     6 |
| Incomplete          |     1 |
+---------------------+-------+
```

### 6. Verify Enrollment History Exists
```sql
SELECT 
    COUNT(DISTINCT lrn) as students_with_history,
    COUNT(*) as total_history_records,
    MIN(changed_at) as earliest_change,
    MAX(changed_at) as latest_change
FROM Enrollment_History;
```

**Expected Output:**
```
+----------------------+------------------------+---------------------+---------------------+
| students_with_history| total_history_records  | earliest_change     | latest_change       |
+----------------------+------------------------+---------------------+---------------------+
|                   10 |                     34 | 2026-01-10 08:00:00 | 2026-02-18 08:00:00 |
+----------------------+------------------------+---------------------+---------------------+
```

## 🔍 Detailed Data Checks

### Sample Students by Track

Run this to see students in each track:

```sql
SELECT 
    t.track_code,
    a.lrn,
    CONCAT(a.first_name, ' ', a.last_name) as student_name,
    a.application_status
FROM Track t
LEFT JOIN Application a ON t.track_id = a.track_id
ORDER BY t.track_code, a.application_status;
```

### Students with Complete Documents

```sql
SELECT 
    a.lrn,
    CONCAT(a.first_name, ' ', a.last_name) as student_name,
    d.verification_status,
    CASE 
        WHEN d.birth_certificate_url IS NOT NULL 
         AND d.diploma_url IS NOT NULL
         AND d.good_moral_url IS NOT NULL
         AND d.report_card_url IS NOT NULL
         AND d.photo_2x2_url IS NOT NULL
        THEN 'Complete'
        ELSE 'Incomplete'
    END as document_completeness
FROM Application a
LEFT JOIN Document d ON a.lrn = d.lrn
ORDER BY document_completeness DESC, a.last_name;
```

## 🐛 Common Issues and Solutions

### Issue 1: No Records in Tables
**Symptom:** Tables exist but have 0 records

**Solution:**
```sql
-- Check if you imported both files
USE enrollment_system;
SHOW TABLES;

-- Re-import sample data
SOURCE complete_sample_data.sql;
```

### Issue 2: Duplicate Key Errors
**Symptom:** Error 1062: Duplicate entry

**Solution:**
```sql
-- Clear existing data first
USE enrollment_system;
DELETE FROM Enrollment_History;
DELETE FROM Document;
DELETE FROM Application;
DELETE FROM Users WHERE username != 'admin';

-- Then re-import
SOURCE complete_sample_data.sql;
```

### Issue 3: Foreign Key Constraint Fails
**Symptom:** Error 1452: Cannot add or update a child row

**Solution:**
```sql
-- Ensure Tracks are imported first
USE enrollment_system;
SELECT COUNT(*) FROM Track;  -- Should be 5

-- If Tracks are missing, re-import schema
SOURCE improved_schema.sql;
```

### Issue 4: Character Encoding Issues
**Symptom:** Strange characters in Filipino names

**Solution:**
```sql
-- Check database charset
SHOW CREATE DATABASE enrollment_system;

-- Should show: CHARACTER SET utf8mb4

-- If not, recreate database
DROP DATABASE enrollment_system;
CREATE DATABASE enrollment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SOURCE improved_schema.sql;
SOURCE complete_sample_data.sql;
```

## ✅ Data Quality Checks

### Check for NULL Required Fields
```sql
-- Applications with missing required data
SELECT lrn, first_name, last_name, 
       CASE 
           WHEN email IS NULL THEN 'Missing Email'
           WHEN birthdate IS NULL THEN 'Missing Birthdate'
           WHEN track_id IS NULL THEN 'Missing Track'
           ELSE 'OK'
       END as issue
FROM Application
HAVING issue != 'OK';
```
**Expected:** No results (all required fields should be filled)

### Check for Invalid Data Ranges
```sql
-- Check age is reasonable
SELECT lrn, first_name, last_name, age
FROM Application
WHERE age < 5 OR age > 100;
```
**Expected:** No results

```sql
-- Check GWA is in valid range (1.00 to 5.00)
SELECT lrn, first_name, last_name, gwa
FROM Application
WHERE gwa < 1.00 OR gwa > 5.00;
```
**Expected:** No results

### Check Foreign Key Integrity
```sql
-- All applications should have valid track_id
SELECT a.lrn, a.track_id
FROM Application a
LEFT JOIN Track t ON a.track_id = t.track_id
WHERE t.track_id IS NULL;
```
**Expected:** No results

```sql
-- All documents should have valid lrn
SELECT d.lrn
FROM Document d
LEFT JOIN Application a ON d.lrn = a.lrn
WHERE a.lrn IS NULL;
```
**Expected:** No results

## 🎯 Test Scenarios

### Scenario 1: Login with Each User
Test each user account:
```
admin/admin123 ✓
staff1/staff123 ✓
staff2/staff123 ✓
registrar/registrar123 ✓
principal/principal123 ✓
teacher1/teacher123 ✓
```

### Scenario 2: View Applications by Status
In the admin dashboard, you should be able to filter and see:
- 6 Enrolled students
- 3 Approved applications
- 2 Under Review
- 2 Pending
- 1 Rejected
- 1 Withdrawn

### Scenario 3: Document Verification
Check document verification workflow:
- 8 applications with verified documents
- 6 with pending verification
- 1 with incomplete documents
- 0 with rejected documents (except the rejected application)

## 📈 Performance Checks

### Check Index Usage
```sql
-- Verify indexes are created
SHOW INDEX FROM Application;
SHOW INDEX FROM Track;
SHOW INDEX FROM Document;
```

### Test Query Performance
```sql
-- This should be fast due to indexes
EXPLAIN SELECT * FROM Application WHERE lrn = '135790246801234';
EXPLAIN SELECT * FROM Application WHERE application_status = 'Enrolled';
EXPLAIN SELECT * FROM Application WHERE track_id = 1;
```

## ✨ Success Criteria

Your data import is successful if:

- ✅ All 6 tables exist
- ✅ Record counts match expected values
- ✅ No NULL values in required fields
- ✅ All foreign key relationships are valid
- ✅ All 6 user accounts can log in
- ✅ Application statuses are distributed correctly
- ✅ Document records exist for all students
- ✅ Enrollment history is populated
- ✅ No SQL errors when querying data
- ✅ Admin dashboard displays statistics correctly

## 🎉 Next Steps

After verification:

1. **Test the UI**
   - Login to admin dashboard
   - View each application
   - Test document upload
   - Update application statuses

2. **Test Student Portal**
   - Submit a new enrollment
   - Upload documents
   - Check if it appears in admin dashboard

3. **Test Reports**
   - View track statistics
   - Check enrollment percentages
   - Review audit trail

4. **Security Check**
   - Change default passwords
   - Test role-based access
   - Verify file upload restrictions

---

**All checks passing?** Your system is ready for use! 🎓
