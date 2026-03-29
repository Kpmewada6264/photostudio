<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Handle booking form submission
$booking_success = '';
$booking_error = '';

if (isset($_POST['book_now'])) {
    $name = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $phone = sanitize($conn, $_POST['phone']);
    $event_type = sanitize($conn, $_POST['event_type']);
    $date = sanitize($conn, $_POST['date']);
    $location = sanitize($conn, $_POST['location']);
    $message = sanitize($conn, $_POST['message']);
    
    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($event_type) || empty($date) || empty($location)) {
        $booking_error = "Please fill in all required fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $booking_error = "Please enter a valid email address";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $booking_error = "Please enter a valid phone number";
    } elseif (strtotime($date) < strtotime(date('Y-m-d'))) {
        $booking_error = "Please select a future date";
    } else {
        // Insert booking into database
        $sql = "INSERT INTO bookings (name, email, phone, event_type, date, location, message) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $name, $email, $phone, $event_type, $date, $location, $message);
        
        if ($stmt->execute()) {
            $booking_success = "Your booking request has been submitted successfully! We will contact you within 24 hours.";
            
            // Send email notification (you can implement email functionality here)
            // mail($email, "Booking Confirmation", "Thank you for booking with PhotoStudio Pro...");
            
            // Clear form
            unset($_POST);
        } else {
            $booking_error = "Failed to submit booking. Please try again.";
        }
    }
}

// Get selected service from URL
$selected_service = isset($_GET['service']) ? sanitize($conn, $_GET['service']) : '';
?>

<!-- Page Header -->
<section class="page-header" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/booking-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="display-4 fw-bold text-white">Book Your Photoshoot</h1>
            <p class="lead text-white">Schedule your professional photography session</p>
        </div>
    </div>
</section>

<!-- Booking Form Section -->
<section class="py-5">
    <style>
        /* Beautiful Booking Page Animations */
        .booking-form-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideInUp 0.8s ease-out;
        }
        
        .booking-form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        .booking-form-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
        }
        
        .booking-form-card .card-header {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4) !important;
            border: none;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .booking-form-card .card-header h3 {
            color: white;
            font-weight: 800;
            font-size: 1.8rem;
            margin: 0;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }
        
        .booking-form-card .card-body {
            padding: 3rem;
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
        
        /* Process Cards */
        .process-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            animation: fadeInUp 0.8s ease-out;
            height: 100%;
        }
        
        .process-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        }
        
        .process-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
            transition: all 0.3s ease;
        }
        
        .process-card:hover .process-icon {
            background: linear-gradient(135deg, #4ECDC4, #45B7D1);
            transform: rotateY(360deg) scale(1.1);
        }
        
        /* FAQ Accordion */
        .faq-accordion {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .faq-accordion:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        }
        
        .accordion-button {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            color: white;
            font-weight: 700;
            border: none;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .accordion-button:hover {
            background: linear-gradient(135deg, #4ECDC4, #45B7D1);
            transform: translateY(-2px);
        }
        
        .accordion-button:focus {
            box-shadow: none;
            border-color: none;
        }
        
        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #4ECDC4, #45B7D1);
            color: white;
        }
        
        .accordion-body {
            background: #f8f9fa;
            padding: 1.5rem;
            color: #495057;
            line-height: 1.6;
        }
        
        /* Contact Section */
        .contact-section {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border-radius: 25px;
            padding: 3rem;
            text-align: center;
            color: white;
            margin-top: 3rem;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .contact-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            animation: shimmer 4s ease-in-out infinite;
        }
        
        .contact-section h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .contact-section p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .contact-section .btn {
            background: white;
            color: #FF6B6B;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .contact-section .btn:hover {
            background: #f8f9fa;
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
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
        
        @keyframes shimmer {
            0%, 100% {
                opacity: 0.8;
            }
            50% {
                opacity: 1;
            }
        }
        
        /* Staggered animations */
        .process-card:nth-child(1) { animation-delay: 0.2s; }
        .process-card:nth-child(2) { animation-delay: 0.4s; }
        .process-card:nth-child(3) { animation-delay: 0.6s; }
        
        .faq-accordion:nth-child(1) { animation-delay: 0.1s; }
        .faq-accordion:nth-child(2) { animation-delay: 0.2s; }
        .faq-accordion:nth-child(3) { animation-delay: 0.3s; }
        .faq-accordion:nth-child(4) { animation-delay: 0.4s; }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php if ($booking_success): ?>
                    <?php echo successMessage($booking_success); ?>
                <?php endif; ?>
                
                <?php if ($booking_error): ?>
                    <?php echo errorMessage($booking_error); ?>
                <?php endif; ?>
                
                <div class="booking-form-card">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">Booking Information</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" id="bookingForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required 
                                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" required 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required 
                                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                           pattern="[0-9]{10,15}" placeholder="1234567890">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="event_type" class="form-label">Event Type *</label>
                                    <select class="form-select" id="event_type" name="event_type" required>
                                        <option value="">Select Event Type</option>
                                        <option value="Wedding Photography" <?php echo ($selected_service == 'Wedding Photography') ? 'selected' : ''; ?>>Wedding Photography</option>
                                        <option value="Pre Wedding Shoot" <?php echo ($selected_service == 'Pre Wedding Shoot') ? 'selected' : ''; ?>>Pre Wedding Shoot</option>
                                        <option value="Birthday Photography">Birthday Photography</option>
                                        <option value="Event Photography">Event Photography</option>
                                        <option value="Product Photography">Product Photography</option>
                                        <option value="Model Portfolio Shoot">Model Portfolio Shoot</option>
                                        <option value="Portrait Session">Portrait Session</option>
                                        <option value="Corporate Event">Corporate Event</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Preferred Date *</label>
                                    <input type="date" class="form-control" id="date" name="date" required 
                                           value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>"
                                           min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Location *</label>
                                    <input type="text" class="form-control" id="location" name="location" required 
                                           value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>"
                                           placeholder="Studio address or event location">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="form-label">Additional Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" 
                                          placeholder="Tell us more about your photoshoot requirements..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" name="book_now" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-calendar-check me-2"></i>Book Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Information Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="info-card text-center">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4>Booking Process</h4>
                    <p>Submit your booking request and we'll contact you within 24 hours to confirm details and discuss your requirements.</p>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="info-card text-center">
                    <div class="info-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h4>Payment</h4>
                    <p>50% advance payment required to confirm booking. Remaining payment due on photoshoot day. Multiple payment options available.</p>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="info-card text-center">
                    <div class="info-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Cancellation Policy</h4>
                    <p>Free cancellation up to 7 days before photoshoot. 50% refund for cancellations 3-7 days before. No refund within 3 days.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Frequently Asked Questions</h2>
            <p class="lead text-muted">Everything you need to know about booking</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How far in advance should I book?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We recommend booking at least 2-3 weeks in advance for regular sessions and 2-3 months for weddings to ensure availability. However, we can accommodate last-minute requests based on our schedule.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                What's included in the booking?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Each package includes professional photography services, post-production editing, digital delivery of images, and online gallery. Specific inclusions vary by package - please check our services page for detailed information.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Do you travel for photoshoots?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes! We travel throughout the city and surrounding areas. Travel fees may apply for locations beyond 50km from our studio. We also offer destination photography services.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                How long does it take to receive photos?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Standard delivery time is 7-14 days for regular sessions and 3-4 weeks for weddings. Rush delivery options are available for an additional fee if you need your photos sooner.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Can I customize my package?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Absolutely! We can customize packages to meet your specific needs. Contact us to discuss your requirements and we'll create a personalized package for you.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Have Questions?</h2>
        <p class="lead mb-4">Our team is here to help you plan the perfect photoshoot</p>
        <a href="contact.php" class="btn btn-light btn-lg me-3">Contact Us</a>
        <a href="tel:+15551234567" class="btn btn-outline-light btn-lg">
            <i class="fas fa-phone me-2"></i>Call Now
        </a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<script>
// Form validation
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const phone = document.getElementById('phone').value;
    const phoneRegex = /^[0-9]{10,15}$/;
    
    if (!phoneRegex.test(phone)) {
        e.preventDefault();
        alert('Please enter a valid phone number (10-15 digits)');
        return false;
    }
    
    const date = document.getElementById('date').value;
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        e.preventDefault();
        alert('Please select a future date for your photoshoot');
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
