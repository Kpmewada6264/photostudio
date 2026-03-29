<?php require_once 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/services-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="display-4 fw-bold text-white">Our Photography Services</h1>
            <p class="lead text-white">Professional photography packages tailored to your needs</p>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <style>
        /* Beautiful Services Page Animations */
        .service-card-full {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.8s ease-out;
            height: 100%;
        }
        
        .service-card-full::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        .service-card-full:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
        }
        
        .service-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }
        
        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.4s ease;
            image-rendering: auto;
            image-rendering: -webkit-optimize-contrast;
            max-width: 100%;
            height: auto;
        }
        
        .service-card-full:hover .service-image img {
            transform: scale(1.1);
            filter: brightness(1.1);
        }
        
        .service-overlay {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        
        .service-price-tag {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .service-content {
            padding: 2rem;
        }
        
        .service-content h3 {
            color: #2c3e50;
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .service-card-full:hover .service-content h3 {
            color: #FF6B6B;
            transform: translateY(-3px);
        }
        
        .service-content p {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .service-card-full:hover .service-content p {
            color: #495057;
            transform: translateY(-2px);
        }
        
        .service-features ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .service-features li {
            padding: 0.5rem 0;
            color: #495057;
            transition: all 0.3s ease;
        }
        
        .service-features li i {
            color: #4ECDC4;
            margin-right: 0.5rem;
        }
        
        .service-card-full:hover .service-features li {
            transform: translateX(5px);
            color: #2c3e50;
        }
        
        /* Why Choose Section */
        .why-choose-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            animation: fadeInUp 0.8s ease-out;
            height: 100%;
        }
        
        .why-choose-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        }
        
        .why-choose-icon {
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
        
        .why-choose-card:hover .why-choose-icon {
            background: linear-gradient(135deg, #4ECDC4, #45B7D1);
            transform: rotateY(360deg) scale(1.1);
        }
        
        /* Process Steps */
        .process-step {
            text-align: center;
            padding: 2rem;
            position: relative;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .process-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 auto 1.5rem;
            transition: all 0.3s ease;
        }
        
        .process-step:hover .process-number {
            background: linear-gradient(135deg, #4ECDC4, #45B7D1);
            transform: scale(1.2);
        }
        
        /* Testimonials */
        .testimonial-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            animation: fadeInUp 0.8s ease-out;
            height: 100%;
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        }
        
        .testimonial-text {
            font-style: italic;
            color: #6c757d;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .testimonial-author {
            text-align: center;
        }
        
        .testimonial-author h5 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .testimonial-author p {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border-radius: 25px;
            padding: 4rem 3rem;
            text-align: center;
            color: white;
            margin-top: 3rem;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            animation: shimmer 4s ease-in-out infinite;
        }
        
        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .cta-section .btn {
            background: white;
            color: #FF6B6B;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .cta-section .btn:hover {
            background: #f8f9fa;
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
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
        
        /* Staggered animations */
        .service-card-full:nth-child(1) { animation-delay: 0.1s; }
        .service-card-full:nth-child(2) { animation-delay: 0.2s; }
        .service-card-full:nth-child(3) { animation-delay: 0.3s; }
        .service-card-full:nth-child(4) { animation-delay: 0.4s; }
        .service-card-full:nth-child(5) { animation-delay: 0.5s; }
        .service-card-full:nth-child(6) { animation-delay: 0.6s; }
        
        .why-choose-card:nth-child(1) { animation-delay: 0.2s; }
        .why-choose-card:nth-child(2) { animation-delay: 0.4s; }
        .why-choose-card:nth-child(3) { animation-delay: 0.6s; }
        .why-choose-card:nth-child(4) { animation-delay: 0.8s; }
        
        .process-step:nth-child(1) { animation-delay: 0.1s; }
        .process-step:nth-child(2) { animation-delay: 0.2s; }
        .process-step:nth-child(3) { animation-delay: 0.3s; }
        .process-step:nth-child(4) { animation-delay: 0.4s; }
        
        .testimonial-card:nth-child(1) { animation-delay: 0.1s; }
        .testimonial-card:nth-child(2) { animation-delay: 0.2s; }
        .testimonial-card:nth-child(3) { animation-delay: 0.3s; }
    </style>
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Photography Packages</h2>
            <p class="lead text-muted">Choose the perfect package for your special occasion</p>
        </div>
        
        <div class="row g-4">
            <?php
            require_once 'includes/db.php';
            $sql = "SELECT * FROM services ORDER BY id ASC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($service = $result->fetch_assoc()) {
                    echo '<div class="col-lg-4 col-md-6">';
                    echo '<div class="service-card-full h-100">';
                    echo '<div class="service-image">';
                    echo '<img src="assets/images/services/' . htmlspecialchars($service['image']) . '" alt="' . htmlspecialchars($service['title']) . '" class="img-fluid">';
                    echo '<div class="service-overlay">';
                    echo '<div class="service-price-tag">$' . number_format($service['price'], 2) . '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="service-content">';
                    echo '<h3>' . htmlspecialchars($service['title']) . '</h3>';
                    echo '<p>' . htmlspecialchars($service['description']) . '</p>';
                    echo '<div class="service-features">';
                    
                    // Add features based on service type
                    if (strpos(strtolower($service['title']), 'wedding') !== false) {
                        echo '<ul class="list-unstyled">';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Full day coverage</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>2 photographers</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>500+ edited photos</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Online gallery</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Print rights included</li>';
                        echo '</ul>';
                    } elseif (strpos(strtolower($service['title']), 'pre wedding') !== false) {
                        echo '<ul class="list-unstyled">';
                        echo '<li><i class="fas fa-check text-success me-2"></i>4 hours shoot</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>2 locations</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>100+ edited photos</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Props included</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>High resolution images</li>';
                        echo '</ul>';
                    } elseif (strpos(strtolower($service['title']), 'birthday') !== false) {
                        echo '<ul class="list-unstyled">';
                        echo '<li><i class="fas fa-check text-success me-2"></i>3 hours coverage</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>50+ edited photos</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Candid & posed shots</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Group photos</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Digital delivery</li>';
                        echo '</ul>';
                    } elseif (strpos(strtolower($service['title']), 'event') !== false) {
                        echo '<ul class="list-unstyled">';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Up to 6 hours</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>200+ edited photos</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Event coverage</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Networking shots</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Same day preview</li>';
                        echo '</ul>';
                    } elseif (strpos(strtolower($service['title']), 'product') !== false) {
                        echo '<ul class="list-unstyled">';
                        echo '<li><i class="fas fa-check text-success me-2"></i>10 products</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>3 angles per product</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>White background</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Commercial license</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Quick delivery</li>';
                        echo '</ul>';
                    } elseif (strpos(strtolower($service['title']), 'portfolio') !== false) {
                        echo '<ul class="list-unstyled">';
                        echo '<li><i class="fas fa-check text-success me-2"></i>3 outfit changes</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>2 locations</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>50+ retouched photos</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Makeup artist</li>';
                        echo '<li><i class="fas fa-check text-success me-2"></i>Portfolio website</li>';
                        echo '</ul>';
                    }
                    
                    echo '</div>';
                    echo '<div class="service-footer">';
                    echo '<div class="price">$' . number_format($service['price'], 2) . '</div>';
                    echo '<a href="booking.php?service=' . urlencode($service['title']) . '" class="btn btn-primary">Book Now</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Why Choose Sanjay PhotoStudio</h2>
            <p class="lead text-muted">Experience the difference with our professional services</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <i class="fas fa-camera feature-icon"></i>
                    <h5>Professional Equipment</h5>
                    <p>Latest cameras, lenses, and lighting equipment for perfect shots</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <i class="fas fa-user-tie feature-icon"></i>
                    <h5>Expert Photographers</h5>
                    <p>Experienced professionals with creative vision and technical skills</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <i class="fas fa-clock feature-icon"></i>
                    <h5>Timely Delivery</h5>
                    <p>Quick turnaround time without compromising on quality</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <i class="fas fa-heart feature-icon"></i>
                    <h5>Passion & Dedication</h5>
                    <p>We love what we do and it shows in every photograph</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Our Simple Process</h2>
            <p class="lead text-muted">From booking to delivery, we make it easy</p>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="process-item text-center">
                    <div class="process-number">1</div>
                    <i class="fas fa-calendar-check process-icon"></i>
                    <h5>Book Your Shoot</h5>
                    <p>Choose your package and schedule your photoshoot</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="process-item text-center">
                    <div class="process-number">2</div>
                    <i class="fas fa-comments process-icon"></i>
                    <h5>Consultation</h5>
                    <p>Discuss your vision and preferences with our team</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="process-item text-center">
                    <div class="process-number">3</div>
                    <i class="fas fa-camera-retro process-icon"></i>
                    <h5>Photoshoot</h5>
                    <p>Enjoy your professional photography session</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="process-item text-center">
                    <div class="process-number">4</div>
                    <i class="fas fa-image process-icon"></i>
                    <h5>Delivery</h5>
                    <p>Receive your beautifully edited photos</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Client Testimonials</h2>
            <p class="lead text-muted">What our clients say about our services</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="stars mb-3">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <p>"The wedding photography package exceeded our expectations. Every detail was captured beautifully!"</p>
                    <div class="testimonial-author">
                        <strong>Jennifer & Mark</strong>
                        <small class="text-muted d-block">Wedding Package</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="stars mb-3">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <p>"Professional product photography that helped our sales increase by 40%!"</p>
                    <div class="testimonial-author">
                        <strong>Tech Startup</strong>
                        <small class="text-muted d-block">Product Photography</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="stars mb-3">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <p>"Amazing portfolio shoot! The team made me feel comfortable and confident."</p>
                    <div class="testimonial-author">
                        <strong>Amanda Lee</strong>
                        <small class="text-muted d-block">Model Portfolio</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Ready to Book Your Photoshoot?</h2>
        <p class="lead mb-4">Contact us today to discuss your photography needs</p>
        <a href="booking.php" class="btn btn-light btn-lg me-3">Book Now</a>
        <a href="contact.php" class="btn btn-outline-light btn-lg">Get Quote</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
