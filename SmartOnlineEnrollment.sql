-- ==================================================================
-- Smart Online Enrollment System - Complete Database Script
-- ==================================================================
-- Description: Complete database setup for Smart Online Enrollment
-- Version: 2.0
-- Date: 2026-02-21
-- Author: Smart Enrollment Team
-- ==================================================================
-- This file contains:
-- 1. Database creation
-- 2. All table schemas with constraints
-- 3. Indexes for performance
-- 4. Views for reporting
-- 5. Stored procedures
-- 6. Triggers
-- 7. Complete sample data
-- ==================================================================

-- ==================================================================
-- STEP 1: DATABASE CREATION
-- ==================================================================

-- Drop database if exists (WARNING: This will delete all data!)
DROP DATABASE IF EXISTS `enrollment_system`;

-- Create database with UTF-8 support
CREATE DATABASE `enrollment_system` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Use the database
USE `enrollment_system`;

-- ==================================================================
-- STEP 2: TABLE SCHEMAS
-- ==================================================================

-- ------------------------------------------------------------------
-- Table: Track
-- Description: Academic tracks/strands available for enrollment
-- ------------------------------------------------------------------
CREATE TABLE `Track` (
    `track_id` INTEGER NOT NULL AUTO_INCREMENT,
    `strand_course` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `capacity` INTEGER DEFAULT 50,
    `available_slots` INTEGER DEFAULT 50,
    `tuition_fee` DECIMAL(10,2) DEFAULT 5000.00,
    `previous_school_records` VARCHAR(255),
    `student_photo_url` VARCHAR(255),
    `status` ENUM('Active', 'Inactive', 'Full') DEFAULT 'Active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`track_id`),
    INDEX `idx_strand` (`strand_course`),
    INDEX `idx_status` (`status`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Academic tracks and strands for enrollment';

-- ------------------------------------------------------------------
-- Table: Application
-- Description: Student enrollment applications
-- ------------------------------------------------------------------
CREATE TABLE `Application` (
    `lrn` VARCHAR(40) NOT NULL,
    `first_name` VARCHAR(50) NOT NULL,
    `middle_name` VARCHAR(50),
    `last_name` VARCHAR(50) NOT NULL,
    `suffix` VARCHAR(10),
    `email` VARCHAR(100),
    `phone` VARCHAR(20),
    `address` VARCHAR(255) NOT NULL,
    `city` VARCHAR(100),
    `province` VARCHAR(100),
    `zip_code` VARCHAR(10),
    `age` INTEGER,
    `birthdate` DATE NOT NULL,
    `birthplace` VARCHAR(150),
    `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
    `nationality` VARCHAR(50) DEFAULT 'Filipino',
    `religion` VARCHAR(50),
    `guardian_name` VARCHAR(100),
    `guardian_contact` VARCHAR(50),
    `guardian_relationship` VARCHAR(50),
    `track_id` INTEGER NOT NULL,
    `application_status` ENUM('Pending', 'Under Review', 'Approved', 'Rejected', 'Enrolled', 'Withdrawn') DEFAULT 'Pending',
    `enrollment_date` DATE,
    `remarks` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`lrn`),
    INDEX `idx_name` (`last_name`, `first_name`),
    INDEX `idx_email` (`email`),
    INDEX `idx_status` (`application_status`),
    INDEX `idx_track` (`track_id`),
    INDEX `idx_birthdate` (`birthdate`),
    INDEX `idx_created` (`created_at`),
    CONSTRAINT `FK_Track_Application` 
        FOREIGN KEY (`track_id`) 
        REFERENCES `Track` (`track_id`) 
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT `chk_age` CHECK (`age` >= 0 AND `age` <= 150),
    CONSTRAINT `chk_email` CHECK (`email` REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$' OR `email` IS NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Student enrollment applications';

-- ------------------------------------------------------------------
-- Table: Document
-- Description: Student enrollment documents
-- ------------------------------------------------------------------
CREATE TABLE `Document` (
    `document_id` INTEGER NOT NULL AUTO_INCREMENT,
    `lrn` VARCHAR(40) NOT NULL,
    `birth_certificate_url` VARCHAR(255),
    `birth_certificate_verified` BOOLEAN DEFAULT FALSE,
    `diploma_url` VARCHAR(255),
    `diploma_verified` BOOLEAN DEFAULT FALSE,
    `good_moral_url` VARCHAR(255),
    `good_moral_verified` BOOLEAN DEFAULT FALSE,
    `report_card_url` VARCHAR(255),
    `report_card_verified` BOOLEAN DEFAULT FALSE,
    `id_photo_url` VARCHAR(255),
    `id_photo_verified` BOOLEAN DEFAULT FALSE,
    `medical_certificate_url` VARCHAR(255),
    `medical_certificate_verified` BOOLEAN DEFAULT FALSE,
    `verification_status` ENUM('Incomplete', 'Pending Verification', 'Verified', 'Rejected') DEFAULT 'Incomplete',
    `verified_by` INTEGER,
    `verified_at` TIMESTAMP NULL,
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`document_id`),
    UNIQUE KEY `unique_lrn` (`lrn`),
    INDEX `idx_verification_status` (`verification_status`),
    INDEX `idx_verified_by` (`verified_by`),
    CONSTRAINT `FK_Application_Document` 
        FOREIGN KEY (`lrn`) 
        REFERENCES `Application` (`lrn`) 
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Student enrollment documents and verification';

-- ------------------------------------------------------------------
-- Table: Users
-- Description: System users (admin, staff, registrar, etc.)
-- ------------------------------------------------------------------
CREATE TABLE `Users` (
    `user_id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `role` ENUM('Admin', 'Staff', 'Registrar', 'Principal', 'Teacher') DEFAULT 'Staff',
    `status` ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
    `last_login` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `unique_username` (`username`),
    UNIQUE KEY `unique_email` (`email`),
    INDEX `idx_role` (`role`),
    INDEX `idx_status` (`status`),
    INDEX `idx_last_login` (`last_login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='System users for authentication and authorization';

-- ------------------------------------------------------------------
-- Table: Enrollment_History
-- Description: Audit trail of application status changes
-- ------------------------------------------------------------------
CREATE TABLE `Enrollment_History` (
    `history_id` INTEGER NOT NULL AUTO_INCREMENT,
    `lrn` VARCHAR(40) NOT NULL,
    `old_status` VARCHAR(50),
    `new_status` VARCHAR(50) NOT NULL,
    `changed_by` INTEGER,
    `change_reason` TEXT,
    `changed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`history_id`),
    INDEX `idx_lrn` (`lrn`),
    INDEX `idx_changed_by` (`changed_by`),
    INDEX `idx_changed_at` (`changed_at`),
    CONSTRAINT `FK_Application_History` 
        FOREIGN KEY (`lrn`) 
        REFERENCES `Application` (`lrn`) 
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `FK_User_History` 
        FOREIGN KEY (`changed_by`) 
        REFERENCES `Users` (`user_id`) 
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Audit trail of enrollment status changes';

-- ==================================================================
-- STEP 3: VIEWS FOR REPORTING
-- ==================================================================

-- ------------------------------------------------------------------
-- View: Student_Dashboard_View
-- Description: Consolidated view of student information
-- ------------------------------------------------------------------
CREATE OR REPLACE VIEW `Student_Dashboard_View` AS
SELECT 
    a.lrn,
    CONCAT(a.first_name, ' ', IFNULL(a.middle_name, ''), ' ', a.last_name, ' ', IFNULL(a.suffix, '')) AS full_name,
    a.email,
    a.phone,
    a.age,
    a.gender,
    a.application_status,
    t.strand_course,
    t.tuition_fee,
    d.verification_status AS document_status,
    a.created_at AS application_date,
    a.enrollment_date
FROM Application a
LEFT JOIN Track t ON a.track_id = t.track_id
LEFT JOIN Document d ON a.lrn = d.lrn;

-- ------------------------------------------------------------------
-- View: Track_Statistics_View
-- Description: Statistics per academic track
-- ------------------------------------------------------------------
CREATE OR REPLACE VIEW `Track_Statistics_View` AS
SELECT 
    t.track_id,
    t.strand_course,
    t.capacity,
    t.available_slots,
    COUNT(a.lrn) AS total_applications,
    SUM(CASE WHEN a.application_status = 'Enrolled' THEN 1 ELSE 0 END) AS enrolled_count,
    SUM(CASE WHEN a.application_status = 'Pending' THEN 1 ELSE 0 END) AS pending_count,
    SUM(CASE WHEN a.application_status = 'Approved' THEN 1 ELSE 0 END) AS approved_count,
    t.tuition_fee,
    (SUM(CASE WHEN a.application_status = 'Enrolled' THEN 1 ELSE 0 END) * t.tuition_fee) AS total_revenue
FROM Track t
LEFT JOIN Application a ON t.track_id = a.track_id
GROUP BY t.track_id, t.strand_course, t.capacity, t.available_slots, t.tuition_fee;

-- ==================================================================
-- STEP 4: STORED PROCEDURES
-- ==================================================================

DELIMITER //

-- ------------------------------------------------------------------
-- Procedure: UpdateApplicationStatus
-- Description: Update application status with audit trail
-- ------------------------------------------------------------------
CREATE PROCEDURE `UpdateApplicationStatus`(
    IN p_lrn VARCHAR(40),
    IN p_new_status VARCHAR(50),
    IN p_changed_by INTEGER,
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
        enrollment_date = CASE 
            WHEN p_new_status = 'Enrolled' THEN CURDATE() 
            ELSE enrollment_date 
        END
    WHERE lrn = p_lrn;
    
    -- Insert into history
    INSERT INTO Enrollment_History (lrn, old_status, new_status, changed_by, change_reason)
    VALUES (p_lrn, v_old_status, p_new_status, p_changed_by, p_reason);
END //

DELIMITER ;

-- ==================================================================
-- STEP 5: TRIGGERS
-- ==================================================================

DELIMITER //

-- ------------------------------------------------------------------
-- Trigger: before_track_update
-- Description: Validate track capacity before updates
-- ------------------------------------------------------------------
CREATE TRIGGER `before_track_update`
BEFORE UPDATE ON `Track`
FOR EACH ROW
BEGIN
    IF NEW.available_slots < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Available slots cannot be negative';
    END IF;
    
    IF NEW.available_slots > NEW.capacity THEN
        SET NEW.available_slots = NEW.capacity;
    END IF;
END //

DELIMITER ;

-- ==================================================================
-- STEP 6: SAMPLE DATA
-- ==================================================================

-- ------------------------------------------------------------------
-- Sample Data: Users
-- Password for all users: {role}123 (hashed with bcrypt)
-- ------------------------------------------------------------------
INSERT INTO `Users` (`username`, `password_hash`, `full_name`, `email`, `role`, `status`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@enrollment.edu', 'Admin', 'Active'),
('staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria Santos', 'maria.santos@enrollment.edu', 'Staff', 'Active'),
('registrar', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Dela Cruz', 'john.delacruz@enrollment.edu', 'Registrar', 'Active'),
('principal', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Patricia Reyes', 'patricia.reyes@enrollment.edu', 'Principal', 'Active'),
('teacher1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Robert Garcia', 'robert.garcia@enrollment.edu', 'Teacher', 'Active'),
('teacher2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lisa Mendoza', 'lisa.mendoza@enrollment.edu', 'Teacher', 'Active');

-- ------------------------------------------------------------------
-- Sample Data: Track
-- Academic tracks/strands
-- ------------------------------------------------------------------
INSERT INTO `Track` (`strand_course`, `description`, `capacity`, `available_slots`, `tuition_fee`, `status`) VALUES
('STEM (Science, Technology, Engineering, Mathematics)', 'Focus on science, technology, engineering, and mathematics subjects', 50, 44, 6000.00, 'Active'),
('ABM (Accountancy, Business, and Management)', 'Focuses on business management and accounting principles', 45, 40, 5500.00, 'Active'),
('HUMSS (Humanities and Social Sciences)', 'Emphasis on human behavior, societal changes, and literature', 40, 37, 5000.00, 'Active'),
('GAS (General Academic Strand)', 'Provides a general education for students who are undecided', 35, 33, 5000.00, 'Active'),
('TVL-ICT (Technical-Vocational-Livelihood - ICT)', 'Focus on information and communications technology', 30, 28, 5500.00, 'Active');

-- ------------------------------------------------------------------
-- Sample Data: Application
-- 15 student applications with various statuses
-- ------------------------------------------------------------------
INSERT INTO `Application` (`lrn`, `first_name`, `middle_name`, `last_name`, `email`, `phone`, `address`, `city`, `province`, `age`, `birthdate`, `gender`, `guardian_name`, `guardian_contact`, `track_id`, `application_status`, `enrollment_date`) VALUES
-- Enrolled Students (6)
('1234567890001', 'Juan', 'Santos', 'Dela Cruz', 'juan.delacruz@student.edu', '09171234567', '123 Main St, Brgy. 1', 'Manila', 'Metro Manila', 16, '2008-03-15', 'Male', 'Pedro Dela Cruz', '09171234568', 1, 'Enrolled', '2024-06-15'),
('1234567890002', 'Maria', 'Garcia', 'Reyes', 'maria.reyes@student.edu', '09181234567', '456 Second St, Brgy. 2', 'Quezon City', 'Metro Manila', 17, '2007-07-22', 'Female', 'Rosa Reyes', '09181234568', 2, 'Enrolled', '2024-06-16'),
('1234567890003', 'Pedro', 'Mendoza', 'Santos', 'pedro.santos@student.edu', '09191234567', '789 Third Ave, Brgy. 3', 'Caloocan', 'Metro Manila', 16, '2008-01-10', 'Male', 'Ana Santos', '09191234568', 1, 'Enrolled', '2024-06-17'),
('1234567890004', 'Ana', 'Cruz', 'Lopez', 'ana.lopez@student.edu', '09201234567', '321 Fourth St, Brgy. 4', 'Pasig', 'Metro Manila', 17, '2007-11-05', 'Female', 'Carlos Lopez', '09201234568', 3, 'Enrolled', '2024-06-18'),
('1234567890005', 'Carlos', 'Ramos', 'Gonzales', 'carlos.gonzales@student.edu', '09211234567', '654 Fifth Ave, Brgy. 5', 'Makati', 'Metro Manila', 16, '2008-04-28', 'Male', 'Elena Gonzales', '09211234568', 1, 'Enrolled', '2024-06-19'),
('1234567890006', 'Elena', 'Torres', 'Fernandez', 'elena.fernandez@student.edu', '09221234567', '987 Sixth St, Brgy. 6', 'Taguig', 'Metro Manila', 17, '2007-09-14', 'Female', 'Jose Fernandez', '09221234568', 2, 'Enrolled', '2024-06-20'),

-- Approved Students (3)
('1234567890007', 'Jose', 'Villanueva', 'Martinez', 'jose.martinez@student.edu', '09231234567', '147 Seventh Ave, Brgy. 7', 'Mandaluyong', 'Metro Manila', 16, '2008-02-19', 'Male', 'Linda Martinez', '09231234568', 1, 'Approved', NULL),
('1234567890008', 'Linda', 'Aquino', 'Castillo', 'linda.castillo@student.edu', '09241234567', '258 Eighth St, Brgy. 8', 'San Juan', 'Metro Manila', 17, '2007-06-30', 'Female', 'Miguel Castillo', '09241234568', 4, 'Approved', NULL),
('1234567890009', 'Miguel', 'Perez', 'Navarro', 'miguel.navarro@student.edu', '09251234567', '369 Ninth Ave, Brgy. 9', 'Pasay', 'Metro Manila', 16, '2008-08-11', 'Male', 'Carmen Navarro', '09251234568', 2, 'Approved', NULL),

-- Under Review (2)
('1234567890010', 'Carmen', 'Diaz', 'Morales', 'carmen.morales@student.edu', '09261234567', '741 Tenth St, Brgy. 10', 'Parañaque', 'Metro Manila', 17, '2007-12-25', 'Female', 'Roberto Morales', '09261234568', 3, 'Under Review', NULL),
('1234567890011', 'Roberto', 'Flores', 'Ortiz', 'roberto.ortiz@student.edu', '09271234567', '852 Eleventh Ave, Brgy. 11', 'Las Piñas', 'Metro Manila', 16, '2008-05-17', 'Male', 'Sofia Ortiz', '09271234568', 4, 'Under Review', NULL),

-- Pending (2)
('1234567890012', 'Sofia', 'Ramirez', 'Gutierrez', 'sofia.gutierrez@student.edu', '09281234567', '963 Twelfth St, Brgy. 12', 'Muntinlupa', 'Metro Manila', 17, '2007-10-03', 'Female', 'Daniel Gutierrez', '09281234568', 5, 'Pending', NULL),
('1234567890013', 'Daniel', 'Jimenez', 'Vargas', 'daniel.vargas@student.edu', '09291234567', '159 Thirteenth Ave, Brgy. 13', 'Valenzuela', 'Metro Manila', 16, '2008-07-21', 'Male', 'Patricia Vargas', '09291234568', 5, 'Pending', NULL),

-- Rejected (1)
('1234567890014', 'Patricia', 'Moreno', 'Silva', 'patricia.silva@student.edu', '09301234567', '357 Fourteenth St, Brgy. 14', 'Malabon', 'Metro Manila', 15, '2009-01-08', 'Female', 'Francisco Silva', '09301234568', 1, 'Rejected', NULL),

-- Withdrawn (1)
('1234567890015', 'Francisco', 'Herrera', 'Medina', 'francisco.medina@student.edu', '09311234567', '753 Fifteenth Ave, Brgy. 15', 'Navotas', 'Metro Manila', 17, '2007-04-12', 'Male', 'Isabella Medina', '09311234568', 2, 'Withdrawn', NULL);

-- ------------------------------------------------------------------
-- Sample Data: Document
-- Document records for all applications
-- ------------------------------------------------------------------
INSERT INTO `Document` (`lrn`, `birth_certificate_url`, `birth_certificate_verified`, `diploma_url`, `diploma_verified`, `good_moral_url`, `good_moral_verified`, `report_card_url`, `report_card_verified`, `verification_status`, `verified_by`) VALUES
-- Verified documents (for enrolled students)
('1234567890001', 'uploads/1234567890001/birth_cert.pdf', TRUE, 'uploads/1234567890001/diploma.pdf', TRUE, 'uploads/1234567890001/good_moral.pdf', TRUE, 'uploads/1234567890001/report_card.pdf', TRUE, 'Verified', 1),
('1234567890002', 'uploads/1234567890002/birth_cert.pdf', TRUE, 'uploads/1234567890002/diploma.pdf', TRUE, 'uploads/1234567890002/good_moral.pdf', TRUE, 'uploads/1234567890002/report_card.pdf', TRUE, 'Verified', 1),
('1234567890003', 'uploads/1234567890003/birth_cert.pdf', TRUE, 'uploads/1234567890003/diploma.pdf', TRUE, 'uploads/1234567890003/good_moral.pdf', TRUE, 'uploads/1234567890003/report_card.pdf', TRUE, 'Verified', 1),
('1234567890004', 'uploads/1234567890004/birth_cert.pdf', TRUE, 'uploads/1234567890004/diploma.pdf', TRUE, 'uploads/1234567890004/good_moral.pdf', TRUE, 'uploads/1234567890004/report_card.pdf', TRUE, 'Verified', 1),
('1234567890005', 'uploads/1234567890005/birth_cert.pdf', TRUE, 'uploads/1234567890005/diploma.pdf', TRUE, 'uploads/1234567890005/good_moral.pdf', TRUE, 'uploads/1234567890005/report_card.pdf', TRUE, 'Verified', 1),
('1234567890006', 'uploads/1234567890006/birth_cert.pdf', TRUE, 'uploads/1234567890006/diploma.pdf', TRUE, 'uploads/1234567890006/good_moral.pdf', TRUE, 'uploads/1234567890006/report_card.pdf', TRUE, 'Verified', 1),

-- Pending verification (for approved students)
('1234567890007', 'uploads/1234567890007/birth_cert.pdf', FALSE, 'uploads/1234567890007/diploma.pdf', FALSE, 'uploads/1234567890007/good_moral.pdf', FALSE, 'uploads/1234567890007/report_card.pdf', FALSE, 'Pending Verification', NULL),
('1234567890008', 'uploads/1234567890008/birth_cert.pdf', FALSE, 'uploads/1234567890008/diploma.pdf', FALSE, 'uploads/1234567890008/good_moral.pdf', FALSE, 'uploads/1234567890008/report_card.pdf', FALSE, 'Pending Verification', NULL),
('1234567890009', 'uploads/1234567890009/birth_cert.pdf', FALSE, 'uploads/1234567890009/diploma.pdf', FALSE, 'uploads/1234567890009/good_moral.pdf', FALSE, 'uploads/1234567890009/report_card.pdf', FALSE, 'Pending Verification', NULL),

-- Incomplete (for under review)
('1234567890010', 'uploads/1234567890010/birth_cert.pdf', FALSE, NULL, FALSE, 'uploads/1234567890010/good_moral.pdf', FALSE, NULL, FALSE, 'Incomplete', NULL),
('1234567890011', 'uploads/1234567890011/birth_cert.pdf', FALSE, 'uploads/1234567890011/diploma.pdf', FALSE, NULL, FALSE, 'uploads/1234567890011/report_card.pdf', FALSE, 'Incomplete', NULL),

-- Incomplete (for pending)
('1234567890012', 'uploads/1234567890012/birth_cert.pdf', FALSE, NULL, FALSE, NULL, FALSE, NULL, FALSE, 'Incomplete', NULL),
('1234567890013', NULL, FALSE, NULL, FALSE, NULL, FALSE, NULL, FALSE, 'Incomplete', NULL),

-- Rejected (for rejected student)
('1234567890014', 'uploads/1234567890014/birth_cert.pdf', FALSE, NULL, FALSE, NULL, FALSE, NULL, FALSE, 'Rejected', 1),

-- Incomplete (for withdrawn)
('1234567890015', 'uploads/1234567890015/birth_cert.pdf', FALSE, 'uploads/1234567890015/diploma.pdf', FALSE, NULL, FALSE, NULL, FALSE, 'Incomplete', NULL);

-- ------------------------------------------------------------------
-- Sample Data: Enrollment_History
-- Audit trail of status changes
-- ------------------------------------------------------------------
INSERT INTO `Enrollment_History` (`lrn`, `old_status`, `new_status`, `changed_by`, `change_reason`) VALUES
-- History for enrolled students
('1234567890001', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890001', 'Under Review', 'Approved', 3, 'Documents verified, application approved'),
('1234567890001', 'Approved', 'Enrolled', 3, 'Student completed enrollment process'),

('1234567890002', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890002', 'Under Review', 'Approved', 3, 'Documents verified, application approved'),
('1234567890002', 'Approved', 'Enrolled', 3, 'Student completed enrollment process'),

('1234567890003', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890003', 'Under Review', 'Approved', 3, 'Documents verified, application approved'),
('1234567890003', 'Approved', 'Enrolled', 3, 'Student completed enrollment process'),

('1234567890004', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890004', 'Under Review', 'Approved', 3, 'Documents verified, application approved'),
('1234567890004', 'Approved', 'Enrolled', 3, 'Student completed enrollment process'),

('1234567890005', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890005', 'Under Review', 'Approved', 3, 'Documents verified, application approved'),
('1234567890005', 'Approved', 'Enrolled', 3, 'Student completed enrollment process'),

('1234567890006', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890006', 'Under Review', 'Approved', 3, 'Documents verified, application approved'),
('1234567890006', 'Approved', 'Enrolled', 3, 'Student completed enrollment process'),

-- History for approved students
('1234567890007', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890007', 'Under Review', 'Approved', 3, 'Documents verified, application approved'),

('1234567890008', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890008', 'Under Review', 'Approved', 3, 'Documents verified, application approved'),

('1234567890009', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890009', 'Under Review', 'Approved', 3, 'Documents verified, application approved'),

-- History for under review
('1234567890010', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890011', 'Pending', 'Under Review', 2, 'Initial review started'),

-- History for rejected
('1234567890014', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890014', 'Under Review', 'Rejected', 3, 'Age requirement not met'),

-- History for withdrawn
('1234567890015', 'Pending', 'Under Review', 2, 'Initial review started'),
('1234567890015', 'Under Review', 'Approved', 3, 'Documents verified, application approved'),
('1234567890015', 'Approved', 'Withdrawn', 2, 'Student withdrew application');

-- ==================================================================
-- STEP 7: DATABASE SUMMARY
-- ==================================================================

-- Display database information
SELECT 
    'DATABASE CREATED SUCCESSFULLY' AS Status,
    DATABASE() AS Database_Name,
    @@character_set_database AS Charset,
    @@collation_database AS Collation;

-- Display table counts
SELECT 'TABLES CREATED' AS Info;
SELECT 
    TABLE_NAME AS 'Table',
    TABLE_ROWS AS 'Rows',
    ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'enrollment_system'
ORDER BY TABLE_NAME;

-- Display sample data summary
SELECT 'SAMPLE DATA SUMMARY' AS Info;
SELECT COUNT(*) AS Total_Users FROM Users;
SELECT COUNT(*) AS Total_Tracks FROM Track;
SELECT COUNT(*) AS Total_Applications FROM Application;
SELECT COUNT(*) AS Total_Documents FROM Document;
SELECT COUNT(*) AS Total_History_Records FROM Enrollment_History;

-- Display statistics by status
SELECT 'APPLICATION STATUS DISTRIBUTION' AS Info;
SELECT 
    application_status AS Status,
    COUNT(*) AS Count
FROM Application
GROUP BY application_status
ORDER BY COUNT(*) DESC;

-- ==================================================================
-- INSTALLATION COMPLETE!
-- ==================================================================
-- 
-- To use this database:
-- 1. Import this file: mysql -u root -p < SmartOnlineEnrollment.sql
-- 2. Configure your PHP files to use database: enrollment_system
-- 3. Default login credentials:
--    Username: admin
--    Password: admin123
--
-- Database includes:
-- ✅ 5 Tables (Track, Application, Document, Users, Enrollment_History)
-- ✅ 2 Views (Student_Dashboard_View, Track_Statistics_View)
-- ✅ 1 Stored Procedure (UpdateApplicationStatus)
-- ✅ 1 Trigger (before_track_update)
-- ✅ Sample Data: 6 users, 5 tracks, 15 students, 15 documents, 34 history records
-- ✅ Complete indexes and constraints for optimal performance
--
-- ==================================================================
