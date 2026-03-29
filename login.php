<?php
require_once 'includes/header.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}
?>

<!-- Page Header -->
<section class="page-header" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/login-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="display-4 fw-bold text-white">Login</h1>
            <p class="lead text-white">Access your Sanjay PhotoStudio account</p>
        </div>
    </div>
</section>

<!-- Login Section -->
<section class="py-5">
    <style>
        /* Beautiful Login Page Animations */
        .auth-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideInUp 0.8s ease-out;
        }
        
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        .auth-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
        }
        
        .auth-header {
            padding: 3rem 2rem 2rem;
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.05) 0%, rgba(78, 205, 196, 0.05) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .auth-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            animation: pulse 2s ease-in-out infinite;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
        }
        
        .auth-header h3 {
            color: #2c3e50;
            font-weight: 800;
            margin-top: 1.5rem;
            font-size: 2rem;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }
        
        .auth-header p {
            color: #6c757d;
            font-size: 1.1rem;
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }
        
        .auth-body {
            padding: 2.5rem;
        }
        
        .form-label {
            color: #495057;
            font-weight: 600;
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: fadeInUp 0.6s ease-out 0.6s both;
        }
        
        .input-group {
            position: relative;
            animation: fadeInUp 0.6s ease-out 0.8s both;
        }
        
        .input-group-text {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border: none;
            color: white;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-left: none;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control:focus {
            border-color: #4ECDC4;
            box-shadow: 0 0 0 0.2rem rgba(78, 205, 196, 0.25);
            background: white;
            transform: translateY(-2px);
        }
        
        .input-group:hover .input-group-text {
            background: linear-gradient(135deg, #4ECDC4, #45B7D1);
            transform: scale(1.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border: none;
            padding: 0.8rem 2rem;
            font-weight: 700;
            font-size: 1.1rem;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out 1s both;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #4ECDC4, #45B7D1);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(78, 205, 196, 0.3);
        }
        
        .btn-primary:hover::before {
            width: 400px;
            height: 400px;
        }
        
        .auth-footer {
            text-align: center;
            padding-top: 1.5rem;
            margin-top: 2rem;
            border-top: 1px solid #e9ecef;
            animation: fadeInUp 0.6s ease-out 1.2s both;
        }
        
        .auth-footer a {
            color: #4ECDC4;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .auth-footer a:hover {
            color: #FF6B6B;
            transform: translateY(-2px);
        }
        
        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        @keyframes shimmer {
            0%, 100% {
                opacity: 0.8;
            }
            50% {
                opacity: 1;
            }
        }
        
        /* Floating background elements */
        .bg-element {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.1), rgba(78, 205, 196, 0.1));
            animation: float 8s ease-in-out infinite;
        }
        
        .bg-element:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .bg-element:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .bg-element:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-card">
                    <div class="bg-element"></div>
                    <div class="bg-element"></div>
                    <div class="bg-element"></div>
                    <div class="auth-header text-center">
                        <i class="fas fa-camera auth-icon"></i>
                        <h3 class="mt-3">Welcome Back</h3>
                        <p class="text-muted">Login to your account</p>
                    </div>
                    
                    <div class="auth-body">
                        <?php if (isset($login_error)): ?>
                            <?php echo errorMessage($login_error); ?>
                        <?php endif; ?>
                        
                        <form method="POST" id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" required 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                           placeholder="Enter your email">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" required 
                                           placeholder="Enter your password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" name="login" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <a href="forgot-password.php" class="text-decoration-none">Forgot your password?</a>
                        </div>
                        
                        <div class="divider">
                            <span>OR</span>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0">Don't have an account?</p>
                            <a href="register.php" class="btn btn-outline-primary mt-2">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Why Create an Account?</h2>
            <p class="lead text-muted">Unlock exclusive features and benefits</p>
        </div>
        
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="feature-item">
                    <i class="fas fa-calendar-check feature-icon"></i>
                    <h5>Easy Booking</h5>
                    <p>Book photoshoots quickly and track your booking history</p>
                </div>
            </div>
            
            <div class="col-md-4 text-center mb-4">
                <div class="feature-item">
                    <i class="fas fa-images feature-icon"></i>
                    <h5>Private Gallery</h5>
                    <p>Access your personal photo gallery anytime, anywhere</p>
                </div>
            </div>
            
            <div class="col-md-4 text-center mb-4">
                <div class="feature-item">
                    <i class="fas fa-gift feature-icon"></i>
                    <h5>Special Offers</h5>
                    <p>Get exclusive discounts and early access to promotions</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Form validation
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    if (!email || !password) {
        e.preventDefault();
        alert('Please fill in all fields');
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address');
        return false;
    }
});

// Remember me functionality
document.addEventListener('DOMContentLoaded', function() {
    const rememberCheckbox = document.getElementById('remember');
    const emailInput = document.getElementById('email');
    
    // Check if email was saved
    const savedEmail = localStorage.getItem('rememberedEmail');
    if (savedEmail) {
        emailInput.value = savedEmail;
        rememberCheckbox.checked = true;
    }
    
    // Save email if remember is checked
    document.getElementById('loginForm').addEventListener('submit', function() {
        if (rememberCheckbox.checked) {
            localStorage.setItem('rememberedEmail', emailInput.value);
        } else {
            localStorage.removeItem('rememberedEmail');
        }
    });
});
</script>
