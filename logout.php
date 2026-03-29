<?php
session_start();
require_once 'includes/db.php';

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy all session data
session_destroy();

// Clear any remaining session data
if (isset($_SESSION)) {
    unset($_SESSION);
}

// Redirect to home page with success message
header("Location: index.php?logout=success");
exit();
?>
