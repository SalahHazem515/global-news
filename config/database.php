<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'yournewpassword');
define('DB_NAME', 'news_website');

function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER,'', DB_NAME,3306);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>