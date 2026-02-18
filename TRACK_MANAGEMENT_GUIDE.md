# Track Management System - User Guide

## 🎯 Overview

The Track Management System allows administrators to create, edit, and manage educational tracks/strands for student enrollment. This is a crucial component of the Smart Online Enrollment System.

---

## 🚀 Accessing Track Management

**URL:** `http://localhost/enrollment/manage_tracks.php`

**Requirements:**
- Must be logged in as an admin
- Database must be properly set up

**Navigation:**
- From Admin Dashboard → Click "🎯 Manage Tracks" button
- Or access directly via URL

---

## ✨ Features

### 1. Add New Tracks
Create new educational programs/strands with:
- Track/Strand Name (e.g., STEM, ABM, HUMSS)
- Enrollment Fee (in Philippine Pesos)
- Required Documents (optional)
- Photo Requirements (optional)

### 2. Edit Existing Tracks
Update track information:
- Modify track name
- Change enrollment fee
- Update requirements

### 3. View All Tracks
See a comprehensive list of all tracks with:
- Track ID
- Name
- Enrollment fee
- Number of enrolled students
- Revenue generated

### 4. Delete Tracks
Remove tracks that are no longer needed:
- ✅ Can delete tracks with no students
- ❌ Cannot delete tracks with enrolled students (protected by foreign key)

### 5. Statistics Dashboard
View track performance:
- Total students per track
- Revenue per track
- Overall statistics

---

## 📝 Step-by-Step Guide

### Adding a New Track

1. **Access the Management Page**
   - Login as admin
   - Go to `manage_tracks.php`

2. **Fill in the Form**
   - **Track/Strand Name:** Full name (e.g., "STEM - Science, Technology, Engineering, Mathematics")
   - **Enrollment Fee:** Base price (e.g., 6000.00)
   - **Required Documents:** (Optional) List of documents (e.g., "Report Card, Diploma")
   - **Photo Requirements:** (Optional) Specifications (e.g., "2x2 ID photo")

3. **Submit**
   - Click "➕ Add Track" button
   - Track will be created with a unique ID
   - Students can now enroll in this track

### Editing a Track

1. **Find the Track**
   - Scroll through the track list
   - Click "✏️ Edit" button on the track card

2. **Update Information**
   - Modify any field
   - Click "💾 Update Track"

3. **Cancel (if needed)**
   - Click "Cancel Edit" to return without changes

### Deleting a Track

1. **Check Student Count**
   - Only tracks with 0 students can be deleted
   - Tracks "In Use" are protected

2. **Delete**
   - Click "🗑️ Delete" button
   - Confirm the deletion
   - Track will be permanently removed

---

## 🎓 Sample Tracks (Pre-loaded)

The system comes with 5 sample tracks:

| ID | Track/Strand | Fee |
|----|--------------|-----|
| 1 | STEM (Science, Technology, Engineering, Mathematics) | ₱6,000.00 |
| 2 | ABM (Accountancy, Business, Management) | ₱5,500.00 |
| 3 | HUMSS (Humanities and Social Sciences) | ₱5,000.00 |
| 4 | GAS (General Academic Strand) | ₱5,000.00 |
| 5 | TVL-ICT (Technical-Vocational-Livelihood) | ₱5,500.00 |

---

## ⚠️ Important Notes

### Foreign Key Protection
- Tracks with enrolled students **cannot be deleted**
- This prevents data integrity issues
- To delete a track in use, you must first:
  1. Reassign all students to different tracks, OR
  2. Delete all student enrollments (not recommended)

### Track ID
- Each track gets a unique ID automatically
- Track IDs cannot be changed
- Track IDs are used in foreign key relationships

### Pricing
- Enrollment fees should be positive numbers
- Use 2 decimal places for precision (e.g., 5000.00)
- Fees can be updated anytime
- New fee applies to future enrollments only

---

## 🔧 Troubleshooting

### "No tracks available" Warning

**Problem:** Enrollment form shows warning about no tracks

**Solution:**
1. Access `manage_tracks.php`
2. Add at least one track
3. Students can now enroll

### Foreign Key Constraint Error

**Problem:** Error when enrolling students

**Cause:** Track table is empty or invalid track_id used

**Solution:**
1. Run `diagnose_fk_error.php` for diagnostics
2. Add tracks using `manage_tracks.php`
3. Or use auto-fix in diagnostic tool

### Cannot Delete Track

**Problem:** "Cannot delete - In Use" message

**Reason:** Track has enrolled students

**Options:**
1. Keep the track (recommended)
2. Reassign students to different tracks
3. Accept data loss and remove enrollments

---

## 📊 Track Statistics

The statistics table shows:
- **Students:** Number of students enrolled
- **Revenue:** Total potential revenue (fee × students)
- **Totals:** Sum across all tracks

This helps administrators:
- Identify popular tracks
- Calculate expected revenue
- Make informed decisions about programs

---

## 💡 Best Practices

### Naming Tracks
- Use clear, descriptive names
- Include full program name
- Add acronym in parentheses
- Example: "STEM (Science, Technology, Engineering, Mathematics)"

### Setting Fees
- Research market rates
- Consider program costs
- Update annually if needed
- Keep fees competitive

### Managing Tracks
- Review track performance regularly
- Remove unused tracks (if no students)
- Update requirements as needed
- Maintain accurate information

### Before Deleting
- Check student count
- Backup data if needed
- Consider archiving instead
- Ensure track is truly obsolete

---

## 🔗 Related Pages

- **Admin Dashboard:** `admin_dashboard.php` - Overview and quick stats
- **Student List:** `student_list.php` - View all enrollments
- **Enrollment Form:** `enrollment_form.php` - Enroll students
- **Diagnostics:** `diagnose_fk_error.php` - Fix track issues

---

## 📖 Database Structure

### Track Table Schema
```sql
CREATE TABLE Track (
    track_id INTEGER NOT NULL AUTO_INCREMENT,
    strand_course VARCHAR(100),              -- Track name
    previous_school_records VARCHAR(255),    -- Required documents
    student_photo_url VARCHAR(255),          -- Photo requirements
    enrollment_fee DECIMAL(10, 2),           -- Base fee
    PRIMARY KEY (track_id)
);
```

### Relationships
- **Application.track_id** → **Track.track_id** (Foreign Key)
- On Delete: CASCADE (deletes all related enrollments)

---

## ✅ Quick Checklist

Before students can enroll:
- [ ] At least one track exists
- [ ] Track has a valid name
- [ ] Enrollment fee is set
- [ ] Track ID is unique
- [ ] Database is properly connected

After adding tracks:
- [ ] Verify track appears in enrollment form dropdown
- [ ] Test enrollment with new track
- [ ] Check track statistics
- [ ] Confirm pricing is correct

---

## 🎉 Success!

You now have a complete track management system!

**Next Steps:**
1. Add your institution's tracks
2. Set appropriate fees
3. Let students start enrolling
4. Monitor statistics regularly

**Need Help?**
- See FK_ERROR_FIX.md for troubleshooting
- Run diagnose_fk_error.php for diagnostics
- Check INDEX.md for all documentation

---

**Last Updated:** February 2026  
**Version:** 1.0  
**System:** Smart Online Enrollment
