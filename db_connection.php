<?php
/**
 * Database Connection
 * Smart Online Enrollment System
 */

require_once 'config.php';

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");

/**
 * Execute a prepared statement with parameters
 */
function db_query($conn, $sql, $params = [], $types = '') {
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        return false;
    }
    
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    
    return $result;
}

/**
 * Get single row
 */
function db_fetch($conn, $sql, $params = [], $types = '') {
    $result = db_query($conn, $sql, $params, $types);
    return $result ? mysqli_fetch_assoc($result) : null;
}

/**
 * Get all rows
 */
function db_fetch_all($conn, $sql, $params = [], $types = '') {
    $result = db_query($conn, $sql, $params, $types);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}
