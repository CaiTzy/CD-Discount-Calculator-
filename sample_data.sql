-- ---------------------------------------------------------------------- 
-- Sample Data for Smart Online Enrollment System
-- This file contains sample student applications for testing
-- ---------------------------------------------------------------------- 

USE `enrollment_system`;

-- Insert sample student applications
INSERT INTO Application (lrn, first_name, last_name, address, birthdate, gender, guardian_name_contact, track_id, status) VALUES
('100123456789', 'Juan', 'Dela Cruz', '123 Rizal Street, Manila', '2006-03-15', 'Male', 'Maria Dela Cruz - 09171234567', 1, 'Enrolled'),
('100123456790', 'Maria', 'Santos', '456 Bonifacio Ave, Quezon City', '2006-07-22', 'Female', 'Jose Santos - 09181234568', 2, 'Approved'),
('100123456791', 'Pedro', 'Reyes', '789 Mabini Street, Makati', '2007-01-10', 'Male', 'Carmen Reyes - 09191234569', 1, 'Pending'),
('100123456792', 'Ana', 'Garcia', '321 Luna Street, Pasig', '2006-11-05', 'Female', 'Roberto Garcia - 09201234570', 3, 'Under Review'),
('100123456793', 'Jose', 'Ramos', '654 Aguinaldo Road, Caloocan', '2007-04-18', 'Male', 'Elena Ramos - 09211234571', 4, 'Enrolled'),
('100123456794', 'Sofia', 'Torres', '987 Burgos Street, Pasay', '2006-09-30', 'Female', 'Miguel Torres - 09221234572', 5, 'Approved'),
('100123456795', 'Miguel', 'Flores', '147 Lapu-Lapu Ave, Taguig', '2007-02-14', 'Male', 'Linda Flores - 09231234573', 1, 'Pending'),
('100123456796', 'Carmen', 'Mendoza', '258 Magallanes Street, Paranaque', '2006-12-25', 'Female', 'Carlos Mendoza - 09241234574', 2, 'Enrolled'),
('100123456797', 'Roberto', 'Cruz', '369 Magsaysay Blvd, Manila', '2007-05-08', 'Male', 'Rosa Cruz - 09251234575', 3, 'Under Review'),
('100123456798', 'Elena', 'Rivera', '741 Osmeña Highway, Muntinlupa', '2006-08-20', 'Female', 'Antonio Rivera - 09261234576', 1, 'Approved'),
('100123456799', 'Carlos', 'Gonzales', '852 Roxas Blvd, Manila', '2007-03-12', 'Male', 'Teresa Gonzales - 09271234577', 4, 'Pending'),
('100123456800', 'Teresa', 'Hernandez', '963 Quezon Ave, Quezon City', '2006-10-28', 'Female', 'Ramon Hernandez - 09281234578', 5, 'Enrolled'),
('100123456801', 'Ramon', 'Diaz', '159 EDSA, Mandaluyong', '2007-06-15', 'Male', 'Gloria Diaz - 09291234579', 2, 'Rejected'),
('100123456802', 'Gloria', 'Martinez', '357 Taft Ave, Manila', '2006-04-03', 'Female', 'Fernando Martinez - 09301234580', 3, 'Withdrawn'),
('100123456803', 'Fernando', 'Lopez', '486 España Blvd, Sampaloc', '2007-01-25', 'Male', 'Luz Lopez - 09311234581', 1, 'Enrolled');

-- Insert sample documents for enrolled students
INSERT INTO Document (lrn, birth_certificate_url, diploma_url, good_moral_url, report_card_url) VALUES
('100123456789', 'birth_certificate_1708494000.pdf', 'diploma_1708494000.pdf', 'good_moral_1708494000.pdf', 'report_card_1708494000.pdf'),
('100123456790', 'birth_certificate_1708494100.pdf', 'diploma_1708494100.pdf', 'good_moral_1708494100.pdf', 'report_card_1708494100.pdf'),
('100123456793', 'birth_certificate_1708494200.pdf', 'diploma_1708494200.pdf', 'good_moral_1708494200.pdf', 'report_card_1708494200.pdf'),
('100123456794', 'birth_certificate_1708494300.pdf', 'diploma_1708494300.pdf', 'good_moral_1708494300.pdf', 'report_card_1708494300.pdf'),
('100123456796', 'birth_certificate_1708494400.pdf', 'diploma_1708494400.pdf', 'good_moral_1708494400.pdf', 'report_card_1708494400.pdf'),
('100123456798', 'birth_certificate_1708494500.pdf', 'diploma_1708494500.pdf', 'good_moral_1708494500.pdf', 'report_card_1708494500.pdf'),
('100123456800', 'birth_certificate_1708494600.pdf', 'diploma_1708494600.pdf', 'good_moral_1708494600.pdf', 'report_card_1708494600.pdf'),
('100123456803', 'birth_certificate_1708494700.pdf', 'diploma_1708494700.pdf', 'good_moral_1708494700.pdf', 'report_card_1708494700.pdf');

-- Insert enrollment history for sample applications
INSERT INTO Enrollment_History (lrn, action, old_status, new_status, changed_by, notes) VALUES
('100123456789', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456789', 'STATUS_UPDATE', 'Pending', 'Under Review', 1, 'Documents verified'),
('100123456789', 'STATUS_UPDATE', 'Under Review', 'Approved', 1, 'Application approved'),
('100123456789', 'STATUS_UPDATE', 'Approved', 'Enrolled', 1, 'Student enrolled for SY 2024-2025'),

('100123456790', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456790', 'STATUS_UPDATE', 'Pending', 'Under Review', 1, 'Under review'),
('100123456790', 'STATUS_UPDATE', 'Under Review', 'Approved', 1, 'Approved for enrollment'),

('100123456791', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),

('100123456792', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456792', 'STATUS_UPDATE', 'Pending', 'Under Review', 1, 'Reviewing documents'),

('100123456793', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456793', 'STATUS_UPDATE', 'Pending', 'Approved', 1, 'Fast-tracked approval'),
('100123456793', 'STATUS_UPDATE', 'Approved', 'Enrolled', 1, 'Student enrolled'),

('100123456794', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456794', 'STATUS_UPDATE', 'Pending', 'Approved', 1, 'Approved'),

('100123456795', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),

('100123456796', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456796', 'STATUS_UPDATE', 'Pending', 'Under Review', 1, 'Under review'),
('100123456796', 'STATUS_UPDATE', 'Under Review', 'Approved', 1, 'Approved'),
('100123456796', 'STATUS_UPDATE', 'Approved', 'Enrolled', 1, 'Enrolled'),

('100123456797', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456797', 'STATUS_UPDATE', 'Pending', 'Under Review', 1, 'Under review'),

('100123456798', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456798', 'STATUS_UPDATE', 'Pending', 'Approved', 1, 'Approved'),

('100123456799', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),

('100123456800', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456800', 'STATUS_UPDATE', 'Pending', 'Approved', 1, 'Approved'),
('100123456800', 'STATUS_UPDATE', 'Approved', 'Enrolled', 1, 'Enrolled'),

('100123456801', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456801', 'STATUS_UPDATE', 'Pending', 'Under Review', 1, 'Under review'),
('100123456801', 'STATUS_UPDATE', 'Under Review', 'Rejected', 1, 'Incomplete documents'),

('100123456802', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456802', 'STATUS_UPDATE', 'Pending', 'Approved', 1, 'Approved'),
('100123456802', 'STATUS_UPDATE', 'Approved', 'Withdrawn', 1, 'Student transferred to another school'),

('100123456803', 'NEW_APPLICATION', NULL, 'Pending', 1, 'Application submitted'),
('100123456803', 'STATUS_UPDATE', 'Pending', 'Enrolled', 1, 'Direct enrollment');

-- Verify data was inserted
SELECT 'Applications inserted:' as Info, COUNT(*) as Count FROM Application
UNION ALL
SELECT 'Documents inserted:', COUNT(*) FROM Document
UNION ALL
SELECT 'History records:', COUNT(*) FROM Enrollment_History;

-- Show status distribution
SELECT status, COUNT(*) as count 
FROM Application 
GROUP BY status 
ORDER BY count DESC;
