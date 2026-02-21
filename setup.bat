@echo off
REM ======================================================================
REM Smart Online Enrollment System - Complete Setup Script (Windows)
REM ======================================================================
REM This script sets up the complete database with all sample data
REM ======================================================================

echo ==========================================
echo Smart Online Enrollment System Setup
echo ==========================================
echo.

SET DB_NAME=enrollment_system
SET DB_USER=root
SET DB_CHARSET=utf8mb4
SET DB_COLLATION=utf8mb4_unicode_ci

REM Check if MySQL is accessible
where mysql >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] MySQL is not installed or not in PATH
    echo Please install MySQL or add it to your system PATH
    pause
    exit /b 1
)

echo [OK] MySQL found

REM Prompt for MySQL password
echo.
echo Please enter your MySQL root password:
SET /P MYSQL_PASSWORD=

REM Test MySQL connection
mysql -u%DB_USER% -p%MYSQL_PASSWORD% -e "SELECT 1;" >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to connect to MySQL. Please check your password.
    pause
    exit /b 1
)

echo [OK] MySQL connection successful
echo.

REM Step 1: Create Database
echo [INFO] Step 1: Creating database '%DB_NAME%'...
mysql -u%DB_USER% -p%MYSQL_PASSWORD% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET %DB_CHARSET% COLLATE %DB_COLLATION%;" 2>nul

if %ERRORLEVEL% EQU 0 (
    echo [OK] Database '%DB_NAME%' created successfully
) else (
    echo [ERROR] Failed to create database
    pause
    exit /b 1
)

REM Step 2: Import Schema
echo [INFO] Step 2: Importing database schema...
if not exist "improved_schema.sql" (
    echo [ERROR] improved_schema.sql not found!
    pause
    exit /b 1
)

mysql -u%DB_USER% -p%MYSQL_PASSWORD% %DB_NAME% < improved_schema.sql 2>nul

if %ERRORLEVEL% EQU 0 (
    echo [OK] Database schema imported successfully
) else (
    echo [ERROR] Failed to import schema
    pause
    exit /b 1
)

REM Step 3: Import Sample Data
echo [INFO] Step 3: Importing sample data...
if not exist "complete_sample_data.sql" (
    echo [ERROR] complete_sample_data.sql not found!
    pause
    exit /b 1
)

mysql -u%DB_USER% -p%MYSQL_PASSWORD% %DB_NAME% < complete_sample_data.sql 2>nul

if %ERRORLEVEL% EQU 0 (
    echo [OK] Sample data imported successfully
) else (
    echo [ERROR] Failed to import sample data
    pause
    exit /b 1
)

REM Step 4: Verify Data
echo.
echo [INFO] Step 4: Verifying data...
echo.

mysql -u%DB_USER% -p%MYSQL_PASSWORD% %DB_NAME% -e "SELECT 'Users' as 'Table', COUNT(*) as 'Records' FROM Users UNION ALL SELECT 'Tracks', COUNT(*) FROM Track UNION ALL SELECT 'Applications', COUNT(*) FROM Application UNION ALL SELECT 'Documents', COUNT(*) FROM Document UNION ALL SELECT 'Enrollment History', COUNT(*) FROM Enrollment_History;" 2>nul

echo [OK] Data verification complete

REM Step 5: Create uploads directory
echo.
echo [INFO] Step 5: Setting up uploads directory...
if not exist "uploads" mkdir uploads

echo [OK] Uploads directory configured

REM Summary
echo.
echo ==========================================
echo Setup Complete!
echo ==========================================
echo.
echo Database: %DB_NAME%
echo Status: Ready to use
echo.
echo Default Login Credentials:
echo   Username: admin
echo   Password: admin123
echo.
echo Additional Users:
echo   staff1/staff123 (Staff)
echo   registrar/registrar123 (Registrar)
echo   principal/principal123 (Principal)
echo.
echo Sample Data:
echo   - 6 system users
echo   - 5 academic tracks
echo   - 15 student applications
echo   - Complete enrollment history
echo.
echo Access your system at:
echo   http://localhost/index.php
echo.
echo [IMPORTANT] Change default passwords after first login!
echo ==========================================
echo.
pause
