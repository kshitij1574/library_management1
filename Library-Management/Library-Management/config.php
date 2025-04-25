<?php
/**
 * Database Configuration File
 * 
 * This file contains the configuration settings for connecting to the MySQL database.
 * It also includes error handling and sets up the PDO connection with proper attributes.
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'library_management');
define('DB_USER', 'library_admin');
define('DB_PASS', 'secure_password_123');
define('DB_CHARSET', 'utf8mb4');

// Error reporting (for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set timezone
date_default_timezone_set('UTC');

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Create PDO instance
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Test connection
    $pdo->query("SELECT 1");
} catch (PDOException $e) {
    // Log error (in production you would log to a file)
    error_log("Database connection failed: " . $e->getMessage());
    
    // Display user-friendly message
    die("We're experiencing technical difficulties. Please try again later.");
}

/**
 * Helper function to execute prepared statements with error handling
 * 
 * @param PDO $pdo The PDO instance
 * @param string $sql The SQL query
 * @param array $params The parameters for the prepared statement
 * @return PDOStatement|false Returns the executed statement or false on failure
 */
function executeQuery($pdo, $sql, $params = []) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Query execution failed: " . $e->getMessage() . "\nSQL: $sql");
        return false;
    }
}

/**
 * Sanitize output to prevent XSS
 * 
 * @param string $data The data to sanitize
 * @return string The sanitized data
 */
function sanitizeOutput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Session configuration (if you add user authentication later)
// session_start();
// ini_set('session.cookie_httponly', 1);
// ini_set('session.cookie_secure', 1); // Enable if using HTTPS
// ini_set('session.use_strict_mode', 1);
?>