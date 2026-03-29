<?php 
require_once 'includes/header.php';

// Display logout success message
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo '<div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 99999; min-width: 300px;">
        <i class="fas fa-check-circle me-2"></i>You have been successfully logged out!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}
?>

<!-- Hero Section with Slider -->
<section class="hero-slider">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('assets/images/perfect.jpg');">
                    <div class="hero-content">
                        <h1 class="display-3 fw-bold mb-4">Capture Your Perfect Moments</h1>
                        <p class="lead mb-4">Professional photography that tells your story with artistic excellence</p>
                        <a href="booking.php" class="btn btn-primary btn-lg me-3">Book a Shoot</a>
                        <a href="gallery.php" class="btn btn-outline-light btn-lg">View Gallery</a>
                    </div>
                </div>
            </div>
            
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('assets/images/perfect1.jpg');">
                    <div class="hero-content">
                        <h1 class="display-3 fw-bold mb-4">Wedding Photography Excellence</h1>
                        <p class="lead mb-4">Making your special day unforgettable with stunning visuals</p>
                        <a href="services.php" class="btn btn-primary btn-lg me-3">Our Services</a>
                        <a href="contact.php" class="btn btn-outline-light btn-lg">Contact Us</a>
                    </div>
                </div>
            </div>
            
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('assets/images/perfect3.jpg');">
                    <div class="hero-content">
                        <h1 class="display-3 fw-bold mb-4">Professional Studio Experience</h1>
                        <p class="lead mb-4">State-of-the-art equipment and experienced photographers</p>
                        <a href="about.php" class="btn btn-primary btn-lg me-3">Learn More</a>
                        <a href="booking.php" class="btn btn-outline-light btn-lg">Get Started</a>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- About Studio Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <img src="assets/images/Gemini_Generated_Image_y65o2ry65o2ry65o.png" alt="Sanjay PhotoStudio" class="img-fluid rounded shadow" style="height: calc(100% + 100px); width: auto; object-fit: cover; border: 3px solid #007bff;">
            </div>
            <div class="col-lg-6 mb-4">
                <h2 class="display-5 fw-bold mb-4">Welcome to Sanjay PhotoStudio</h2>
                <p class="lead mb-4">With over 15 years of experience in professional photography, we specialize in capturing life's most precious moments with artistic vision and technical excellence.</p>
                <p class="mb-4">Our team of award-winning photographers combines creative talent with state-of-the-art equipment to deliver stunning images that exceed your expectations.</p>
                <div class="row">
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-award text-primary fs-4 me-3"></i>
                            <div>
                                <h5 class="mb-0">Award Winning</h5>
                                <small class="text-muted">Multiple photography awards</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-users text-primary fs-4 me-3"></i>
                            <div>
                                <h5 class="mb-0">5000+ Clients</h5>
                                <small class="text-muted">Happy customers worldwide</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-camera text-primary fs-4 me-3"></i>
                            <div>
                                <h5 class="mb-0">Expert Team</h5>
                                <small class="text-muted">Professional photographers</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-heart text-primary fs-4 me-3"></i>
                            <div>
                                <h5 class="mb-0">Passion</h5>
                                <small class="text-muted">Dedicated to excellence</small>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="about.php" class="btn btn-primary mt-3">Learn More About Us</a>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview Section -->
<section class="py-5">
    <style>
        /* Beautiful Service Card Animations */
        .service-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            overflow: hidden;
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
            transform: translateY(0);
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .service-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
        }
        
        .service-card:hover::before {
            transform: scaleX(1);
        }
        
        .service-icon {
            width: 90px;
            height: 90px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.2rem;
            transition: all 0.4s ease;
            opacity: 1 !important;
            visibility: visible !important;
            position: relative;
            z-index: 2;
        }
        
        .service-card:hover .service-icon {
            background: linear-gradient(135deg, #4ECDC4, #45B7D1);
            transform: rotateY(360deg) scale(1.1);
            box-shadow: 0 10px 25px rgba(78, 205, 196, 0.3);
        }
        
        .service-card h4 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 1.4rem;
            transition: all 0.3s ease;
            opacity: 1 !important;
            visibility: visible !important;
            position: relative;
            z-index: 2;
        }
        
        .service-card:hover h4 {
            color: #FF6B6B;
            transform: translateY(-5px);
        }
        
        .service-card p {
            color: #6c757d;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            transition: all 0.3s ease;
            opacity: 1 !important;
            visibility: visible !important;
            position: relative;
            z-index: 2;
        }
        
        .service-card:hover p {
            color: #495057;
            transform: translateY(-3px);
        }
        
        .service-price {
            font-size: 1.8rem;
            font-weight: 800;
            color: #FF6B6B;
            margin: 1.5rem 0;
            transition: all 0.3s ease;
            opacity: 1 !important;
            visibility: visible !important;
            position: relative;
            z-index: 2;
        }
        
        .service-card:hover .service-price {
            color: #4ECDC4;
            transform: translateY(-5px) scale(1.05);
        }
        
        .service-card .btn {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border: none;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.4s ease;
            opacity: 1 !important;
            visibility: visible !important;
            position: relative;
            z-index: 2;
            overflow: hidden;
        }
        
        .service-card .btn::before {
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
        
        .service-card:hover .btn {
            background: linear-gradient(135deg, #4ECDC4, #45B7D1);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(78, 205, 196, 0.3);
        }
        
        .service-card:hover .btn::before {
            width: 300px;
            height: 300px;
        }
        
        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .service-card {
            animation: float 6s ease-in-out infinite;
        }
        
        .service-card:nth-child(2) {
            animation-delay: 0.5s;
        }
        
        .service-card:nth-child(3) {
            animation-delay: 1s;
        }
        
        .service-card:nth-child(4) {
            animation-delay: 1.5s;
        }
        
        .service-card:nth-child(5) {
            animation-delay: 2s;
        }
        
        .service-card:nth-child(6) {
            animation-delay: 2.5s;
        }
    </style>
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Our Photography Services</h2>
            <p class="lead text-muted">Professional photography services tailored to your needs</p>
        </div>
        
        <div class="row g-4">
            <?php
            require_once 'includes/db.php';
            $sql = "SELECT * FROM services ORDER BY id ASC LIMIT 6";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($service = $result->fetch_assoc()) {
                    echo '<div class="col-lg-4 col-md-6">';
                    echo '<div class="service-card h-100">';
                    echo '<div class="service-icon">';
                    echo '<i class="fas fa-camera"></i>';
                    echo '</div>';
                    echo '<h4>' . htmlspecialchars($service['title']) . '</h4>';
                    echo '<p>' . substr(htmlspecialchars($service['description']), 0, 100) . '...</p>';
                    echo '<div class="service-price">$' . number_format($service['price'], 2) . '</div>';
                    echo '<a href="services.php" class="btn">Learn More</a>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="services.php" class="btn btn-primary btn-lg">View All Services</a>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <h2 class="counter" data-target="5000" style="color: white !important;">0</h2>
                    <p style="color: white !important;">Happy Clients</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <h2 class="counter" data-target="7500" style="color: white !important;">0</h2>
                    <p style="color: white !important;">Photoshoots</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <h2 class="counter" data-target="15" style="color: white !important;">0</h2>
                    <p style="color: white !important;">Years Experience</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <h2 class="counter" data-target="25" style="color: white !important;">0</h2>
                    <p style="color: white !important;">Awards Won</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Preview Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Recent Work</h2>
            <p class="lead text-muted">Explore our latest photography projects</p>
        </div>
        
        <div class="row g-4" id="gallery-preview">
            <?php
            $sql = "SELECT * FROM gallery ORDER BY uploaded_at DESC LIMIT 8";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($photo = $result->fetch_assoc()) {
                    echo '<div class="col-lg-3 col-md-4 col-6">';
                    echo '<div class="gallery-item">';
                    echo '<img src="assets/images/gallery/' . htmlspecialchars($photo['image']) . '" alt="' . htmlspecialchars($photo['category']) . '" class="img-fluid">';
                    echo '<div class="gallery-overlay">';
                    echo '<div class="gallery-info">';
                    echo '<h5>' . htmlspecialchars($photo['category']) . '</h5>';
                    echo '<a href="gallery.php" class="btn btn-sm btn-outline-light">View Gallery</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="gallery.php" class="btn btn-primary btn-lg">View Full Gallery</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">What Our Clients Say</h2>
            <p class="lead text-muted">Real experiences from our valued customers</p>
        </div>
        
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="testimonial-item">
                        <div class="stars mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Absolutely amazing photography! The team captured our wedding day perfectly. Every photo tells a story and the quality is exceptional. Highly recommend!"</p>
                        <div class="testimonial-author">
                            <div>
                                <h6 class="mb-0">Sarah Johnson</h6>
                                <small class="text-muted">Wedding Client</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="carousel-item">
                    <div class="testimonial-item">
                        <div class="stars mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Professional, creative, and attentive to detail. Our corporate event photos exceeded expectations. The team made everyone feel comfortable and natural."</p>
                        <div class="testimonial-author">
                            <div>
                                <h6 class="mb-0">Michael Chen</h6>
                                <small class="text-muted">Corporate Client</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="carousel-item">
                    <div class="testimonial-item">
                        <div class="stars mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"The best photography experience! They captured the essence of our brand perfectly. Product photos that actually increased our sales significantly!"</p>
                        <div class="testimonial-author">
                            <div>
                                <h6 class="mb-0">Emily Rodriguez</h6>
                                <small class="text-muted">Product Client</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Ready to Capture Your Perfect Moments?</h2>
        <p class="lead mb-4">Book your photoshoot today and let us create beautiful memories together</p>
        <a href="booking.php" class="btn btn-light btn-lg me-3">Book a Shoot Now</a>
        <a href="contact.php" class="btn btn-outline-light btn-lg">Get in Touch</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
