<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'photostudio_pro';

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Function to sanitize input
function sanitize($conn, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@photostudio.com';
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to display success message
function successMessage($message) {
    return "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                <i class='fas fa-check-circle'></i> $message
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

// Function to display error message
function errorMessage($message) {
    return "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <i class='fas fa-exclamation-circle'></i> $message
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}
?>
