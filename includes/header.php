<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanjay PhotoStudio - Professional Photography Services</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body>
    <?php 
    $is_admin_page = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false || strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
    $base_path = $is_admin_page ? '' : 'admin/';
    if ($is_admin_page): 
    ?>
    <style>
        /* Navigation - Admin Panel Style */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border-bottom: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.5);
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 99999 !important;
            padding: 1rem 0;
            transition: none !important;
            min-height: 80px !important;
            width: 100vw !important;
            transform: translate3d(0, 0, 0) !important;
            will-change: transform !important;
            backface-visibility: hidden !important;
        }
        
        /* Navbar Brand Enhancement */
        .navbar-brand {
            font-size: 1.4rem !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            text-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }
        
        .navbar-brand i {
            font-size: 1.6rem;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        
        /* Navbar Links Enhancement - Original Style */
        .navbar-nav {
            margin-left: 0;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            font-size: 0.85rem;
            border-radius: 8px;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .navbar-nav .nav-link:hover::before {
            left: 100%;
        }
        
        .navbar-nav .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #ffffff, #f0f0f0);
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover::after {
            width: 80%;
        }
        
        /* Dropdown Enhancement */
        .dropdown-menu {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            backdrop-filter: blur(10px);
            margin-top: 1rem;
            animation: fadeInDown 0.3s ease;
        }
        
        .dropdown-item {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0.2rem 0.5rem;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            border-radius: 10px;
            transform: translateX(5px);
        }
        
        /* Active Link Enhancement */
        .navbar-nav .nav-link.active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        /* Navbar Toggler Enhancement */
        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.5) !important;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-toggler:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255, 255, 255, 0.9%29' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        
        /* Navbar Container Enhancement */
        .navbar .container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 2rem;
        }
        
        /* Navbar Animation */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        /* Content positioning fix */
        body {
            padding-top: 100px !important;
        }
        
        /* Disable scroll effects for admin */
        .navbar.scrolled {
            transform: translate3d(0, 0, 0) !important;
            min-height: 80px !important;
            padding: 1rem 0 !important;
        }
        
        /* Admin page content adjustments */
        body {
            padding-top: 100px !important;
        }
        
        /* Admin Statistics Cards - Beautiful Design */
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.25);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            padding: 30px;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(102, 126, 234, 0.35);
        }
        
        .stat-card .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #ffffff;
            backdrop-filter: blur(10px);
        }
        
        .stat-card .stat-content h3 {
            color: #ffffff !important;
            font-weight: 800;
            font-size: 1.8rem;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
        }
        
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.95) !important;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            padding: 0.5rem 1rem !important;
        }
        
        .navbar-nav .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transform: translateY(-2px);
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%);
        }
        
        .navbar-nav .nav-link:hover::after {
            width: 80%;
        }
        
        /* Admin dropdown styling */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.25);
            margin-top: 1rem;
            position: absolute !important;
            z-index: 9999 !important;
            max-height: none;
            overflow: visible;
            left: -100px !important;
        }
        
        .dropdown-item {
            color: #333 !important;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            border-radius: 10px;
            transform: translateX(5px);
        }
        
        /* Admin footer styling */
        footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
            color: #ffffff !important;
            border-top: 3px solid #667eea;
        }
        
        footer a {
            color: #667eea !important;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        footer a:hover {
            color: #764ba2 !important;
            text-decoration: underline;
        }
        
        /* Admin cards styling */
        .admin-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .admin-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #667eea;
            color: #333;
        }
        
        /* Admin table styling */
        .table {
            background: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: scale(1.02);
        }
        
        /* Admin buttons styling */
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-outline-info {
            border: 2px solid #17a2b8;
            color: #17a2b8;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-info:hover {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border-color: #17a2b8;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(23, 162, 184, 0.3);
        }
        
        .btn-outline-success {
            border: 2px solid #28a745;
            color: #28a745;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-success:hover {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            border-color: #28a745;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }
        
        /* Admin message styling */
        .message-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #667eea;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .message-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }
        
        /* Remove horizontal scrollbar */
        body {
            overflow-x: hidden !important;
        }
        
        .container-fluid {
            overflow: visible !important;
            max-width: 100% !important;
        }
        
        * {
            box-sizing: border-box !important;
        }
        
        .container-fluid,
        .admin-card,
        .card,
        .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12,
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12,
        .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12,
        .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12,
        .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12 {
            overflow: visible !important;
        }
        
        /* Text center styling fix */
        .text-muted.text-center {
            color: #6c757d !important;
            text-align: center !important;
        }
    </style>
    <?php endif; ?>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-camera"></i> Sanjay PhotoStudio
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $is_admin_page ? '../index.php' : 'index.php'; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $is_admin_page ? '../about.php' : 'about.php'; ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $is_admin_page ? '../services.php' : 'services.php'; ?>">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $is_admin_page ? '../gallery.php' : 'gallery.php'; ?>">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $is_admin_page ? '../booking.php' : 'booking.php'; ?>">Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $is_admin_page ? '../contact.php' : 'contact.php'; ?>">Contact</a>
                    </li>
                    
                    <?php if(isLoggedIn()): ?>
                        <?php if(isAdmin()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-shield"></i> Admin
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?php echo $is_admin_page ? 'dashboard.php' : $base_path . 'dashboard.php'; ?>">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?php echo $is_admin_page ? 'manage_bookings.php' : $base_path . 'manage_bookings.php'; ?>">Manage Bookings</a></li>
                                    <li><a class="dropdown-item" href="<?php echo $is_admin_page ? 'manage_gallery.php' : $base_path . 'manage_gallery.php'; ?>">Manage Gallery</a></li>
                                    <li><a class="dropdown-item" href="<?php echo $is_admin_page ? 'manage_services.php' : $base_path . 'manage_services.php'; ?>">Manage Services</a></li>
                                    <li><a class="dropdown-item" href="<?php echo $is_admin_page ? 'manage_users.php' : $base_path . 'manage_users.php'; ?>">Manage Users</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo $is_admin_page ? '../profile.php' : 'profile.php'; ?>">My Profile</a></li>
                                <li><a class="dropdown-item" href="<?php echo $is_admin_page ? '../my_bookings.php' : 'my_bookings.php'; ?>">My Bookings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo $is_admin_page ? 'logout.php' : 'logout.php'; ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $is_admin_page ? '../login.php' : 'login.php'; ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $is_admin_page ? '../register.php' : 'register.php'; ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <?php if (!$is_admin_page): ?>
    <style>
        /* Admin Navbar - Regular Style Applied */
        .navbar {
            background: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 50%, #45B7D1 100%) !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border-bottom: 3px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 40px rgba(255, 107, 107, 0.4);
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 99999 !important;
            padding: 1rem 0;
            transition: none !important;
            min-height: 80px !important;
            width: 100vw !important;
            transform: translate3d(0, 0, 0) !important;
            will-change: transform !important;
            backface-visibility: hidden !important;
        }
        
        .navbar-brand {
            font-size: 1.4rem !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            text-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }
        
        .navbar-brand i {
            font-size: 1.6rem;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        
        .navbar-nav {
            margin-left: 100px;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            font-size: 0.85rem;
            border-radius: 8px;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .navbar-nav .nav-link:hover::before {
            left: 100%;
        }
        
        .navbar-nav .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #ffffff, #f0f0f0);
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover::after {
            width: 80%;
        }
        
        .dropdown-menu {
            background: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 50%, #45B7D1 100%) !important;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
            backdrop-filter: blur(10px);
            margin-top: 1rem;
        }
        
        .dropdown-item {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0.2rem 0.5rem;
        }
        
        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff !important;
            border-radius: 10px;
            transform: translateX(5px);
        }
        
        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.5) !important;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-toggler:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255, 255, 255, 0.9%29' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
    <?php endif; ?>
