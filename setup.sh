#!/bin/bash
# ======================================================================
# Smart Online Enrollment System - Complete Setup Script
# ======================================================================
# This script sets up the complete database with all sample data
# ======================================================================

echo "=========================================="
echo "Smart Online Enrollment System Setup"
echo "=========================================="
echo ""

# Configuration
DB_NAME="enrollment_system"
DB_USER="root"
DB_CHARSET="utf8mb4"
DB_COLLATION="utf8mb4_unicode_ci"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored messages
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    print_error "MySQL is not installed or not in PATH"
    exit 1
fi

print_success "MySQL found"

# Prompt for MySQL password
echo ""
print_info "Please enter your MySQL root password:"
read -s MYSQL_PASSWORD
echo ""

# Test MySQL connection
if ! mysql -u"$DB_USER" -p"$MYSQL_PASSWORD" -e "SELECT 1;" &> /dev/null; then
    print_error "Failed to connect to MySQL. Please check your password."
    exit 1
fi

print_success "MySQL connection successful"
echo ""

# Step 1: Create Database
print_info "Step 1: Creating database '$DB_NAME'..."
mysql -u"$DB_USER" -p"$MYSQL_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET $DB_CHARSET COLLATE $DB_COLLATION;" 2>/dev/null

if [ $? -eq 0 ]; then
    print_success "Database '$DB_NAME' created successfully"
else
    print_error "Failed to create database"
    exit 1
fi

# Step 2: Import Schema
print_info "Step 2: Importing database schema..."
if [ ! -f "improved_schema.sql" ]; then
    print_error "improved_schema.sql not found!"
    exit 1
fi

mysql -u"$DB_USER" -p"$MYSQL_PASSWORD" "$DB_NAME" < improved_schema.sql 2>/dev/null

if [ $? -eq 0 ]; then
    print_success "Database schema imported successfully"
else
    print_error "Failed to import schema"
    exit 1
fi

# Step 3: Import Sample Data
print_info "Step 3: Importing sample data..."
if [ ! -f "complete_sample_data.sql" ]; then
    print_error "complete_sample_data.sql not found!"
    exit 1
fi

mysql -u"$DB_USER" -p"$MYSQL_PASSWORD" "$DB_NAME" < complete_sample_data.sql 2>/dev/null

if [ $? -eq 0 ]; then
    print_success "Sample data imported successfully"
else
    print_error "Failed to import sample data"
    exit 1
fi

# Step 4: Verify Data
echo ""
print_info "Step 4: Verifying data..."
echo ""

# Get record counts
mysql -u"$DB_USER" -p"$MYSQL_PASSWORD" "$DB_NAME" -e "
SELECT 'Users' as 'Table', COUNT(*) as 'Records' FROM Users
UNION ALL
SELECT 'Tracks', COUNT(*) FROM Track
UNION ALL
SELECT 'Applications', COUNT(*) FROM Application
UNION ALL
SELECT 'Documents', COUNT(*) FROM Document
UNION ALL
SELECT 'Enrollment History', COUNT(*) FROM Enrollment_History;
" 2>/dev/null

print_success "Data verification complete"

# Step 5: Create uploads directory
echo ""
print_info "Step 5: Setting up uploads directory..."
mkdir -p uploads
chmod 755 uploads

print_success "Uploads directory configured"

# Summary
echo ""
echo "=========================================="
echo "Setup Complete!"
echo "=========================================="
echo ""
echo "Database: $DB_NAME"
echo "Status: Ready to use"
echo ""
echo "Default Login Credentials:"
echo "  Username: admin"
echo "  Password: admin123"
echo ""
echo "Additional Users:"
echo "  staff1/staff123 (Staff)"
echo "  registrar/registrar123 (Registrar)"
echo "  principal/principal123 (Principal)"
echo ""
echo "Sample Data:"
echo "  - 6 system users"
echo "  - 5 academic tracks"
echo "  - 15 student applications"
echo "  - Complete enrollment history"
echo ""
echo "Access your system at:"
echo "  http://localhost/index.php"
echo ""
print_info "IMPORTANT: Change default passwords after first login!"
echo "=========================================="
