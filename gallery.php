<?php require_once 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/gallery-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="display-4 fw-bold text-white">Photo Gallery</h1>
            <p class="lead text-white">Explore our stunning photography collection</p>
        </div>
    </div>
</section>

<!-- Gallery Filter Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="text-center">
            <!-- Desktop View - Button Group -->
            <div class="btn-group d-none d-lg-flex" role="group">
                <button type="button" class="btn btn-outline-primary active" data-filter="all" style="background: transparent; border-color: #007bff; padding: 8px 12px; font-size: 14px;">All Photos</button>
                <button type="button" class="btn btn-outline-primary" data-filter="wedding" style="background: transparent; border-color: #007bff; padding: 8px 12px; font-size: 14px;">Wedding</button>
                <button type="button" class="btn btn-outline-primary" data-filter="prewedding" style="background: transparent; border-color: #007bff; padding: 8px 12px; font-size: 14px;">PreWedding</button>
                <button type="button" class="btn btn-outline-primary" data-filter="events" style="background: transparent; border-color: #007bff; padding: 8px 12px; font-size: 14px;">Events</button>
                <button type="button" class="btn btn-outline-primary" data-filter="portrait" style="background: transparent; border-color: #007bff; padding: 8px 12px; font-size: 14px;">Portrait</button>
                <button type="button" class="btn btn-outline-primary" data-filter="fashion" style="background: transparent; border-color: #007bff; padding: 8px 12px; font-size: 14px;">Fashion</button>
            </div>
            
            <!-- Mobile View - Dropdown -->
            <div class="dropdown d-lg-none">
                <button class="btn btn-primary dropdown-toggle w-100" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent; border: 1px solid #007bff; color: #007bff;">
                    <i class="fas fa-filter me-2"></i>Filter Photos
                </button>
                <ul class="dropdown-menu w-100" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item active" href="#" data-filter="all">All Photos</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="wedding">Wedding</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="prewedding">PreWedding</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="events">Events</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="portrait">Portrait</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="fashion">Fashion</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Grid Section -->
<section class="py-5">
    <style>
        /* Beautiful Gallery Page Animations */
        .gallery-card {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.8s ease-out;
            height: 100%;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        .gallery-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        }
        
        .gallery-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: all 0.4s ease;
            image-rendering: auto;
            image-rendering: -webkit-optimize-contrast;
            max-width: 100%;
            height: auto;
        }
        
        .gallery-card:hover .gallery-image {
            transform: scale(1.1);
            filter: brightness(1.1);
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.9), rgba(78, 205, 196, 0.9));
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.4s ease;
        }
        
        .gallery-card:hover .gallery-overlay {
            opacity: 1;
        }
        
        .gallery-content {
            text-align: center;
            color: white;
            transform: translateY(20px);
            transition: all 0.4s ease;
        }
        
        .gallery-card:hover .gallery-content {
            transform: translateY(0);
        }
        
        .gallery-content h5 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .gallery-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .gallery-actions .btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .gallery-actions .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* Gallery Stats */
        .gallery-stat {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .gallery-stat:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        }
        
        .gallery-stat h3 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }
        
        .gallery-stat p {
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 0;
        }
        
        /* Filter Buttons */
        .btn-group .btn {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #e9ecef;
            color: #495057;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            margin: 0 0.25rem;
        }
        
        .btn-group .btn:hover {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border-color: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
        }
        
        .btn-group .btn.active {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border-color: transparent;
            color: white;
        }
        
        /* Lightbox Modal */
        .modal-content {
            border-radius: 25px;
            overflow: hidden;
            border: none;
        }
        
        .modal-body {
            padding: 0;
            position: relative;
        }
        
        .modal-body img {
            width: 100%;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
            image-rendering: auto;
            image-rendering: -webkit-optimize-contrast;
            max-width: 100%;
        }
        
        .btn-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 10;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            opacity: 1 !important;
        }
        
        .btn-close:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.1);
        }
        
        .btn-close::before {
            content: '×';
            font-size: 24px;
            font-weight: bold;
            color: #333 !important;
            line-height: 1;
        }
        
        /* Increase modal z-index to appear above navbar */
        .modal {
            z-index: 9999 !important;
        }
        
        .modal-backdrop {
            z-index: 9998 !important;
        }
        
        .modal-dialog {
            margin-top: 100px !important;
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
        
        /* Staggered animations */
        .gallery-item:nth-child(1) { animation-delay: 0.1s; }
        .gallery-item:nth-child(2) { animation-delay: 0.2s; }
        .gallery-item:nth-child(3) { animation-delay: 0.3s; }
        .gallery-item:nth-child(4) { animation-delay: 0.4s; }
        .gallery-item:nth-child(5) { animation-delay: 0.5s; }
        .gallery-item:nth-child(6) { animation-delay: 0.6s; }
        .gallery-item:nth-child(7) { animation-delay: 0.7s; }
        .gallery-item:nth-child(8) { animation-delay: 0.8s; }
        .gallery-item:nth-child(9) { animation-delay: 0.9s; }
        
        .gallery-stat:nth-child(1) { animation-delay: 0.2s; }
        .gallery-stat:nth-child(2) { animation-delay: 0.4s; }
        .gallery-stat:nth-child(3) { animation-delay: 0.6s; }
        .gallery-stat:nth-child(4) { animation-delay: 0.8s; }
    </style>
    <div class="container">
        <div class="row g-4" id="gallery-grid">
            <?php
            require_once 'includes/db.php';
            require_once 'includes/image_optimizer.php';
            $sql = "SELECT * FROM gallery ORDER BY uploaded_at DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($photo = $result->fetch_assoc()) {
                    $category = strtolower($photo['category']);
                    echo '<div class="col-lg-4 col-md-6 gallery-item" data-category="' . $category . '">';
                    echo '<div class="gallery-card">';
                    $thumb_src = imageSrc('assets/images/gallery', $photo['image'], true);
                    $full_src = imageSrc('assets/images/gallery', $photo['image']);
                    echo '<img src="' . htmlspecialchars($thumb_src) . '" alt="' . htmlspecialchars($photo['category']) . '" class="img-fluid gallery-image" loading="lazy" decoding="async">';
                    echo '<div class="gallery-overlay">';
                    echo '<div class="gallery-content">';
                    echo '<h5>' . htmlspecialchars($photo['category']) . '</h5>';
                    echo '<p class="text-white mb-3">Click to view full size</p>';
                    echo '<div class="gallery-actions">';
                    echo '<button class="btn btn-light btn-sm me-2" onclick="openLightbox(\'' . htmlspecialchars($full_src) . '\')">';
                    echo '<i class="fas fa-search-plus"></i> View';
                    echo '</button>';
                    echo '<button class="btn btn-outline-light btn-sm" onclick="shareImage(\'' . htmlspecialchars($photo['image']) . '\')">';
                    echo '<i class="fas fa-share"></i> Share';
                    echo '</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-12 text-center">';
                echo '<p class="text-muted">No gallery images available yet.</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-body p-0">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                <img id="lightboxImage" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Gallery Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4">
                <div class="gallery-stat">
                    <h3 class="counter" data-target="1000">0</h3>
                    <p class="text-muted">Total Photos</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="gallery-stat">
                    <h3 class="counter" data-target="50">0</h3>
                    <p class="text-muted">Wedding Shoots</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="gallery-stat">
                    <h3 class="counter" data-target="75">0</h3>
                    <p class="text-muted">Portrait Sessions</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="gallery-stat">
                    <h3 class="counter" data-target="100" style="color: white !important;">0</h3>
                    <p style="color: white !important;">Happy Clients</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Collections Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Featured Collections</h2>
            <p class="lead text-muted">Curated photography collections by category</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="collection-card">
                    <div class="collection-image" style="background-image: url('<?php echo htmlspecialchars(imageSrc('assets/images/gallery', '1774365149_wedding.jpg')); ?>');">
                        <div class="collection-overlay">
                            <h3>Wedding Collection</h3>
                            <p>Beautiful moments from our wedding photography</p>
                            <a href="#" class="btn btn-light">View Collection</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="collection-card">
                    <div class="collection-image" style="background-image: url('<?php echo htmlspecialchars(imageSrc('assets/images/gallery', '1774366298_portrait6.jpg')); ?>');">
                        <div class="collection-overlay">
                            <h3>Portrait Collection</h3>
                            <p>Stunning portrait photography sessions</p>
                            <a href="#" class="btn btn-light">View Collection</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="collection-card">
                    <div class="collection-image" style="background-image: url('<?php echo htmlspecialchars(imageSrc('assets/images/gallery', '1774366725_fashion3.jpg')); ?>');">
                        <div class="collection-overlay">
                            <h3>Fashion Collection</h3>
                            <p>High-fashion editorial photography</p>
                            <a href="#" class="btn btn-light">View Collection</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Love What You See?</h2>
        <p class="lead mb-4">Let us create beautiful memories for you too</p>
        <a href="booking.php" class="btn btn-light btn-lg me-3">Book Your Shoot</a>
        <a href="services.php" class="btn btn-outline-light btn-lg">View Services</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<!-- Additional JavaScript for Gallery -->
<script>
// Gallery Filter
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            galleryItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // Lightbox functionality
    const galleryImages = document.querySelectorAll('.gallery-image');
    const lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
    const lightboxImage = document.getElementById('lightboxImage');
    
    galleryImages.forEach(image => {
        image.addEventListener('click', function() {
            lightboxImage.src = this.src;
            lightboxImage.alt = this.alt;
            lightboxModal.show();
        });
    });
});

// Share image function
function shareImage(imageName) {
    if (navigator.share) {
        navigator.share({
            title: 'Sanjay PhotoStudio Gallery',
            text: 'Check out this amazing photo from Sanjay PhotoStudio!',
            url: window.location.href
        });
    } else {
        // Fallback - copy to clipboard
        const dummy = document.createElement('input');
        document.body.appendChild(dummy);
        dummy.value = window.location.href;
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
        
        alert('Gallery link copied to clipboard!');
    }
}
</script>

<style>
.gallery-item {
    transition: all 0.3s ease;
    opacity: 1;
    transform: scale(1);
}

.gallery-card {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: transform 0.3s ease;
}

.gallery-card:hover {
    transform: translateY(-5px);
}

.gallery-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-card:hover .gallery-image {
    transform: scale(1.05);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    display: flex;
    align-items: flex-end;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-card:hover .gallery-overlay {
    opacity: 1;
}

.collection-card {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    height: 300px;
}

.collection-image {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    position: relative;
    transition: transform 0.3s ease;
}

.collection-card:hover .collection-image {
    transform: scale(1.05);
}

.collection-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
    padding: 30px;
    color: white;
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.collection-card:hover .collection-overlay {
    transform: translateY(0);
}

.gallery-stat h3 {
    font-size: 2.5rem;
    font-weight: bold;
    color: #007bff;
}
</style>

<script>
// Lightbox functionality
function openLightbox(imageSrc) {
    const modal = new bootstrap.Modal(document.getElementById('lightboxModal'));
    const lightboxImage = document.getElementById('lightboxImage');
    lightboxImage.src = imageSrc;
    modal.show();
}

// Share functionality
function shareImage(imageName) {
    if (navigator.share) {
        navigator.share({
            title: 'Sanjay PhotoStudio Gallery',
            text: 'Check out this amazing photo!',
            url: window.location.href
        }).then(() => {
            console.log('Thanks for sharing!');
        }).catch((error) => {
            console.log('Error sharing:', error);
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        const dummy = document.createElement('input');
        document.body.appendChild(dummy);
        dummy.value = window.location.href;
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
        alert('Gallery link copied to clipboard!');
    }
}

// Gallery filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter gallery items
            galleryItems.forEach(item => {
                const category = item.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // Smooth transitions for gallery items
    galleryItems.forEach(item => {
        item.style.transition = 'all 0.3s ease';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
