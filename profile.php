<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

// Get user's bookings
$user_email = $_SESSION['user_email'];
$sql = "SELECT * FROM bookings WHERE email = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    $error_message = "Error preparing statement: " . $conn->error;
    $recent_bookings = [];
} else {
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $recent_bookings = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!-- Page Header -->
<section class="page-header" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/profile-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="display-4 fw-bold text-white">My Profile</h1>
            <p class="lead text-white">Manage your account and view your booking history</p>
        </div>
    </div>
</section>

<!-- Profile Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- User Info Card -->
            <div class="col-lg-4 mb-4">
                <div class="profile-card">
                    <div class="card-body text-center">
                        <div class="profile-avatar mb-3">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h4 class="mb-2"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h4>
                        <p class="text-muted"><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                        <div class="profile-stats">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h5 class="text-primary"><?php echo count($recent_bookings); ?></h5>
                                    <small class="text-muted">Bookings</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="text-primary">0</h5>
                                    <small class="text-muted">Photos</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="text-primary">1</h5>
                                    <small class="text-muted">Year</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Bookings -->
            <div class="col-lg-8">
                <div class="admin-card">
                    <div class="card-header">
                        <h4 class="mb-0">Recent Bookings</h4>
                        <div class="card-actions">
                            <a href="my_bookings.php" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($recent_bookings)): ?>
                            <div class="bookings-list">
                                <?php foreach ($recent_bookings as $booking): ?>
                                    <div class="booking-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="mb-1">Booking #<?php echo $booking['id']; ?></h6>
                                                <p class="mb-1">
                                                    <i class="fas fa-calendar me-2"></i>
                                                    <?php echo date('F d, Y', strtotime($booking['date'])); ?>
                                                </p>
                                                <p class="mb-1">
                                                    <i class="fas fa-tag me-2"></i>
                                                    <?php echo htmlspecialchars($booking['event_type']); ?>
                                                </p>
                                                <p class="mb-0 text-muted small">
                                                    <i class="fas fa-clock me-2"></i>
                                                    Booked on <?php echo date('M d, Y', strtotime($booking['created_at'])); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <?php 
                                                $status = strtolower($booking['status']);
                                                if ($status == 'approved') {
                                                    echo '<span class="badge bg-success">Approved</span>';
                                                } elseif ($status == 'rejected') {
                                                    echo '<span class="badge bg-danger">Rejected</span>';
                                                } elseif ($status == 'completed') {
                                                    echo '<span class="badge bg-primary">Completed</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning text-dark">Pending</span>';
                                                }
                                                ?>
                                                <div class="mt-2">
                                                    <a href="my_bookings.php?view=<?php echo $booking['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5>No bookings found</h5>
                                <p class="text-muted">You haven't made any bookings yet. Book your first photoshoot now!</p>
                                <a href="booking.php" class="btn btn-primary">
                                    <i class="fas fa-camera"></i> Book Now
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.profile-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 25px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    animation: slideInUp 0.8s ease-out;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    margin: 0 auto;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.booking-item {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #4ECDC4;
    transition: all 0.3s ease;
}

.booking-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

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
</style>

<?php require_once 'includes/footer.php'; ?>
