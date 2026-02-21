-- ---------------------------------------------------------------------- 
-- Target DBMS: MySQL 5+                                                  
-- Project: Smart Online Enrollment System                                       
-- ---------------------------------------------------------------------- 

-- Create database
CREATE DATABASE IF NOT EXISTS `enrollment_system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `enrollment_system`;

-- 1. Create Track Table first (referenced by Application)
CREATE TABLE IF NOT EXISTS `Track` (
    `track_id` INTEGER NOT NULL AUTO_INCREMENT,
    `strand_course` VARCHAR(100) NOT NULL,
    `previous_school_records` VARCHAR(255),
    `student_photo_url` VARCHAR(255),
    `description` TEXT,
    `tuition_fee` DECIMAL(10,2) DEFAULT 5000.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`track_id`),
    INDEX `idx_strand` (`strand_course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Create Application Table
CREATE TABLE IF NOT EXISTS `Application` (
    `lrn` VARCHAR(40) NOT NULL,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `age` INTEGER,
    `birthdate` DATE,
    `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
    `guardian_name_contact` VARCHAR(150),
    `track_id` INTEGER NOT NULL,
    `status` ENUM('Pending', 'Under Review', 'Approved', 'Rejected', 'Enrolled', 'Withdrawn') DEFAULT 'Pending',
    `application_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`lrn`),
    INDEX `idx_track` (`track_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_name` (`last_name`, `first_name`),
    CONSTRAINT `FK_Track_Application` 
        FOREIGN KEY (`track_id`) REFERENCES `Track` (`track_id`) 
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Create Document Table (Linked to Application via LRN)
CREATE TABLE IF NOT EXISTS `Document` (
    `document_id` INTEGER NOT NULL AUTO_INCREMENT,
    `lrn` VARCHAR(40) NOT NULL,
    `birth_certificate_url` VARCHAR(255),
    `diploma_url` VARCHAR(255),
    `good_moral_url` VARCHAR(255),
    `report_card_url` VARCHAR(255),
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`document_id`),
    UNIQUE KEY `unique_lrn` (`lrn`),
    CONSTRAINT `FK_Application_Document` 
        FOREIGN KEY (`lrn`) REFERENCES `Application` (`lrn`) 
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Create Users Table for Authentication
CREATE TABLE IF NOT EXISTS `Users` (
    `user_id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('Admin', 'Staff', 'Registrar', 'Principal', 'Teacher') DEFAULT 'Staff',
    `email` VARCHAR(100),
    `full_name` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `last_login` TIMESTAMP NULL,
    PRIMARY KEY (`user_id`),
    INDEX `idx_username` (`username`),
    INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Create Enrollment_History Table for tracking changes
CREATE TABLE IF NOT EXISTS `Enrollment_History` (
    `history_id` INTEGER NOT NULL AUTO_INCREMENT,
    `lrn` VARCHAR(40) NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    `old_status` VARCHAR(30),
    `new_status` VARCHAR(30),
    `changed_by` INTEGER,
    `changed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `notes` TEXT,
    PRIMARY KEY (`history_id`),
    INDEX `idx_lrn` (`lrn`),
    INDEX `idx_changed_at` (`changed_at`),
    CONSTRAINT `FK_Application_History`
        FOREIGN KEY (`lrn`) REFERENCES `Application` (`lrn`)
        ON DELETE CASCADE,
    CONSTRAINT `FK_User_History`
        FOREIGN KEY (`changed_by`) REFERENCES `Users` (`user_id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Views for easier querying
CREATE OR REPLACE VIEW `vw_student_overview` AS
SELECT 
    a.lrn,
    CONCAT(a.first_name, ' ', a.last_name) AS full_name,
    a.first_name,
    a.last_name,
    a.age,
    a.birthdate,
    a.gender,
    a.address,
    a.guardian_name_contact,
    t.strand_course,
    t.tuition_fee,
    a.status,
    a.application_date,
    CASE 
        WHEN d.document_id IS NOT NULL THEN 'Uploaded'
        ELSE 'Pending'
    END AS document_status
FROM Application a
JOIN Track t ON a.track_id = t.track_id
LEFT JOIN Document d ON a.lrn = d.lrn;

-- Create a stored procedure for updating application status
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS `sp_update_application_status`(
    IN p_lrn VARCHAR(40),
    IN p_new_status VARCHAR(30),
    IN p_user_id INTEGER,
    IN p_notes TEXT
)
BEGIN
    DECLARE v_old_status VARCHAR(30);
    
    -- Get current status
    SELECT status INTO v_old_status FROM Application WHERE lrn = p_lrn;
    
    -- Update application status
    UPDATE Application SET status = p_new_status WHERE lrn = p_lrn;
    
    -- Log the change in history
    INSERT INTO Enrollment_History (lrn, action, old_status, new_status, changed_by, notes)
    VALUES (p_lrn, 'STATUS_UPDATE', v_old_status, p_new_status, p_user_id, p_notes);
END //
DELIMITER ;

-- Create trigger to automatically calculate age from birthdate
DELIMITER //
CREATE TRIGGER IF NOT EXISTS `trg_calculate_age`
BEFORE INSERT ON Application
FOR EACH ROW
BEGIN
    IF NEW.birthdate IS NOT NULL THEN
        SET NEW.age = TIMESTAMPDIFF(YEAR, NEW.birthdate, CURDATE());
    END IF;
END //

CREATE TRIGGER IF NOT EXISTS `trg_update_age`
BEFORE UPDATE ON Application
FOR EACH ROW
BEGIN
    IF NEW.birthdate IS NOT NULL THEN
        SET NEW.age = TIMESTAMPDIFF(YEAR, NEW.birthdate, CURDATE());
    END IF;
END //
DELIMITER ;

-- Insert default admin user (password: admin123)
INSERT INTO Users (username, password, role, email, full_name) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'admin@enrollment.local', 'System Administrator')
ON DUPLICATE KEY UPDATE username = username;

-- Insert sample tracks
INSERT INTO Track (strand_course, description, tuition_fee) VALUES
('STEM (Science, Technology, Engineering, Mathematics)', 'For students interested in science, engineering, and mathematics careers', 5500.00),
('ABM (Accountancy, Business, and Management)', 'For students interested in business, accounting, and management', 5000.00),
('HUMSS (Humanities and Social Sciences)', 'For students interested in social sciences, humanities, and liberal arts', 5000.00),
('GAS (General Academic Strand)', 'For students who are undecided on their career path', 5000.00),
('TVL (Technical-Vocational-Livelihood)', 'For students who want to pursue technical or vocational careers', 5200.00)
ON DUPLICATE KEY UPDATE strand_course = VALUES(strand_course);
