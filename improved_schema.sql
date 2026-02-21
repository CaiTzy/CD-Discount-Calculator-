-- ======================================================================
-- Smart Online Enrollment System - Improved Database Schema
-- ======================================================================
-- Target DBMS: MySQL 5.7+ / MariaDB 10.2+
-- Character Set: utf8mb4 (full Unicode support including emojis)
-- Collation: utf8mb4_unicode_ci (case-insensitive, accent-insensitive)
-- Engine: InnoDB (supports foreign keys, transactions, ACID compliance)
-- ======================================================================

-- Set database character set and collation
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ======================================================================
-- Table 1: Track
-- Purpose: Stores academic track/strand information for students
-- ======================================================================
CREATE TABLE IF NOT EXISTS `Track` (
    -- Primary Key
    `track_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    
    -- Track Information
    `strand_course` VARCHAR(100) NOT NULL COMMENT 'Name of the academic strand or course (e.g., STEM, ABM, HUMSS)',
    `track_code` VARCHAR(20) UNIQUE COMMENT 'Unique code for the track (e.g., STEM-01, ABM-02)',
    `description` TEXT COMMENT 'Detailed description of the track/strand',
    `capacity` INTEGER UNSIGNED DEFAULT 40 COMMENT 'Maximum number of students allowed',
    `tuition_fee` DECIMAL(10,2) UNSIGNED DEFAULT 0.00 COMMENT 'Base tuition fee for this track',
    
    -- File References
    `previous_school_records` VARCHAR(255) COMMENT 'Template or requirements for previous school records',
    `student_photo_url` VARCHAR(255) COMMENT 'Default photo URL or requirements',
    
    -- Status
    `is_active` BOOLEAN DEFAULT TRUE COMMENT 'Whether this track is currently accepting enrollments',
    
    -- Audit Fields
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record last update timestamp',
    
    -- Constraints
    PRIMARY KEY (`track_id`),
    INDEX `idx_track_code` (`track_code`),
    INDEX `idx_active_tracks` (`is_active`, `track_id`),
    
    -- Validation
    CONSTRAINT `chk_capacity` CHECK (`capacity` > 0),
    CONSTRAINT `chk_tuition_fee` CHECK (`tuition_fee` >= 0)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Academic tracks/strands available for student enrollment';

-- ======================================================================
-- Table 2: Application
-- Purpose: Stores student enrollment application information
-- ======================================================================
CREATE TABLE IF NOT EXISTS `Application` (
    -- Primary Key (LRN - Learner Reference Number)
    `lrn` VARCHAR(40) NOT NULL COMMENT 'Learner Reference Number (unique student identifier)',
    
    -- Personal Information
    `first_name` VARCHAR(50) NOT NULL COMMENT 'Student first name',
    `middle_name` VARCHAR(50) COMMENT 'Student middle name',
    `last_name` VARCHAR(50) NOT NULL COMMENT 'Student last name',
    `suffix` VARCHAR(10) COMMENT 'Name suffix (Jr., Sr., III, etc.)',
    
    -- Contact Information
    `email` VARCHAR(100) UNIQUE COMMENT 'Student email address',
    `phone_number` VARCHAR(20) COMMENT 'Student contact number',
    `address` VARCHAR(255) NOT NULL COMMENT 'Complete residential address',
    `city` VARCHAR(100) COMMENT 'City/Municipality',
    `province` VARCHAR(100) COMMENT 'Province',
    `zip_code` VARCHAR(10) COMMENT 'Postal/ZIP code',
    
    -- Demographics
    `age` TINYINT UNSIGNED COMMENT 'Student age at time of application',
    `birthdate` DATE NOT NULL COMMENT 'Student date of birth',
    `gender` ENUM('Male', 'Female', 'Other') NOT NULL COMMENT 'Student gender',
    `nationality` VARCHAR(50) DEFAULT 'Filipino' COMMENT 'Student nationality',
    
    -- Guardian Information
    `guardian_name` VARCHAR(100) COMMENT 'Full name of parent/guardian',
    `guardian_contact` VARCHAR(20) COMMENT 'Guardian contact number',
    `guardian_email` VARCHAR(100) COMMENT 'Guardian email address',
    `guardian_relationship` VARCHAR(50) COMMENT 'Relationship to student (e.g., Mother, Father, Guardian)',
    
    -- Academic Track Reference
    `track_id` INTEGER UNSIGNED NOT NULL COMMENT 'Reference to chosen track/strand',
    
    -- Application Status
    `application_status` ENUM('Pending', 'Under Review', 'Approved', 'Rejected', 'Enrolled', 'Withdrawn') 
        DEFAULT 'Pending' COMMENT 'Current status of the application',
    `application_date` DATE DEFAULT (CURRENT_DATE) COMMENT 'Date application was submitted',
    `enrollment_date` DATE COMMENT 'Date student was officially enrolled',
    
    -- Additional Information
    `previous_school` VARCHAR(200) COMMENT 'Name of previous school attended',
    `year_graduated` YEAR COMMENT 'Year of graduation from previous school',
    `gwa` DECIMAL(3,2) COMMENT 'General Weighted Average (e.g., 1.25, 2.50)',
    
    -- Audit Fields
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record last update timestamp',
    
    -- Constraints
    PRIMARY KEY (`lrn`),
    
    -- Indexes for performance
    INDEX `idx_last_name` (`last_name`),
    INDEX `idx_track_id` (`track_id`),
    INDEX `idx_application_status` (`application_status`),
    INDEX `idx_application_date` (`application_date`),
    INDEX `idx_email` (`email`),
    INDEX `idx_full_name` (`last_name`, `first_name`),
    
    -- Foreign Key Constraints
    CONSTRAINT `FK_Track_Application` 
        FOREIGN KEY (`track_id`) 
        REFERENCES `Track` (`track_id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
        COMMENT 'Prevent track deletion if students are enrolled',
    
    -- Validation Constraints
    CONSTRAINT `chk_age_valid` CHECK (`age` BETWEEN 5 AND 100),
    CONSTRAINT `chk_birthdate_valid` CHECK (`birthdate` <= CURDATE()),
    CONSTRAINT `chk_gwa_valid` CHECK (`gwa` BETWEEN 1.00 AND 5.00),
    CONSTRAINT `chk_enrollment_after_application` CHECK (`enrollment_date` IS NULL OR `enrollment_date` >= `application_date`)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Student enrollment applications and information';

-- ======================================================================
-- Table 3: Document
-- Purpose: Stores URLs/references to required enrollment documents
-- ======================================================================
CREATE TABLE IF NOT EXISTS `Document` (
    -- Primary Key
    `document_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    
    -- Link to Application
    `lrn` VARCHAR(40) NOT NULL COMMENT 'Reference to student LRN',
    
    -- Required Documents
    `birth_certificate_url` VARCHAR(255) COMMENT 'URL/path to birth certificate',
    `diploma_url` VARCHAR(255) COMMENT 'URL/path to diploma or certificate of completion',
    `good_moral_url` VARCHAR(255) COMMENT 'URL/path to certificate of good moral character',
    `report_card_url` VARCHAR(255) COMMENT 'URL/path to Form 138 (report card)',
    `photo_2x2_url` VARCHAR(255) COMMENT 'URL/path to 2x2 ID photo',
    `transfer_credentials_url` VARCHAR(255) COMMENT 'URL/path to transfer credentials (if applicable)',
    
    -- Document Verification Status
    `verification_status` ENUM('Pending', 'Verified', 'Rejected', 'Incomplete') 
        DEFAULT 'Pending' COMMENT 'Document verification status',
    `verified_by` VARCHAR(100) COMMENT 'Name of staff who verified documents',
    `verified_at` TIMESTAMP NULL COMMENT 'Timestamp when documents were verified',
    `verification_notes` TEXT COMMENT 'Notes or reasons for rejection',
    
    -- Audit Fields
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When documents were first uploaded',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp',
    
    -- Constraints
    PRIMARY KEY (`document_id`),
    
    -- Unique constraint to ensure one document record per student
    UNIQUE KEY `idx_unique_lrn` (`lrn`),
    
    -- Index for verification status
    INDEX `idx_verification_status` (`verification_status`),
    
    -- Foreign Key Constraints
    CONSTRAINT `FK_Application_Document` 
        FOREIGN KEY (`lrn`) 
        REFERENCES `Application` (`lrn`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
        COMMENT 'Delete documents when application is deleted'
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Student enrollment documents and verification status';

-- ======================================================================
-- Table 4: Users (Optional - for system access control)
-- Purpose: Stores user credentials for staff/admin access
-- ======================================================================
CREATE TABLE IF NOT EXISTS `Users` (
    -- Primary Key
    `user_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    
    -- Credentials
    `username` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Login username',
    `password_hash` VARCHAR(255) NOT NULL COMMENT 'Hashed password (use PASSWORD() or bcrypt)',
    `email` VARCHAR(100) NOT NULL UNIQUE COMMENT 'User email address',
    
    -- User Information
    `full_name` VARCHAR(100) NOT NULL COMMENT 'Full name of user',
    `role` ENUM('Admin', 'Staff', 'Registrar', 'Principal', 'Teacher') 
        DEFAULT 'Staff' COMMENT 'User role/permission level',
    
    -- Status
    `is_active` BOOLEAN DEFAULT TRUE COMMENT 'Whether account is active',
    `last_login` TIMESTAMP NULL COMMENT 'Last successful login timestamp',
    
    -- Audit Fields
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Constraints
    PRIMARY KEY (`user_id`),
    INDEX `idx_username` (`username`),
    INDEX `idx_email` (`email`),
    INDEX `idx_role` (`role`)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_unicode_ci
  COMMENT='System users for enrollment management';

-- ======================================================================
-- Table 5: Enrollment_History (Optional - for audit trail)
-- Purpose: Tracks all status changes in applications
-- ======================================================================
CREATE TABLE IF NOT EXISTS `Enrollment_History` (
    `history_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `lrn` VARCHAR(40) NOT NULL COMMENT 'Student LRN',
    `previous_status` VARCHAR(50) COMMENT 'Previous application status',
    `new_status` VARCHAR(50) NOT NULL COMMENT 'New application status',
    `changed_by` VARCHAR(100) COMMENT 'User who made the change',
    `change_reason` TEXT COMMENT 'Reason for status change',
    `changed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When change occurred',
    
    PRIMARY KEY (`history_id`),
    INDEX `idx_lrn_history` (`lrn`),
    INDEX `idx_changed_at` (`changed_at`),
    
    CONSTRAINT `FK_Application_History`
        FOREIGN KEY (`lrn`)
        REFERENCES `Application` (`lrn`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Audit trail for application status changes';

-- ======================================================================
-- Sample Data for Testing (Optional)
-- ======================================================================

-- Insert sample tracks
INSERT INTO `Track` (`strand_course`, `track_code`, `description`, `capacity`, `tuition_fee`, `is_active`) VALUES
('STEM - Science, Technology, Engineering, and Mathematics', 'STEM-01', 'For students interested in careers in science, engineering, medicine, and technology', 50, 25000.00, TRUE),
('ABM - Accountancy, Business and Management', 'ABM-01', 'For students planning to pursue business, accounting, or management courses', 45, 24000.00, TRUE),
('HUMSS - Humanities and Social Sciences', 'HUMSS-01', 'For students interested in social sciences, education, and humanities', 40, 23000.00, TRUE),
('GAS - General Academic Strand', 'GAS-01', 'For undecided students or those wanting a broad academic foundation', 40, 22000.00, TRUE),
('TVL-ICT - Technical-Vocational-Livelihood (ICT)', 'TVL-ICT-01', 'Technical training in Information and Communications Technology', 35, 26000.00, TRUE);

-- ======================================================================
-- Views for Common Queries (Optional)
-- ======================================================================

-- View: Active Applications Summary
CREATE OR REPLACE VIEW `vw_active_applications` AS
SELECT 
    a.lrn,
    CONCAT(a.last_name, ', ', a.first_name, ' ', IFNULL(a.middle_name, '')) AS full_name,
    a.email,
    a.phone_number,
    t.strand_course,
    a.application_status,
    a.application_date,
    d.verification_status,
    DATEDIFF(CURDATE(), a.application_date) AS days_pending
FROM Application a
INNER JOIN Track t ON a.track_id = t.track_id
LEFT JOIN Document d ON a.lrn = d.lrn
WHERE a.application_status IN ('Pending', 'Under Review');

-- View: Track Enrollment Statistics
CREATE OR REPLACE VIEW `vw_track_statistics` AS
SELECT 
    t.track_id,
    t.strand_course,
    t.track_code,
    t.capacity,
    COUNT(a.lrn) AS enrolled_students,
    t.capacity - COUNT(a.lrn) AS available_slots,
    ROUND((COUNT(a.lrn) / t.capacity * 100), 2) AS enrollment_percentage
FROM Track t
LEFT JOIN Application a ON t.track_id = a.track_id 
    AND a.application_status IN ('Approved', 'Enrolled')
WHERE t.is_active = TRUE
GROUP BY t.track_id, t.strand_course, t.track_code, t.capacity;

-- ======================================================================
-- Stored Procedures (Optional)
-- ======================================================================

DELIMITER //

-- Procedure: Update Application Status with History
CREATE PROCEDURE `sp_update_application_status`(
    IN p_lrn VARCHAR(40),
    IN p_new_status VARCHAR(50),
    IN p_changed_by VARCHAR(100),
    IN p_reason TEXT
)
BEGIN
    DECLARE v_old_status VARCHAR(50);
    
    -- Get current status
    SELECT application_status INTO v_old_status
    FROM Application
    WHERE lrn = p_lrn;
    
    -- Update status
    UPDATE Application
    SET application_status = p_new_status,
        enrollment_date = CASE WHEN p_new_status = 'Enrolled' THEN CURDATE() ELSE enrollment_date END
    WHERE lrn = p_lrn;
    
    -- Log to history
    INSERT INTO Enrollment_History (lrn, previous_status, new_status, changed_by, change_reason)
    VALUES (p_lrn, v_old_status, p_new_status, p_changed_by, p_reason);
END //

DELIMITER ;

-- ======================================================================
-- Triggers for Data Validation (Optional)
-- ======================================================================

DELIMITER //

-- Trigger: Validate track capacity before enrollment
CREATE TRIGGER `trg_check_track_capacity` 
BEFORE UPDATE ON `Application`
FOR EACH ROW
BEGIN
    DECLARE v_current_enrolled INT;
    DECLARE v_capacity INT;
    
    IF NEW.application_status = 'Enrolled' AND OLD.application_status != 'Enrolled' THEN
        -- Count current enrollments
        SELECT COUNT(*) INTO v_current_enrolled
        FROM Application
        WHERE track_id = NEW.track_id 
        AND application_status = 'Enrolled';
        
        -- Get track capacity
        SELECT capacity INTO v_capacity
        FROM Track
        WHERE track_id = NEW.track_id;
        
        -- Check if capacity exceeded
        IF v_current_enrolled >= v_capacity THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Track capacity exceeded. Cannot enroll more students.';
        END IF;
    END IF;
END //

DELIMITER ;

-- ======================================================================
-- End of Schema
-- ======================================================================
