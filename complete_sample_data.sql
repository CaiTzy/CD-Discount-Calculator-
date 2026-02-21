-- ======================================================================
-- Complete Sample Data for Smart Online Enrollment System
-- ======================================================================
-- This file contains comprehensive sample data for all tables
-- Run this AFTER importing improved_schema.sql
-- ======================================================================

USE enrollment_system;

-- ======================================================================
-- 1. USERS TABLE - System Users with Different Roles
-- ======================================================================

-- Clear existing users (optional - for clean setup)
-- DELETE FROM Users;

INSERT INTO `Users` (`username`, `password_hash`, `email`, `full_name`, `role`, `is_active`, `last_login`) VALUES
-- Admin user (password: admin123)
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@school.edu', 'System Administrator', 'Admin', TRUE, '2026-02-20 10:30:00'),

-- Staff users (password: staff123)
('staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'maria.santos@school.edu', 'Maria Santos', 'Staff', TRUE, '2026-02-20 09:15:00'),
('staff2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'juan.dela.cruz@school.edu', 'Juan Dela Cruz', 'Staff', TRUE, '2026-02-19 14:20:00'),

-- Registrar (password: registrar123)
('registrar', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'registrar@school.edu', 'Ana Reyes', 'Registrar', TRUE, '2026-02-20 08:00:00'),

-- Principal (password: principal123)
('principal', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'principal@school.edu', 'Dr. Roberto Garcia', 'Principal', TRUE, '2026-02-18 16:45:00'),

-- Teacher (password: teacher123)
('teacher1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher@school.edu', 'Elena Cruz', 'Teacher', TRUE, NULL);

-- ======================================================================
-- 2. TRACK TABLE - Already populated by improved_schema.sql
-- ======================================================================
-- STEM-01, ABM-01, HUMSS-01, GAS-01, TVL-ICT-01 should already exist

-- ======================================================================
-- 3. APPLICATION TABLE - Sample Student Applications
-- ======================================================================

INSERT INTO `Application` (
    `lrn`, `first_name`, `middle_name`, `last_name`, `suffix`, 
    `email`, `phone_number`, `address`, `city`, `province`, `zip_code`,
    `age`, `birthdate`, `gender`, `nationality`,
    `guardian_name`, `guardian_contact`, `guardian_email`, `guardian_relationship`,
    `track_id`, `application_status`, `application_date`, `enrollment_date`,
    `previous_school`, `year_graduated`, `gwa`
) VALUES
-- Student 1 - STEM, Enrolled
('135790246801234', 'Juan', 'Santos', 'Dela Cruz', 'Jr.', 
'juan.delacruz@email.com', '+639171234567', '123 Rizal Street, Brgy. San Jose', 'Manila', 'Metro Manila', '1000',
16, '2009-05-15', 'Male', 'Filipino',
'Maria Dela Cruz', '+639189876543', 'maria.dc@email.com', 'Mother',
1, 'Enrolled', '2026-01-15', '2026-02-01',
'San Jose High School', 2025, 1.75),

-- Student 2 - ABM, Enrolled
('246801357902468', 'Maria', 'Santos', 'Garcia', NULL,
'maria.garcia@email.com', '+639182345678', '456 Bonifacio Avenue, Brgy. Poblacion', 'Quezon City', 'Metro Manila', '1100',
17, '2008-08-22', 'Female', 'Filipino',
'Pedro Garcia', '+639193456789', 'pedro.g@email.com', 'Father',
2, 'Enrolled', '2026-01-18', '2026-02-03',
'Quezon Memorial High School', 2025, 1.50),

-- Student 3 - HUMSS, Approved
('357902468013579', 'Jose', 'Rizal', 'Mercado', NULL,
'jose.mercado@email.com', '+639173456789', '789 Luna Street, Brgy. Centro', 'Caloocan', 'Metro Manila', '1400',
16, '2009-11-30', 'Male', 'Filipino',
'Ana Mercado', '+639184567890', 'ana.m@email.com', 'Mother',
3, 'Approved', '2026-01-20', NULL,
'Caloocan Science High School', 2025, 1.85),

-- Student 4 - GAS, Approved
('468013579024680', 'Anna', 'Marie', 'Lopez', NULL,
'anna.lopez@email.com', '+639174567890', '321 Del Pilar Street, Brgy. San Antonio', 'Pasig', 'Metro Manila', '1600',
17, '2008-03-10', 'Female', 'Filipino',
'Roberto Lopez', '+639185678901', 'roberto.l@email.com', 'Father',
4, 'Approved', '2026-01-22', NULL,
'Pasig Catholic School', 2025, 2.00),

-- Student 5 - TVL-ICT, Enrolled
('579024680135790', 'Carlos', 'Miguel', 'Ramos', NULL,
'carlos.ramos@email.com', '+639175678901', '654 Mabini Avenue, Brgy. Libis', 'Makati', 'Metro Manila', '1200',
18, '2007-12-05', 'Male', 'Filipino',
'Elena Ramos', '+639186789012', 'elena.r@email.com', 'Mother',
5, 'Enrolled', '2026-01-25', '2026-02-05',
'Makati Technical High School', 2025, 2.25),

-- Student 6 - STEM, Under Review
('680135790246801', 'Sofia', 'Isabel', 'Torres', NULL,
'sofia.torres@email.com', '+639176789012', '987 Aguinaldo Highway, Brgy. San Miguel', 'Taguig', 'Metro Manila', '1630',
16, '2009-07-18', 'Female', 'Filipino',
'Luis Torres', '+639187890123', 'luis.t@email.com', 'Father',
1, 'Under Review', '2026-02-01', NULL,
'Taguig Science High School', 2025, 1.60),

-- Student 7 - ABM, Under Review
('790246801357902', 'Miguel', 'Angelo', 'Reyes', NULL,
'miguel.reyes@email.com', '+639177890123', '147 Roxas Boulevard, Brgy. Baclaran', 'Parañaque', 'Metro Manila', '1700',
17, '2008-09-25', 'Male', 'Filipino',
'Carmen Reyes', '+639188901234', 'carmen.r@email.com', 'Mother',
2, 'Under Review', '2026-02-03', NULL,
'Parañaque National High School', 2025, 1.90),

-- Student 8 - HUMSS, Pending
('801357902468013', 'Isabella', 'Marie', 'Cruz', NULL,
'isabella.cruz@email.com', '+639178901234', '258 Macapagal Avenue, Brgy. Tambo', 'Las Piñas', 'Metro Manila', '1740',
16, '2009-04-12', 'Female', 'Filipino',
'Fernando Cruz', '+639189012345', 'fernando.c@email.com', 'Father',
3, 'Pending', '2026-02-08', NULL,
'Las Piñas East National High School', 2025, 2.10),

-- Student 9 - GAS, Pending
('912468035790246', 'Gabriel', 'Antonio', 'Villanueva', NULL,
'gabriel.v@email.com', '+639179012345', '369 España Boulevard, Brgy. Sampaloc', 'Manila', 'Metro Manila', '1008',
17, '2008-06-30', 'Male', 'Filipino',
'Rosa Villanueva', '+639190123456', 'rosa.v@email.com', 'Mother',
4, 'Pending', '2026-02-10', NULL,
'Sampaloc High School', 2025, 2.35),

-- Student 10 - TVL-ICT, Enrolled
('023579468013579', 'Patricia', 'Grace', 'Mendoza', NULL,
'patricia.mendoza@email.com', '+639180123456', '741 Quirino Avenue, Brgy. Malate', 'Manila', 'Metro Manila', '1004',
18, '2007-10-08', 'Female', 'Filipino',
'Mario Mendoza', '+639191234567', 'mario.m@email.com', 'Father',
5, 'Enrolled', '2026-02-12', '2026-02-15',
'Malate Catholic School', 2025, 1.95),

-- Student 11 - STEM, Rejected
('134680257913579', 'Ricardo', 'Luis', 'Aquino', NULL,
'ricardo.aquino@email.com', '+639181234567', '852 Taft Avenue, Brgy. Ermita', 'Manila', 'Metro Manila', '1000',
16, '2009-01-20', 'Male', 'Filipino',
'Teresa Aquino', '+639192345678', 'teresa.a@email.com', 'Mother',
1, 'Rejected', '2026-01-10', NULL,
'Ermita High School', 2025, 3.50),

-- Student 12 - ABM, Enrolled
('245791357902468', 'Gabriela', 'Sofia', 'Santiago', NULL,
'gabriela.santiago@email.com', '+639182345679', '963 P. Burgos Street, Brgy. Paco', 'Manila', 'Metro Manila', '1007',
17, '2008-11-14', 'Female', 'Filipino',
'Antonio Santiago', '+639193456780', 'antonio.s@email.com', 'Father',
2, 'Enrolled', '2026-01-28', '2026-02-08',
'Paco Catholic School', 2025, 1.65),

-- Student 13 - HUMSS, Withdrawn
('356802479135791', 'Daniel', 'Jose', 'Bautista', NULL,
'daniel.bautista@email.com', '+639183456780', '159 Legarda Street, Brgy. Santa Cruz', 'Manila', 'Metro Manila', '1003',
16, '2009-02-28', 'Male', 'Filipino',
'Isabel Bautista', '+639194567891', 'isabel.b@email.com', 'Mother',
3, 'Withdrawn', '2026-01-12', NULL,
'Santa Cruz High School', 2025, 2.15),

-- Student 14 - STEM, Enrolled
('467913580246802', 'Angelica', 'Rose', 'Morales', NULL,
'angelica.morales@email.com', '+639184567891', '357 Blumentritt Road, Brgy. San Nicolas', 'Manila', 'Metro Manila', '1010',
16, '2009-09-05', 'Female', 'Filipino',
'Manuel Morales', '+639195678902', 'manuel.m@email.com', 'Father',
1, 'Enrolled', '2026-02-05', '2026-02-18',
'San Nicolas High School', 2025, 1.55),

-- Student 15 - TVL-ICT, Approved
('578024691357913', 'Francisco', 'Miguel', 'Fernandez', NULL,
'francisco.f@email.com', '+639185678902', '753 Quirino Highway, Brgy. Novaliches', 'Quezon City', 'Metro Manila', '1123',
18, '2007-07-22', 'Male', 'Filipino',
'Lourdes Fernandez', '+639196789013', 'lourdes.f@email.com', 'Mother',
5, 'Approved', '2026-02-15', NULL,
'Novaliches Technical School', 2025, 2.05);

-- ======================================================================
-- 4. DOCUMENT TABLE - Sample Document Records
-- ======================================================================

INSERT INTO `Document` (
    `lrn`, 
    `birth_certificate_url`, `diploma_url`, `good_moral_url`, `report_card_url`, 
    `photo_2x2_url`, `transfer_credentials_url`,
    `verification_status`, `verified_by`, `verified_at`, `verification_notes`
) VALUES
-- Student 1 - All documents verified
('135790246801234', 
'135790246801234/birth_cert_001.pdf', '135790246801234/diploma_001.pdf', '135790246801234/good_moral_001.pdf', '135790246801234/report_card_001.pdf',
'135790246801234/photo_001.jpg', NULL,
'Verified', 'Maria Santos', '2026-01-18 14:30:00', 'All documents complete and authentic'),

-- Student 2 - All documents verified
('246801357902468',
'246801357902468/birth_cert_002.pdf', '246801357902468/diploma_002.pdf', '246801357902468/good_moral_002.pdf', '246801357902468/report_card_002.pdf',
'246801357902468/photo_002.jpg', NULL,
'Verified', 'Maria Santos', '2026-01-20 10:15:00', 'Documents verified successfully'),

-- Student 3 - Pending verification
('357902468013579',
'357902468013579/birth_cert_003.pdf', '357902468013579/diploma_003.pdf', '357902468013579/good_moral_003.pdf', '357902468013579/report_card_003.pdf',
'357902468013579/photo_003.jpg', NULL,
'Pending', NULL, NULL, NULL),

-- Student 4 - Verified
('468013579024680',
'468013579024680/birth_cert_004.pdf', '468013579024680/diploma_004.pdf', '468013579024680/good_moral_004.pdf', '468013579024680/report_card_004.pdf',
'468013579024680/photo_004.jpg', NULL,
'Verified', 'Juan Dela Cruz', '2026-01-25 09:45:00', 'All requirements met'),

-- Student 5 - Verified
('579024680135790',
'579024680135790/birth_cert_005.pdf', '579024680135790/diploma_005.pdf', '579024680135790/good_moral_005.pdf', '579024680135790/report_card_005.pdf',
'579024680135790/photo_005.jpg', '579024680135790/transfer_cred_005.pdf',
'Verified', 'Ana Reyes', '2026-01-28 11:20:00', 'Transfer student - all documents complete'),

-- Student 6 - Pending
('680135790246801',
'680135790246801/birth_cert_006.pdf', '680135790246801/diploma_006.pdf', '680135790246801/good_moral_006.pdf', '680135790246801/report_card_006.pdf',
'680135790246801/photo_006.jpg', NULL,
'Pending', NULL, NULL, NULL),

-- Student 7 - Incomplete
('790246801357902',
'790246801357902/birth_cert_007.pdf', NULL, '790246801357902/good_moral_007.pdf', '790246801357902/report_card_007.pdf',
'790246801357902/photo_007.jpg', NULL,
'Incomplete', 'Maria Santos', '2026-02-05 13:00:00', 'Missing diploma - requested to submit'),

-- Student 8 - Pending
('801357902468013',
'801357902468013/birth_cert_008.pdf', '801357902468013/diploma_008.pdf', '801357902468013/good_moral_008.pdf', '801357902468013/report_card_008.pdf',
'801357902468013/photo_008.jpg', NULL,
'Pending', NULL, NULL, NULL),

-- Student 9 - Pending
('912468035790246',
'912468035790246/birth_cert_009.pdf', '912468035790246/diploma_009.pdf', '912468035790246/good_moral_009.pdf', NULL,
'912468035790246/photo_009.jpg', NULL,
'Pending', NULL, NULL, NULL),

-- Student 10 - Verified
('023579468013579',
'023579468013579/birth_cert_010.pdf', '023579468013579/diploma_010.pdf', '023579468013579/good_moral_010.pdf', '023579468013579/report_card_010.pdf',
'023579468013579/photo_010.jpg', NULL,
'Verified', 'Juan Dela Cruz', '2026-02-14 15:30:00', 'Complete documentation'),

-- Student 11 - Rejected
('134680257913579',
'134680257913579/birth_cert_011.pdf', '134680257913579/diploma_011.pdf', '134680257913579/good_moral_011.pdf', '134680257913579/report_card_011.pdf',
'134680257913579/photo_011.jpg', NULL,
'Rejected', 'Ana Reyes', '2026-01-12 10:00:00', 'Documents do not meet minimum requirements'),

-- Student 12 - Verified
('245791357902468',
'245791357902468/birth_cert_012.pdf', '245791357902468/diploma_012.pdf', '245791357902468/good_moral_012.pdf', '245791357902468/report_card_012.pdf',
'245791357902468/photo_012.jpg', NULL,
'Verified', 'Maria Santos', '2026-02-01 14:00:00', 'All clear'),

-- Student 13 - Verified (but withdrawn)
('356802479135791',
'356802479135791/birth_cert_013.pdf', '356802479135791/diploma_013.pdf', '356802479135791/good_moral_013.pdf', '356802479135791/report_card_013.pdf',
'356802479135791/photo_013.jpg', NULL,
'Verified', 'Juan Dela Cruz', '2026-01-15 11:30:00', 'Student withdrew after verification'),

-- Student 14 - Verified
('467913580246802',
'467913580246802/birth_cert_014.pdf', '467913580246802/diploma_014.pdf', '467913580246802/good_moral_014.pdf', '467913580246802/report_card_014.pdf',
'467913580246802/photo_014.jpg', NULL,
'Verified', 'Ana Reyes', '2026-02-10 09:00:00', 'Documents verified'),

-- Student 15 - Pending
('578024691357913',
'578024691357913/birth_cert_015.pdf', '578024691357913/diploma_015.pdf', '578024691357913/good_moral_015.pdf', '578024691357913/report_card_015.pdf',
'578024691357913/photo_015.jpg', NULL,
'Pending', NULL, NULL, NULL);

-- ======================================================================
-- 5. ENROLLMENT_HISTORY TABLE - Sample Status Change History
-- ======================================================================

INSERT INTO `Enrollment_History` (
    `lrn`, `previous_status`, `new_status`, `changed_by`, `change_reason`, `changed_at`
) VALUES
-- Student 1 history
('135790246801234', NULL, 'Pending', 'System', 'Initial application submitted', '2026-01-15 10:00:00'),
('135790246801234', 'Pending', 'Under Review', 'Maria Santos', 'Documents received and under review', '2026-01-16 14:30:00'),
('135790246801234', 'Under Review', 'Approved', 'Ana Reyes', 'All requirements met, application approved', '2026-01-18 15:00:00'),
('135790246801234', 'Approved', 'Enrolled', 'Ana Reyes', 'Student officially enrolled', '2026-02-01 09:00:00'),

-- Student 2 history
('246801357902468', NULL, 'Pending', 'System', 'Initial application submitted', '2026-01-18 11:30:00'),
('246801357902468', 'Pending', 'Under Review', 'Juan Dela Cruz', 'Under review', '2026-01-19 10:00:00'),
('246801357902468', 'Under Review', 'Approved', 'Ana Reyes', 'Approved for enrollment', '2026-01-20 14:00:00'),
('246801357902468', 'Approved', 'Enrolled', 'Ana Reyes', 'Enrolled successfully', '2026-02-03 10:30:00'),

-- Student 3 history
('357902468013579', NULL, 'Pending', 'System', 'Initial application submitted', '2026-01-20 09:00:00'),
('357902468013579', 'Pending', 'Under Review', 'Maria Santos', 'Documents under review', '2026-01-22 11:00:00'),
('357902468013579', 'Under Review', 'Approved', 'Ana Reyes', 'Application approved', '2026-01-25 13:30:00'),

-- Student 5 history
('579024680135790', NULL, 'Pending', 'System', 'Initial application submitted', '2026-01-25 14:00:00'),
('579024680135790', 'Pending', 'Approved', 'Ana Reyes', 'Fast-tracked approval', '2026-01-27 10:00:00'),
('579024680135790', 'Approved', 'Enrolled', 'Ana Reyes', 'Enrolled in TVL-ICT track', '2026-02-05 08:30:00'),

-- Student 6 history
('680135790246801', NULL, 'Pending', 'System', 'Initial application submitted', '2026-02-01 10:30:00'),
('680135790246801', 'Pending', 'Under Review', 'Juan Dela Cruz', 'Review in progress', '2026-02-02 09:00:00'),

-- Student 7 history
('790246801357902', NULL, 'Pending', 'System', 'Initial application submitted', '2026-02-03 11:00:00'),
('790246801357902', 'Pending', 'Under Review', 'Maria Santos', 'Documents incomplete - under review', '2026-02-04 14:00:00'),

-- Student 10 history
('023579468013579', NULL, 'Pending', 'System', 'Initial application submitted', '2026-02-12 09:30:00'),
('023579468013579', 'Pending', 'Approved', 'Ana Reyes', 'Approved', '2026-02-14 16:00:00'),
('023579468013579', 'Approved', 'Enrolled', 'Ana Reyes', 'Enrolled successfully', '2026-02-15 10:00:00'),

-- Student 11 history (rejected)
('134680257913579', NULL, 'Pending', 'System', 'Initial application submitted', '2026-01-10 08:00:00'),
('134680257913579', 'Pending', 'Under Review', 'Maria Santos', 'Under review', '2026-01-11 10:00:00'),
('134680257913579', 'Under Review', 'Rejected', 'Dr. Roberto Garcia', 'GWA does not meet minimum requirements', '2026-01-12 14:00:00'),

-- Student 12 history
('245791357902468', NULL, 'Pending', 'System', 'Initial application submitted', '2026-01-28 10:00:00'),
('245791357902468', 'Pending', 'Approved', 'Ana Reyes', 'Approved', '2026-01-30 11:00:00'),
('245791357902468', 'Approved', 'Enrolled', 'Ana Reyes', 'Enrolled', '2026-02-08 09:00:00'),

-- Student 13 history (withdrawn)
('356802479135791', NULL, 'Pending', 'System', 'Initial application submitted', '2026-01-12 09:00:00'),
('356802479135791', 'Pending', 'Approved', 'Ana Reyes', 'Approved', '2026-01-15 14:00:00'),
('356802479135791', 'Approved', 'Withdrawn', 'Juan Dela Cruz', 'Student requested withdrawal - moving to another school', '2026-01-20 10:00:00'),

-- Student 14 history
('467913580246802', NULL, 'Pending', 'System', 'Initial application submitted', '2026-02-05 11:00:00'),
('467913580246802', 'Pending', 'Approved', 'Ana Reyes', 'Approved', '2026-02-10 13:00:00'),
('467913580246802', 'Approved', 'Enrolled', 'Ana Reyes', 'Enrolled', '2026-02-18 08:00:00');

-- ======================================================================
-- Data Import Summary
-- ======================================================================
-- Users: 6 (1 Admin, 2 Staff, 1 Registrar, 1 Principal, 1 Teacher)
-- Tracks: 5 (from improved_schema.sql)
-- Applications: 15 students with various statuses
-- Documents: 15 document records with different verification statuses
-- Enrollment History: 34 status change records
-- ======================================================================

-- Query to verify data
SELECT 'Users' as Table_Name, COUNT(*) as Record_Count FROM Users
UNION ALL
SELECT 'Tracks', COUNT(*) FROM Track
UNION ALL
SELECT 'Applications', COUNT(*) FROM Application
UNION ALL
SELECT 'Documents', COUNT(*) FROM Document
UNION ALL
SELECT 'Enrollment History', COUNT(*) FROM Enrollment_History;

-- Query to see enrollment status distribution
SELECT application_status, COUNT(*) as count 
FROM Application 
GROUP BY application_status 
ORDER BY count DESC;

-- Query to see track enrollment
SELECT 
    t.track_code,
    t.strand_course,
    COUNT(a.lrn) as enrolled_count,
    t.capacity
FROM Track t
LEFT JOIN Application a ON t.track_id = a.track_id AND a.application_status = 'Enrolled'
GROUP BY t.track_id
ORDER BY t.track_code;

-- ======================================================================
-- End of Sample Data
-- ======================================================================
