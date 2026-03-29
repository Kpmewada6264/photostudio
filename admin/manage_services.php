<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Check if user is admin
if (!isAdmin()) {
    redirect('../index.php');
}

// Handle service actions
$success_message = '';
$error_message = '';

// Add new service
if (isset($_POST['add_service'])) {
    $title = sanitize($conn, $_POST['title']);
    $description = sanitize($conn, $_POST['description']);
    $price = sanitize($conn, $_POST['price']);
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_types)) {
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $upload_path = '../assets/images/services/' . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image = $file_name;
            } else {
                $error_message = "Failed to upload image";
            }
        } else {
            $error_message = "Invalid file type. Allowed types: JPG, PNG, GIF, WebP";
        }
    }
    
    if (empty($error_message)) {
        $sql = "INSERT INTO services (title, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $title, $description, $price, $image);
        
        if ($stmt->execute()) {
            $success_message = "Service added successfully!";
        } else {
            $error_message = "Failed to add service";
        }
    }
}

// Delete service
if (isset($_GET['delete'])) {
    $service_id = sanitize($conn, $_GET['delete']);
    
    // Get service image before deleting
    $sql = "SELECT image FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    
    if ($service && !empty($service['image'])) {
        // Delete file from server
        $file_path = '../assets/images/services/' . $service['image'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Delete from database
    $sql = "DELETE FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    
    if ($stmt->execute()) {
        $success_message = "Service deleted successfully!";
    } else {
        $error_message = "Failed to delete service";
    }
}

// Get all services
$services = [];
$sql = "SELECT * FROM services ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Get service statistics
$total_services = count($services);
$active_services = array_filter($services, function($service) {
    return !empty($service['image']);
});
$active_services_count = count($active_services);
?>

<style>
/* Attractive Admin Page Styles */
.admin-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin: -20px 0 0.5rem 0;
    position: relative;
    overflow: hidden;
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
                $image = $file_name;
            } else {
                $error_message = "Failed to upload service image";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, PNG, GIF, and WebP files are allowed.";
        }
    }
    
    if (empty($error_message)) {
        $sql = "INSERT INTO services (title, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $title, $description, $price, $image);
        
        if ($stmt->execute()) {
            $success_message = "Service added successfully!";
        } else {
            $error_message = "Failed to add service";
            if (!empty($image) && file_exists('../assets/images/services/' . $image)) {
                unlink('../assets/images/services/' . $image);
            }
        }
    }
}

// Update service
if (isset($_POST['update_service'])) {
    $service_id = sanitize($conn, $_POST['service_id']);
    $title = sanitize($conn, $_POST['title']);
    $description = sanitize($conn, $_POST['description']);
    $price = sanitize($conn, $_POST['price']);
    
    // Handle image upload
    $image_update = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_types)) {
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $upload_path = '../assets/images/services/' . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Get old image to delete
                $sql = "SELECT image FROM services WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $service_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $old_service = $result->fetch_assoc();
                    if (!empty($old_service['image']) && file_exists('../assets/images/services/' . $old_service['image'])) {
                        unlink('../assets/images/services/' . $old_service['image']);
                    }
                }
                
                $image_update = ", image = '" . $conn->real_escape_string($file_name) . "'";
            } else {
                $error_message = "Failed to upload service image";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, PNG, GIF, and WebP files are allowed.";
        }
    }
    
    if (empty($error_message)) {
        $sql = "UPDATE services SET title = ?, description = ?, price = ? $image_update WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $title, $description, $price, $service_id);
        
        if ($stmt->execute()) {
            $success_message = "Service updated successfully!";
        } else {
            $error_message = "Failed to update service";
        }
    }
}

// Delete service
if (isset($_GET['delete'])) {
    $service_id = sanitize($conn, $_GET['delete']);
    
    // Get service image before deleting
    $sql = "SELECT image FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
        
        // Delete from database
        $sql = "DELETE FROM services WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $service_id);
        
        if ($stmt->execute()) {
            // Delete image file
            if (!empty($service['image']) && file_exists('../assets/images/services/' . $service['image'])) {
                unlink('../assets/images/services/' . $service['image']);
            }
            $success_message = "Service deleted successfully!";
        } else {
            $error_message = "Failed to delete service";
        }
    }
}

// Get all services
$services = [];
$sql = "SELECT * FROM services ORDER BY id ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Get service for editing
$editing_service = null;
if (isset($_GET['edit'])) {
    $service_id = sanitize($conn, $_GET['edit']);
    $sql = "SELECT * FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $editing_service = $result->fetch_assoc();
    }
} else {
    // Initialize as empty array if not editing
    $editing_service = null;
}
?>

<!-- Admin Header -->
<div class="admin-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0">Manage Services</h1>
                <p class="text-muted mb-0">Add, edit, and manage photography services</p>
            </div>
            <div class="col-auto">
                <a href="dashboard.php" class="btn btn-outline-primary">
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

<!-- Services Management -->
<div class="container-fluid py-4">
    <?php 
    // Ensure $editing_service is always defined
    if (!isset($editing_service)) {
        $editing_service = null;
    }
    ?>
    <!-- Service Form -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><?php echo $editing_service ? 'Edit Service' : 'Add New Service'; ?></h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <?php if ($editing_service): ?>
                    <input type="hidden" name="service_id" value="<?php echo $editing_service['id']; ?>">
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Service Title</label>
                        <input type="text" class="form-control" id="title" name="title" required 
                               value="<?php echo $editing_service ? htmlspecialchars($editing_service['title']) : ''; ?>"
                               placeholder="e.g., Wedding Photography">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="number" class="form-control" id="price" name="price" required 
                               value="<?php echo $editing_service ? htmlspecialchars($editing_service['price']) : ''; ?>"
                               step="0.01" min="0" placeholder="1500.00">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required 
                              placeholder="Detailed description of the service..."><?php echo $editing_service ? htmlspecialchars($editing_service['description']) : ''; ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Service Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <small class="text-muted">
                        <?php if ($editing_service && !empty($editing_service['image'])): ?>
                            Current image: <a href="../assets/images/services/<?php echo htmlspecialchars($editing_service['image']); ?>" target="_blank">View</a>
                        <?php endif; ?>
                        <br>Supported formats: JPG, PNG, GIF, WebP (Max 5MB)
                    </small>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" name="<?php echo $editing_service ? 'update_service' : 'add_service'; ?>" class="btn btn-primary">
                        <i class="fas fa-<?php echo $editing_service ? 'save' : 'plus'; ?> me-2"></i>
                        <?php echo $editing_service ? 'Update Service' : 'Add Service'; ?>
                    </button>
                    <?php if ($editing_service): ?>
                        <a href="manage_services.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Services List -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="mb-0">Services List</h5>
            <div class="card-actions">
                <span class="badge bg-info"><?php echo count($services); ?> services</span>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($services)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($service['image'])): ?>
                                            <img src="../assets/images/services/<?php echo htmlspecialchars($service['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($service['title']); ?>" 
                                                 class="service-thumbnail">
                                        <?php else: ?>
                                            <div class="service-placeholder">
                                                <i class="fas fa-camera"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($service['title']); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo substr(htmlspecialchars($service['description']), 0, 100); ?>...
                                    </td>
                                    <td>
                                        <span class="badge bg-success">$<?php echo number_format($service['price'], 2); ?></span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($service['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="?edit=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this service?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-concierge-bell fa-3x text-muted mb-3"></i>
                                            <h5>No services found</h5>
                    <p class="text-muted">Add your first service to get started.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<style>
.service-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.service-placeholder {
    width: 60px;
    height: 60px;
    background: #e9ecef;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.5rem;
}

.table td {
    vertical-align: middle;
}
</style>

<?php require_once '../includes/footer.php'; ?>
