<?php
require_once 'includes/header.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}
?>

<!-- Page Header -->
<section class="page-header" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/register-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="display-4 fw-bold text-white">Create Account</h1>
            <p class="lead text-white">Join Sanjay PhotoStudio community</p>
        </div>
    </div>
</section>

<!-- Register Section -->
<section class="py-5">
    <style>
        /* Beautiful Registration Page Animations */
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
        
        /* Registration specific styles */
        .row .col-md-6:nth-child(odd) .input-group {
            animation: fadeInUp 0.6s ease-out 0.8s both;
        }
        
        .row .col-md-6:nth-child(even) .input-group {
            animation: fadeInUp 0.6s ease-out 0.9s both;
        }
        
        .form-check {
            animation: fadeInUp 0.6s ease-out 1.1s both;
        }
        
        .form-check-input:checked {
            background-color: #4ECDC4;
            border-color: #4ECDC4;
        }
        
        .form-check-input:focus {
            border-color: #4ECDC4;
            box-shadow: 0 0 0 0.2rem rgba(78, 205, 196, 0.25);
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
        
        .bg-element:nth-child(4) {
            width: 120px;
            height: 120px;
            top: 30%;
            right: 20%;
            animation-delay: 6s;
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
            <div class="col-lg-6 col-md-8">
                <div class="auth-card">
                    <div class="bg-element"></div>
                    <div class="bg-element"></div>
                    <div class="bg-element"></div>
                    <div class="bg-element"></div>
                    <div class="auth-header text-center">
                        <i class="fas fa-user-plus auth-icon"></i>
                        <h3 class="mt-3">Create Your Account</h3>
                        <p class="text-muted">Join us to book photoshoots and access exclusive features</p>
                    </div>
                    
                    <div class="auth-body">
                        <?php if (isset($register_success)): ?>
                            <?php echo successMessage($register_success); ?>
                            <div class="text-center mt-3">
                                <a href="login.php" class="btn btn-primary">Login Now</a>
                            </div>
                        <?php else: ?>
                            <?php if (isset($register_error)): ?>
                                <?php echo errorMessage($register_error); ?>
                            <?php endif; ?>
                            
                            <form method="POST" id="registerForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <input type="text" class="form-control" id="name" name="name" required 
                                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                                   placeholder="Enter your full name">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                            <input type="tel" class="form-control" id="phone" name="phone" required 
                                                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                                   placeholder="1234567890"
                                                   pattern="[0-9]{10,15}">
                                        </div>
                                    </div>
                                </div>
                                
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
                                               placeholder="Create a password"
                                               minlength="6">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Minimum 6 characters</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required 
                                               placeholder="Confirm your password"
                                               minlength="6">
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="password-strength">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>Password Strength:</small>
                                            <small id="strengthText">Weak</small>
                                        </div>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" id="strengthBar" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a> and <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
                                    </label>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter">
                                    <label class="form-check-label" for="newsletter">
                                        Subscribe to our newsletter for exclusive offers and updates
                                    </label>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" name="register" class="btn btn-primary btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>Create Account
                                    </button>
                                </div>
                            </form>
                            
                            <div class="divider">
                                <span>OR</span>
                            </div>
                            
                            <div class="text-center">
                                <p class="mb-0">Already have an account?</p>
                                <a href="login.php" class="btn btn-outline-primary mt-2">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>1. Account Registration</h6>
                <p>By creating an account with PhotoStudio Pro, you agree to provide accurate and complete information.</p>
                
                <h6>2. Booking Services</h6>
                <p>All bookings are subject to availability and confirmation. A 50% advance payment is required to secure your booking.</p>
                
                <h6>3. Payment Terms</h6>
                <p>Payments can be made via credit card, bank transfer, or cash. All prices are inclusive of applicable taxes.</p>
                
                <h6>4. Cancellation Policy</h6>
                <p>Free cancellation up to 7 days before the scheduled photoshoot. 50% refund for cancellations 3-7 days before. No refund within 3 days.</p>
                
                <h6>5. Image Rights</h6>
                <p>PhotoStudio Pro retains the right to use photographs for promotional purposes unless explicitly agreed otherwise in writing.</p>
                
                <h6>6. Privacy</h6>
                <p>Your personal information will be protected according to our Privacy Policy and will not be shared with third parties.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Privacy Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Information We Collect</h6>
                <p>We collect personal information including name, email, phone number, and booking details to provide our services.</p>
                
                <h6>How We Use Your Information</h6>
                <p>Your information is used to process bookings, communicate with you, and improve our services.</p>
                
                <h6>Data Security</h6>
                <p>We implement appropriate security measures to protect your personal information from unauthorized access.</p>
                
                <h6>Third-Party Sharing</h6>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent.</p>
                
                <h6>Cookies</h6>
                <p>We use cookies to enhance your experience on our website and remember your preferences.</p>
                
                <h6>Your Rights</h6>
                <p>You have the right to access, update, or delete your personal information at any time.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Member Benefits</h2>
            <p class="lead text-muted">Exclusive advantages for registered users</p>
        </div>
        
        <div class="row">
            <div class="col-md-3 col-6 text-center mb-4">
                <div class="benefit-item">
                    <i class="fas fa-percentage benefit-icon"></i>
                    <h6>Member Discounts</h6>
                    <p class="small">Get 10% off on all services</p>
                </div>
            </div>
            
            <div class="col-md-3 col-6 text-center mb-4">
                <div class="benefit-item">
                    <i class="fas fa-bolt benefit-icon"></i>
                    <h6>Priority Booking</h6>
                    <p class="small">Book preferred dates first</p>
                </div>
            </div>
            
            <div class="col-md-3 col-6 text-center mb-4">
                <div class="benefit-item">
                    <i class="fas fa-download benefit-icon"></i>
                    <h6>Free Downloads</h6>
                    <p class="small">5 free photos per session</p>
                </div>
            </div>
            
            <div class="col-md-3 col-6 text-center mb-4">
                <div class="benefit-item">
                    <i class="fas fa-star benefit-icon"></i>
                    <h6>Loyalty Points</h6>
                    <p class="small">Earn rewards with every booking</p>
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

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('confirm_password');
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

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    let strength = 0;
    
    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    const strengthLevels = ['Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    const strengthColors = ['danger', 'warning', 'info', 'success', 'success'];
    const strengthWidths = ['20%', '40%', '60%', '80%', '100%'];
    
    strengthBar.style.width = strengthWidths[strength];
    strengthBar.className = 'progress-bar bg-' + strengthColors[strength];
    strengthText.textContent = strengthLevels[strength];
});

// Form validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match');
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long');
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address');
        return false;
    }
    
    const phoneRegex = /^[0-9]{10,15}$/;
    if (!phoneRegex.test(phone)) {
        e.preventDefault();
        alert('Please enter a valid phone number (10-15 digits)');
        return false;
    }
});

// Auto-format phone number
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 15) {
        value = value.slice(0, 15);
    }
    e.target.value = value;
});
</script>
