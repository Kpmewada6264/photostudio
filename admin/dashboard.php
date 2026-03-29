<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Check if user is admin
if (!isAdmin()) {
    redirect('../index.php');
}

// Get dashboard statistics
$total_bookings = 0;
$total_users = 0;
$total_gallery = 0;
$total_services = 0;
$pending_bookings = 0;
$recent_bookings = [];
$recent_messages = [];

// Get total counts
$sql = "SELECT COUNT(*) as count FROM bookings";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $total_bookings = $result->fetch_assoc()['count'];
}

$sql = "SELECT COUNT(*) as count FROM users WHERE email != 'admin@photostudio.com'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $total_users = $result->fetch_assoc()['count'];
}

$sql = "SELECT COUNT(*) as count FROM gallery";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $total_gallery = $result->fetch_assoc()['count'];
}

$sql = "SELECT COUNT(*) as count FROM services";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $total_services = $result->fetch_assoc()['count'];
}

$sql = "SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $pending_bookings = $result->fetch_assoc()['count'];
}

// Get recent bookings
$sql = "SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $recent_bookings[] = $row;
    }
}

// Get recent messages
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $recent_messages[] = $row;
    }
}
?>

<!-- Admin Dashboard Header -->
<div class="admin-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0">Admin Dashboard</h1>
                <p class="text-muted mb-0">Welcome back, <?php echo $_SESSION['user_name']; ?>!</p>
            </div>
            <div class="col-auto">
                <span class="badge bg-success">Online</span>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-primary">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $total_bookings; ?></h3>
                    <p>Total Bookings</p>
                    <small class="text-muted">
                        <?php if ($pending_bookings > 0): ?>
                            <span class="badge bg-warning"><?php echo $pending_bookings; ?> pending</span>
                        <?php endif; ?>
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-success">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $total_users; ?></h3>
                    <p>Total Users</p>
                    <small class="text-muted">Registered customers</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-info">
                <div class="stat-icon">
                    <i class="fas fa-images"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $total_gallery; ?></h3>
                    <p>Gallery Photos</p>
                    <small class="text-muted">Across all categories</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-warning">
                <div class="stat-icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $total_services; ?></h3>
                    <p>Services</p>
                    <small class="text-muted">Active photography packages</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="row g-4 mt-2">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Bookings</h5>
                    <a href="manage_bookings.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_bookings)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Event Type</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_bookings as $booking): ?>
                                        <tr>
                                            <td>#<?php echo $booking['id']; ?></td>
                                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['email']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['event_type']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($booking['date'])); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $booking['status'] == 'pending' ? 'warning' : ($booking['status'] == 'approved' ? 'success' : 'danger'); ?>">
                                                    <?php echo ucfirst($booking['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="manage_bookings.php?edit=<?php echo $booking['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No bookings found</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Messages</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_messages)): ?>
                        <div class="message-list">
                            <?php foreach ($recent_messages as $message): ?>
                                <div class="message-item">
                                    <div class="message-header">
                                        <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                                        <small class="text-muted"><?php echo date('M d, H:i', strtotime($message['created_at'])); ?></small>
                                    </div>
                                    <div class="message-subject">
                                        <?php echo htmlspecialchars($message['subject']); ?>
                                    </div>
                                    <div class="message-preview">
                                        <?php echo substr(htmlspecialchars($message['message']), 0, 50); ?>...
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No messages found</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="admin-card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="manage_bookings.php" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-check me-2"></i>Manage Bookings
                        </a>
                        <a href="manage_gallery.php" class="btn btn-outline-info">
                            <i class="fas fa-images me-2"></i>Manage Gallery
                        </a>
                        <a href="manage_services.php" class="btn btn-outline-success">
                            <i class="fas fa-concierge-bell me-2"></i>Manage Services
                        </a>
                        <a href="manage_users.php" class="btn btn-outline-warning">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="row g-4 mt-2">
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0">Booking Status Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="bookingChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0">Service Popularity</h5>
                </div>
                <div class="card-body">
                    <canvas id="serviceChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Booking Status Chart
const bookingCtx = document.getElementById('bookingChart').getContext('2d');
const bookingChart = new Chart(bookingCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Approved', 'Rejected'],
        datasets: [{
            data: [
                <?php 
                $sql = "SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'";
                $result = $conn->query($sql);
                echo $result->fetch_assoc()['count'];
                ?>,
                <?php 
                $sql = "SELECT COUNT(*) as count FROM bookings WHERE status = 'approved'";
                $result = $conn->query($sql);
                echo $result->fetch_assoc()['count'];
                ?>,
                <?php 
                $sql = "SELECT COUNT(*) as count FROM bookings WHERE status = 'rejected'";
                $result = $conn->query($sql);
                echo $result->fetch_assoc()['count'];
                ?>
            ],
            backgroundColor: [
                '#ffc107',
                '#28a745',
                '#dc3545'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Service Popularity Chart
const serviceCtx = document.getElementById('serviceChart').getContext('2d');
const serviceChart = new Chart(serviceCtx, {
    type: 'bar',
    data: {
        labels: ['Wedding', 'Pre-Wedding', 'Birthday', 'Event', 'Product', 'Portfolio'],
        datasets: [{
            label: 'Number of Bookings',
            data: [
                <?php 
                $sql = "SELECT COUNT(*) as count FROM bookings WHERE event_type LIKE '%Wedding%'";
                $result = $conn->query($sql);
                echo $result->fetch_assoc()['count'];
                ?>,
                <?php 
                $sql = "SELECT COUNT(*) as count FROM bookings WHERE event_type LIKE '%Pre%'";
                $result = $conn->query($sql);
                echo $result->fetch_assoc()['count'];
                ?>,
                <?php 
                $sql = "SELECT COUNT(*) as count FROM bookings WHERE event_type LIKE '%Birthday%'";
                $result = $conn->query($sql);
                echo $result->fetch_assoc()['count'];
                ?>,
                <?php 
                $sql = "SELECT COUNT(*) as count FROM bookings WHERE event_type LIKE '%Event%'";
                $result = $conn->query($sql);
                echo $result->fetch_assoc()['count'];
                ?>,
                <?php 
                $sql = "SELECT COUNT(*) as count FROM bookings WHERE event_type LIKE '%Product%'";
                $result = $conn->query($sql);
                echo $result->fetch_assoc()['count'];
                ?>,
                <?php 
                $sql = "SELECT COUNT(*) as count FROM bookings WHERE event_type LIKE '%Portfolio%'";
                $result = $conn->query($sql);
                echo $result->fetch_assoc()['count'];
                ?>
            ],
            backgroundColor: '#007bff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
