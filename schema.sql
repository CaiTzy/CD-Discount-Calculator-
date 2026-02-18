# ---------------------------------------------------------------------- #
# Target DBMS: MySQL 5+                                                  #
# Project: Smart Online Enrollment                                       #
# ---------------------------------------------------------------------- #

# 1. Create Users Table for authentication
CREATE TABLE IF NOT EXISTS `Users` (
    `user_id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'student') DEFAULT 'student',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB;

# 2. Create Track Table (referenced by Application)
CREATE TABLE IF NOT EXISTS `Track` (
    `track_id` INTEGER NOT NULL AUTO_INCREMENT,
    `strand_course` VARCHAR(100),
    `previous_school_records` VARCHAR(255),
    `student_photo_url` VARCHAR(255),
    `enrollment_fee` DECIMAL(10, 2) DEFAULT 5000.00,
    PRIMARY KEY (`track_id`)
) ENGINE=InnoDB;

# 3. Create Application Table
CREATE TABLE IF NOT EXISTS `Application` (
    `lrn` VARCHAR(40) NOT NULL,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `age` INTEGER,
    `birthdate` DATE,
    `gender` ENUM('Male', 'Female', 'Other'),
    `guardian_name_contact` VARCHAR(150),
    `track_id` INTEGER NOT NULL,
    `enrollment_fee` DECIMAL(10, 2) DEFAULT 5000.00,
    `discount_percent` DECIMAL(5, 2) DEFAULT 0.00,
    `total_amount` DECIMAL(10, 2),
    `payment_status` ENUM('Pending', 'Paid', 'Partial') DEFAULT 'Pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`lrn`),
    INDEX (`track_id`),
    CONSTRAINT `FK_Track_Application` 
        FOREIGN KEY (`track_id`) REFERENCES `Track` (`track_id`) 
        ON DELETE CASCADE
) ENGINE=InnoDB;

# 4. Create Document Table (Linked to Application via LRN)
CREATE TABLE IF NOT EXISTS `Document` (
    `document_id` INTEGER NOT NULL AUTO_INCREMENT,
    `lrn` VARCHAR(40) NOT NULL,
    `birth_certificate_url` VARCHAR(255),
    `diploma_url` VARCHAR(255),
    `good_moral_url` VARCHAR(255),
    `report_card_url` VARCHAR(255),
    PRIMARY KEY (`document_id`),
    CONSTRAINT `FK_Application_Document` 
        FOREIGN KEY (`lrn`) REFERENCES `Application` (`lrn`) 
        ON DELETE CASCADE
) ENGINE=InnoDB;

# Insert default admin user (password: admin123)
INSERT INTO `Users` (`username`, `password`, `role`) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

# Insert sample tracks with pricing
INSERT INTO `Track` (`strand_course`, `enrollment_fee`) VALUES
('STEM (Science, Technology, Engineering, Mathematics)', 6000.00),
('ABM (Accountancy, Business, Management)', 5500.00),
('HUMSS (Humanities and Social Sciences)', 5000.00),
('GAS (General Academic Strand)', 5000.00),
('TVL-ICT (Technical-Vocational-Livelihood)', 5500.00);
