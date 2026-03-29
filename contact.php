<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Handle contact form submission
$contact_success = '';
$contact_error = '';

if (isset($_POST['send_message'])) {
    $name = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $subject = sanitize($conn, $_POST['subject']);
    $message = sanitize($conn, $_POST['message']);
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $contact_error = "Please fill in all required fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contact_error = "Please enter a valid email address";
    } else {
        // Insert contact message into database
        $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            $contact_success = "Your message has been sent successfully! We'll get back to you within 24 hours.";
            
            // Send email notification (you can implement email functionality here)
            // mail('admin@photostudio.com', "New Contact Form Submission", "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message");
            
            // Clear form
            unset($_POST);
        } else {
            $contact_error = "Failed to send message. Please try again.";
        }
    }
}
?>

<!-- Page Header -->
<section class="page-header" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/contact-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="display-4 fw-bold text-white">Contact Us</h1>
            <p class="lead text-white">Get in touch with Sanjay PhotoStudio</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <style>
        /* Beautiful Contact Page Animations */
        .contact-form-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideInLeft 0.8s ease-out;
        }
        
        .contact-form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        .contact-form-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
        }
        
        .contact-form-card .card-header {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4) !important;
            border: none;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .contact-form-card .card-header h3 {
            color: white;
            font-weight: 800;
            font-size: 1.8rem;
            margin: 0;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }
        
        .contact-form-card .card-body {
            padding: 3rem;
        }
        
        .contact-info-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideInRight 0.8s ease-out;
        }
        
        .contact-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #4ECDC4, #45B7D1, #FF6B6B);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        .contact-info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
        }
        
        .contact-item {
            padding: 2rem;
            border-radius: 15px;
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.05) 0%, rgba(78, 205, 196, 0.05) 100%);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }
        
        .contact-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.1) 0%, rgba(78, 205, 196, 0.1) 100%);
        }
        
        .contact-item i {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            display: block;
            text-align: center;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .contact-item h4 {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .contact-item p {
            color: #6c757d;
            text-align: center;
            margin-bottom: 0;
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
        
        .form-control {
            border: 2px solid #e9ecef;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
            border-radius: 10px;
            animation: fadeInUp 0.6s ease-out 0.8s both;
        }
        
        .form-control:focus {
            border-color: #4ECDC4;
            box-shadow: 0 0 0 0.2rem rgba(78, 205, 196, 0.25);
            background: white;
            transform: translateY(-2px);
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
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
            animation: fadeInUp 0.6s ease-out 1.2s both;
        }
        
        .social-links a {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: linear-gradient(135deg, #4ECDC4, #45B7D1);
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 10px 25px rgba(78, 205, 196, 0.3);
        }
        
        /* Animations */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
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
            pointer-events: none;
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
        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-8 mb-4">
                <div class="contact-form-card">
                    <div class="bg-element"></div>
                    <div class="bg-element"></div>
                    <div class="bg-element"></div>
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Send Us a Message</h3>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($contact_success): ?>
                            <?php echo successMessage($contact_success); ?>
                        <?php endif; ?>
                        
                        <?php if ($contact_error): ?>
                            <?php echo errorMessage($contact_error); ?>
                        <?php endif; ?>
                        
                        <form method="POST" id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Your Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required 
                                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" required 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject" required 
                                       value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"
                                       placeholder="How can we help you?">
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" name="message" rows="6" required 
                                          placeholder="Tell us more about your inquiry..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>
                            
                            <button type="submit" name="send_message" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-lg-4 mb-4">
                <div class="contact-info-card">
                    <div class="bg-element"></div>
                    <div class="bg-element"></div>
                    <div class="bg-element"></div>
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">Contact Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <h4>Studio Address</h4>
                            <p>Old AB road,near by Vivek talkies<br>Biaora Rajgarh,M.P</p>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <h4>Phone Numbers</h4>
                            <p>9981086235</p>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <h4>Email Address</h4>
                            <p>shakysanjay009@gmail.com</p>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <h4>Business Hours</h4>
                            <p>24*7</p>
                        </div>
                        
                        <div class="social-links">
                            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Find Our Studio</h2>
            <p class="lead text-muted">Visit us at our convenient location in the heart of the city</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.8354499264!2d76.8267!3d23.7544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x397fb0d8f5d5d5d5%3A0x1234567890abcdef!2sOld+AB+Road%2C+Vivek+Talkies%2C+Biaora%2C+Rajgarh%2C+Madhya+Pradesh!5e0!3m2!1sen!2sin!4v1234567890"
                        width="100%" 
                        height="450" 
                        style="border:0; border-radius: 10px;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4 text-center">
                        <div class="direction-item">
                            <i class="fas fa-car text-primary fs-2 mb-3"></i>
                            <h5>By Car</h5>
                            <p class="text-muted">Parking available on-site and nearby garages</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="direction-item">
                            <i class="fas fa-subway text-primary fs-2 mb-3"></i>
                            <h5>By Subway</h5>
                            <p class="text-muted">5 minutes walk from Times Square station</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="direction-item">
                            <i class="fas fa-bus text-primary fs-2 mb-3"></i>
                            <h5>By Bus</h5>
                            <p class="text-muted">Multiple bus routes stop nearby</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Contact Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Quick Contact Options</h2>
        <div class="row justify-content-center">
            <div class="col-md-3 col-6 mb-3">
                <a href="tel:+15551234567" class="quick-contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>Call Us</span>
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="mailto:info@photostudiopro.com" class="quick-contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>Email Us</span>
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="https://wa.me/15559876543" target="_blank" class="quick-contact-item">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp</span>
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="booking.php" class="quick-contact-item">
                    <i class="fas fa-calendar"></i>
                    <span>Book Online</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Common Questions</h2>
            <p class="lead text-muted">Quick answers to frequently asked questions</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="faq-item">
                            <h5>How quickly do you respond to inquiries?</h5>
                            <p class="text-muted">We respond to all inquiries within 24 hours during business days.</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="faq-item">
                            <h5>Do you offer free consultations?</h5>
                            <p class="text-muted">Yes, we offer free 15-minute consultation calls to discuss your photography needs.</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="faq-item">
                            <h5>Can I visit the studio before booking?</h5>
                            <p class="text-muted">Absolutely! Schedule a studio tour to see our facilities and meet the team.</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="faq-item">
                            <h5>What payment methods do you accept?</h5>
                            <p class="text-muted">We accept cash, credit cards, bank transfers, and digital payments.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<script>
// Form validation
document.getElementById('contactForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address');
        return false;
    }
});

// Character counter for message
document.getElementById('message').addEventListener('input', function() {
    const maxLength = 1000;
    const currentLength = this.value.length;
    
    if (currentLength > maxLength) {
        this.value = this.value.substring(0, maxLength);
    }
});
</script>
