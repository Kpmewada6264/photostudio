<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Check if user is admin
if (!isAdmin()) {
    redirect('../index.php');
}

// Handle gallery actions
$success_message = '';
$error_message = '';

// Upload photo
if (isset($_POST['upload_photo'])) {
    $category = sanitize($conn, $_POST['category']);
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_types)) {
            $file_name = time() . '_' . basename($_FILES['photo']['name']);
            $upload_path = '../assets/images/gallery/' . $file_name;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                $sql = "INSERT INTO gallery (image, category) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $file_name, $category);
                
                if ($stmt->execute()) {
                    $success_message = "Photo uploaded successfully!";
                } else {
                    $error_message = "Failed to save photo to database";
                }
            } else {
                $error_message = "Failed to upload photo";
            }
        } else {
            $error_message = "Invalid file type. Allowed types: JPG, PNG, GIF, WebP";
        }
    } else {
        $error_message = "Please select a photo to upload";
    }
}

// Delete photo
if (isset($_GET['delete'])) {
    $photo_id = sanitize($conn, $_GET['delete']);
    
    // Get photo file name before deleting
    $sql = "SELECT image FROM gallery WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $photo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $photo = $result->fetch_assoc();
    
    if ($photo) {
        // Delete file from server
        $file_path = '../assets/images/gallery/' . $photo['image'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Delete from database
        $sql = "DELETE FROM gallery WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $photo_id);
        
        if ($stmt->execute()) {
            $success_message = "Photo deleted successfully!";
        } else {
            $error_message = "Failed to delete photo";
        }
    }
}

// Get all photos
$photos = [];
$sql = "SELECT * FROM gallery ORDER BY uploaded_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $photos[] = $row;
    }
} elseif (!$result) {
    $error_message = "Error fetching photos: " . $conn->error;
}

// Get gallery statistics
$total_photos = count($photos);
$categories = ['wedding', 'portrait', 'events', 'commercial'];
$category_counts = [];
foreach ($categories as $cat) {
    $category_counts[$cat] = count(array_filter($photos, function($photo) use ($cat) {
        return $photo['category'] == $cat;
    }));
}

// Filter photos based on category
$category_filter = $_GET['category'] ?? 'all';
$gallery_photos = $photos;

if ($category_filter !== 'all') {
    $gallery_photos = array_filter($photos, function($photo) use ($category_filter) {
        return strtolower($photo['category']) === strtolower($category_filter);
    });
}
?>

<style>
/* Attractive Admin Page Styles - Updated v2.0 */
.admin-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    padding: 2rem 0;
    margin: 0 0 0.5rem 0;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.admin-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,133.3C960,128,1056,96,1152,90.7C1248,85,1344,107,1392,117.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
}

.admin-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    animation: fadeInUp 0.8s ease;
}

.admin-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    animation: fadeInUp 0.8s ease 0.2s both;
}

.admin-header .btn-outline-primary {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.admin-header .btn-outline-primary:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Gallery Statistics Cards */
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(102, 126, 234, 0.1);
    animation: fadeInUp 0.8s ease 0.4s both;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.stat-card:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 30px 60px rgba(102, 126, 234, 0.2);
}

.stat-card.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card.bg-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.stat-card.bg-info {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
    color: white;
}

.stat-card.bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: white;
}

.stat-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
    animation: pulse 2s infinite;
}

.stat-card h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Gallery Upload Form Enhancement */
.admin-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    border: none;
    overflow: hidden;
    animation: fadeInUp 0.8s ease 0.6s both;
    position: relative;
}

.admin-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.admin-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.5rem;
    font-weight: 600;
    font-size: 1.2rem;
    position: relative;
    overflow: hidden;
}

.admin-card .card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
    opacity: 0.3;
}

.admin-card .card-header h5 {
    margin: 0;
    position: relative;
    z-index: 1;
}

.admin-card .card-body {
    padding: 2.5rem;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

/* Enhanced Form Styling */
.upload-form-container {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    border: 2px solid #f0f0f0;
    transition: all 0.3s ease;
}

.upload-form-container:hover {
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.15);
    border-color: #667eea;
}

.form-label {
    font-weight: 700;
    color: #333;
    margin-bottom: 0.8rem;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    display: flex;
    align-items: center;
}

.form-label::before {
    content: '';
    width: 4px;
    height: 4px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    margin-right: 8px;
    animation: pulse 2s infinite;
}

.form-control, .form-select {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 1rem 1.2rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
    position: relative;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
}

.form-control:hover, .form-select:hover {
    border-color: #667eea;
    background: white;
}

/* File Input Enhancement */
input[type="file"] {
    cursor: pointer;
}

input[type="file"]::-webkit-file-upload-button {
    visibility: hidden;
}

input[type="file"]::before {
    content: '📁 Choose Photo';
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    margin-right: 1rem;
    transition: all 0.3s ease;
}

input[type="file"]:hover::before {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

/* Enhanced Button Styling */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem 2.5rem;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-primary:hover::before {
    left: 100%;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

.btn-primary:active {
    transform: translateY(-1px);
}

/* Upload Area Enhancement */
.upload-area {
    border: 3px dashed #667eea;
    border-radius: 15px;
    padding: 3rem;
    text-align: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.upload-area::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.upload-area:hover::before {
    opacity: 1;
}

.upload-area:hover {
    border-color: #764ba2;
    background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
}

.upload-icon {
    font-size: 3rem;
    color: #667eea;
    margin-bottom: 1rem;
    animation: pulse 2s infinite;
}

.upload-text {
    color: #333;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.upload-subtext {
    color: #666;
    font-size: 0.9rem;
}

/* Form Group Enhancement */
.form-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.form-group.has-error .form-control {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.form-group.has-success .form-control {
    border-color: #28a745;
    box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
}

/* Success Message Enhancement */
.upload-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    animation: fadeInUp 0.5s ease;
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
}

/* Error Message Enhancement */
.upload-error {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    animation: fadeInUp 0.5s ease;
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
}

/* Filter Tabs Enhancement */
.nav-pills .nav-link {
    background: #f8f9fa;
    color: #667eea;
    border-radius: 10px;
    padding: 0.8rem 1.5rem;
    margin: 0 0.3rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.nav-pills .nav-link:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

/* Gallery Grid Enhancement */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    animation: fadeInUp 0.8s ease 0.8s both;
}

.gallery-item {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    transition: all 0.4s ease;
    position: relative;
}

.gallery-item:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 25px 50px rgba(102, 126, 234, 0.2);
}

.gallery-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: all 0.4s ease;
}

.gallery-item:hover img {
    transform: scale(1.1);
}

.gallery-item-info {
    padding: 1.5rem;
    background: white;
}

.gallery-item-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.gallery-item-category {
    color: #667eea;
    font-size: 0.9rem;
    font-weight: 500;
}

.gallery-item-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    opacity: 0;
    transition: all 0.3s ease;
}

.gallery-item:hover .gallery-item-actions {
    opacity: 1;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    border-radius: 10px;
    padding: 0.5rem 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
}

/* Animations */
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
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}
</style>

<!-- JavaScript for Upload Area -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('photo');
    
    if (uploadArea && fileInput) {
        // Click to upload
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });
        
        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#764ba2';
            uploadArea.style.background = 'linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%)';
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#667eea';
            uploadArea.style.background = 'linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%)';
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#667eea';
            uploadArea.style.background = 'linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%)';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                updateUploadArea(files[0]);
            }
        });
        
        // File input change
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                updateUploadArea(e.target.files[0]);
            }
        });
        
        function updateUploadArea(file) {
            const uploadText = uploadArea.querySelector('.upload-text');
            const uploadSubtext = uploadArea.querySelector('.upload-subtext');
            const uploadIcon = uploadArea.querySelector('.upload-icon i');
            
            if (file) {
                uploadText.textContent = `Selected: ${file.name}`;
                uploadSubtext.textContent = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
                uploadIcon.className = 'fas fa-check-circle';
                uploadArea.style.borderColor = '#28a745';
            }
        }
    }
});
</script>

<!-- Admin Header -->
<div class="admin-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; color: white !important; padding: 2rem 0; margin: 0 0 0.5rem 0; position: relative; overflow: hidden; z-index: 1;">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0" style="font-size: 2.5rem; font-weight: 700; text-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);">Manage Gallery</h1>
                <p class="text-muted mb-0" style="font-size: 1.1rem; opacity: 0.9;">Upload and manage gallery photos</p>
            </div>
            <div class="col-auto">
                <a href="dashboard.php" class="btn btn-outline-primary" style="background: rgba(255, 255, 255, 0.2); border: 2px solid rgba(255, 255, 255, 0.3); color: white; padding: 0.75rem 1.5rem; border-radius: 25px; font-weight: 600; backdrop-filter: blur(10px);">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Messages -->
<?php if ($success_message): ?>
    <div class="container-fluid mt-3">
        <?php echo successMessage($success_message); ?>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="container-fluid mt-3">
        <?php echo errorMessage($error_message); ?>
    </div>
<?php endif; ?>

<!-- Gallery Statistics -->
<div class="container-fluid py-2">
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary">
                <div class="stat-icon">
                    <i class="fas fa-images"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $total_photos; ?></h3>
                    <p>Total Photos</p>
                    <small class="text-white">All gallery photos</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card bg-success">
                <div class="stat-icon">
                    <i class="fas fa-ring"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $category_counts['wedding'] ?? 0; ?></h3>
                    <p>Wedding</p>
                    <small class="text-white">Wedding photos</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card bg-info">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $category_counts['portrait'] ?? 0; ?></h3>
                    <p>Portrait</p>
                    <small class="text-white">Portrait photos</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card bg-warning">
                <div class="stat-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $category_counts['events'] ?? 0; ?></h3>
                    <p>Events</p>
                    <small class="text-white">Event photos</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Management -->
<div class="container-fluid py-4">
    <!-- Upload Form -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-cloud-upload-alt me-2"></i>Upload New Photo</h5>
        </div>
        <div class="card-body">
            <div class="upload-form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Wedding">💒 Wedding</option>
                                    <option value="PreWedding">💑 PreWedding</option>
                                    <option value="Events">🎉 Events</option>
                                    <option value="Portrait">📸 Portrait</option>
                                    <option value="Fashion">👗 Fashion</option>
                                    <option value="Other">📷 Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="photo" class="form-label">Photo</label>
                                <div class="upload-area" id="uploadArea">
                                    <div class="upload-icon">
                                        <i class="fas fa-images"></i>
                                    </div>
                                    <div class="upload-text">Drop photo here or click to browse</div>
                                    <div class="upload-subtext">JPG, PNG, GIF, WebP (Max 5MB)</div>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required style="display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" name="upload_photo" class="btn btn-primary btn-lg">
                            <i class="fas fa-upload me-2"></i>Upload Photo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Filter Tabs -->
    <div class="admin-card mb-4">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link <?php echo $category_filter == 'all' ? 'active' : ''; ?>" href="?category=all">
                        All Photos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $category_filter == 'Wedding' ? 'active' : ''; ?>" href="?category=Wedding">
                        Wedding
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $category_filter == 'PreWedding' ? 'active' : ''; ?>" href="?category=PreWedding">
                        PreWedding
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $category_filter == 'Events' ? 'active' : ''; ?>" href="?category=Events">
                        Events
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $category_filter == 'Portrait' ? 'active' : ''; ?>" href="?category=Portrait">
                        Portrait
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $category_filter == 'Fashion' ? 'active' : ''; ?>" href="?category=Fashion">
                        Fashion
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Gallery Grid -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="mb-0">Gallery Photos</h5>
            <div class="card-actions">
                <span class="badge bg-info"><?php echo count($gallery_photos); ?> photos</span>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($gallery_photos)): ?>
                <div class="row g-3" id="galleryGrid">
                    <?php foreach ($gallery_photos as $photo): ?>
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                            <div class="gallery-item-admin">
                                <div class="gallery-image-container">
                                    <img src="../assets/images/gallery/<?php echo htmlspecialchars($photo['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($photo['category']); ?>" 
                                         class="img-fluid gallery-thumbnail"
                                         onclick="viewPhoto('../assets/images/gallery/<?php echo htmlspecialchars($photo['image']); ?>')">
                                    <div class="gallery-overlay-admin">
                                        <div class="gallery-actions-admin">
                                            <button class="btn btn-sm btn-light" onclick="viewPhoto('../assets/images/gallery/<?php echo htmlspecialchars($photo['image']); ?>')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="?delete=<?php echo $photo['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Delete this photo?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="gallery-info-admin">
                                    <small class="text-muted"><?php echo htmlspecialchars($photo['category']); ?></small>
                                    <br>
                                    <small><?php echo date('M d, Y', strtotime($photo['uploaded_at'])); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <h5>No photos found</h5>
                    <p class="text-muted">Upload some photos to get started.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Photo View Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <style>
        /* Admin Gallery Lightbox Modal Fix */
        #photoModal {
            z-index: 9999 !important;
        }
        
        #photoModal .modal-backdrop {
            z-index: 9998 !important;
        }
        
        #photoModal .modal-dialog {
            margin-top: 100px !important;
        }
        
        #photoModal .modal-content {
            border-radius: 25px;
            overflow: hidden;
            border: none;
        }
        
        #photoModal .modal-body {
            padding: 0;
            position: relative;
        }
        
        #photoModal .modal-body img {
            width: 100%;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
            image-rendering: auto;
            image-rendering: -webkit-optimize-contrast;
            max-width: 100%;
        }
        
        #photoModal .btn-close {
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
        
        #photoModal .btn-close:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.1);
        }
        
        #photoModal .btn-close::before {
            content: '×';
            font-size: 24px;
            font-weight: bold;
            color: #333 !important;
            line-height: 1;
        }
    </style>
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-body p-0">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<script>
// View photo in modal
function viewPhoto(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('photoModal')).show();
}

// Drag and drop functionality
const dropZone = document.getElementById('photo');
if (dropZone) {
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            this.files = files;
        }
    });
}

// Initialize sortable for gallery grid
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('galleryGrid');
    if (grid && typeof Sortable !== 'undefined') {
        new Sortable(grid, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                // Here you would typically update the order in the database
                console.log('Gallery order changed');
            }
        });
    }
});
</script>

<style>
.gallery-item-admin {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.gallery-item-admin:hover {
    transform: translateY(-5px);
}

.gallery-image-container {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
}

.gallery-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.gallery-item-admin:hover .gallery-thumbnail {
    transform: scale(1.05);
}

.gallery-overlay-admin {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item-admin:hover .gallery-overlay-admin {
    opacity: 1;
}

.gallery-actions-admin {
    display: flex;
    gap: 10px;
}

.gallery-info-admin {
    padding: 8px;
    background: #f8f9fa;
    font-size: 0.8rem;
}

.sortable-ghost {
    opacity: 0.4;
}

.dragover {
    border-color: #007bff !important;
    background-color: #e3f2fd !important;
}
</style>

<!-- Sortable.js for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
