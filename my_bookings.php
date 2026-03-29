<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

// Get user's bookings
$user_email = $_SESSION['user_email'];
$sql = "SELECT * FROM bookings WHERE email = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Handle SQL error
    $error_message = "Error preparing statement: " . $conn->error;
    $bookings = [];
} else {
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle view booking details
$booking_details = null;
if (isset($_GET['view'])) {
    $booking_id = sanitize($conn, $_GET['view']);
    $sql = "SELECT * FROM bookings WHERE id = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        $error_message = "Error preparing statement: " . $conn->error;
    } else {
        $stmt->bind_param("is", $booking_id, $user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking_details = $result->fetch_assoc();
    }
}
?>

<!-- Page Header -->
<section class="page-header" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/booking-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="display-4 fw-bold text-white">My Bookings</h1>
            <p class="lead text-white">View and manage your photography bookings</p>
        </div>
    </div>
</section>

<!-- Bookings Section -->
<section class="py-5">
    <div class="container">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <?php if ($booking_details): ?>
            <!-- Booking Details Modal/Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="booking-details-card">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Booking #<?php echo $booking_details['id']; ?></h4>
                            <button class="btn btn-light btn-sm" onclick="history.back()">
                                <i class="fas fa-arrow-left"></i> Back to Bookings
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="booking-info-item">
                                        <label class="info-label">Full Name</label>
                                        <p class="info-value"><?php echo htmlspecialchars($booking_details['name']); ?></p>
                                    </div>
                                    <div class="booking-info-item">
                                        <label class="info-label">Email Address</label>
                                        <p class="info-value"><?php echo htmlspecialchars($booking_details['email']); ?></p>
                                    </div>
                                    <div class="booking-info-item">
                                        <label class="info-label">Phone Number</label>
                                        <p class="info-value"><?php echo htmlspecialchars($booking_details['phone']); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="booking-info-item">
                                        <label class="info-label">Event Type</label>
                                        <p class="info-value"><?php echo htmlspecialchars($booking_details['event_type']); ?></p>
                                    </div>
                                    <div class="booking-info-item">
                                        <label class="info-label">Preferred Date</label>
                                        <p class="info-value"><?php echo date('F d, Y', strtotime($booking_details['date'])); ?></p>
                                    </div>
                                    <div class="booking-info-item">
                                        <label class="info-label">Location</label>
                                        <p class="info-value"><?php echo htmlspecialchars($booking_details['location']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($booking_details['message'])): ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="booking-info-item">
                                        <label class="info-label">Additional Message</label>
                                        <p class="info-value"><?php echo nl2br(htmlspecialchars($booking_details['message'])); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="booking-info-item">
                                        <label class="info-label">Booking Date</label>
                                        <p class="info-value"><?php echo date('F d, Y h:i A', strtotime($booking_details['created_at'])); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="booking-info-item">
                                        <label class="info-label">Status</label>
                                        <p class="info-value">
                                            <?php 
                                            $status = strtolower($booking_details['status']);
                                            if ($status == 'approved') {
                                                echo '<span class="badge bg-success fs-6">Approved</span>';
                                            } elseif ($status == 'rejected') {
                                                echo '<span class="badge bg-danger fs-6">Rejected</span>';
                                            } elseif ($status == 'completed') {
                                                echo '<span class="badge bg-primary fs-6">Completed</span>';
                                            } else {
                                                echo '<span class="badge bg-warning text-dark fs-6">Pending</span>';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <?php 
                                    $status = strtolower($booking_details['status']);
                                    if ($status == 'approved') {
                                        echo '<div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Booking Approved!</strong> Your booking has been approved. Please proceed with the payment as discussed.
                                        </div>';
                                    } elseif ($status == 'rejected') {
                                        echo '<div class="alert alert-danger">
                                            <i class="fas fa-times-circle me-2"></i>
                                            <strong>Booking Rejected</strong> Unfortunately, your booking could not be accommodated. Please contact us for alternative dates.
                                        </div>';
                                    } elseif ($status == 'completed') {
                                        echo '<div class="alert alert-primary">
                                            <i class="fas fa-check-double me-2"></i>
                                            <strong>Booking Completed</strong> Your photoshoot has been successfully completed. Photos will be delivered as discussed.
                                        </div>';
                                    } else {
                                        echo '<div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Next Steps:</strong> We will contact you within 24 hours to confirm your booking details and discuss your requirements.
                                        </div>';
                                    }
                                    ?>
                                    <div class="alert alert-success">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Payment Information:</strong> 50% advance payment required to confirm booking. Remaining payment due on photoshoot day.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Bookings List -->
            <div class="row">
                <div class="col-12">
                    <div class="admin-card">
                        <div class="card-header">
                            <h4 class="mb-0">Your Bookings</h4>
                            <div class="card-actions">
                                <span class="badge bg-info"><?php echo count($bookings); ?> bookings</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($bookings)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Event Type</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bookings as $booking): ?>
                                            <tr>
                                                <td>#<?php echo $booking['id']; ?></td>
                                                <td><?php echo htmlspecialchars($booking['event_type']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($booking['date'])); ?></td>
                                                <td>
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
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($booking['created_at'])); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" onclick="viewBooking(<?php echo $booking['id']; ?>)">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h4>No Bookings Found</h4>
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
        <?php endif; ?>
    </div>
</section>

<style>
/* Booking Details Styling */
.booking-details-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 25px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    animation: slideInUp 0.8s ease-out;
}

.booking-details-card .card-header {
    background: linear-gradient(135deg, #FF6B6B, #4ECDC4) !important;
    border: none;
    padding: 1.5rem;
}

.booking-info-item {
    margin-bottom: 1.5rem;
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 15px;
    border-left: 4px solid #4ECDC4;
    transition: all 0.3s ease;
}

.booking-info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.info-label {
    font-weight: 700;
    color: #495057;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 0.5rem;
}

.info-value {
    color: #212529;
    font-size: 1rem;
    margin: 0;
    font-weight: 500;
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

<script>
function viewBooking(bookingId) {
    window.location.href = '?view=' + bookingId;
}
</script>

<?php require_once 'includes/footer.php'; ?>
