<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'aips_db');

// Flutterwave configuration
// Replace the fallback values with your live keys in production.
define('FLW_PUBLIC_KEY', getenv('FLW_PUBLIC_KEY') ?: 'FLWPUBK_TEST-xxxxxxxxxxxxxxxxxxxxx-X');
define('FLW_SECRET_KEY', getenv('FLW_SECRET_KEY') ?: 'FLWSECK_TEST-xxxxxxxxxxxxxxxxxxxxx-X');
define('FLW_ENCRYPTION_KEY', getenv('FLW_ENCRYPTION_KEY') ?: 'FLWSECK_TESTxxxxxxxx');

function db_connect()
{
    static $connection = null;

    if ($connection === null) {
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$connection) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        mysqli_set_charset($connection, 'utf8mb4');
    }

    return $connection;
}

function sanitize($value)
{
    $conn = db_connect();
    return mysqli_real_escape_string($conn, trim($value));
}
?>
