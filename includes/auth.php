<?php
require_once 'db.php';

// Function to register user
function registerUser($name, $email, $password, $phone) {
    global $conn;
    
    // Check if email already exists
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        return false;
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $sql = "INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $phone);
    
    return $stmt->execute();
}

// Function to login user
function loginUser($email, $password) {
    global $conn;
    
    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }
    
    return false;
}

// Function to update user profile
function updateUserProfile($user_id, $name, $phone) {
    global $conn;
    
    $sql = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $phone, $user_id);
    
    return $stmt->execute();
}

// Function to change password
function changePassword($user_id, $current_password, $new_password) {
    global $conn;
    
    // Verify current password
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($current_password, $user['password'])) {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $hashed_password, $user_id);
            return $update_stmt->execute();
        }
    }
    
    return false;
}

// Function to get user bookings
function getUserBookings($user_id) {
    global $conn;
    
    $sql = "SELECT * FROM bookings WHERE email = (SELECT email FROM users WHERE id = ?) ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    return $bookings;
}

// Handle login
if (isset($_POST['login'])) {
    $email = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $user = loginUser($email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        redirect('index.php');
    } else {
        $login_error = "Invalid email or password";
    }
}

// Handle registration
if (isset($_POST['register'])) {
    $name = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = sanitize($conn, $_POST['phone']);
    
    if ($password !== $confirm_password) {
        $register_error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $register_error = "Password must be at least 6 characters";
    } else {
        if (registerUser($name, $email, $password, $phone)) {
            $register_success = "Registration successful! Please login.";
        } else {
            $register_error = "Email already exists";
        }
    }
}

// Handle profile update
if (isset($_POST['update_profile'])) {
    $user_id = $_SESSION['user_id'];
    $name = sanitize($conn, $_POST['name']);
    $phone = sanitize($conn, $_POST['phone']);
    
    if (updateUserProfile($user_id, $name, $phone)) {
        $_SESSION['user_name'] = $name;
        $profile_success = "Profile updated successfully!";
    } else {
        $profile_error = "Failed to update profile";
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $password_error = "New passwords do not match";
    } elseif (strlen($new_password) < 6) {
        $password_error = "Password must be at least 6 characters";
    } else {
        if (changePassword($user_id, $current_password, $new_password)) {
            $password_success = "Password changed successfully!";
        } else {
            $password_error = "Current password is incorrect";
        }
    }
}
?>
