<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Check if user is admin
if (!isAdmin()) {
    redirect('../index.php');
}

// Handle booking actions
$success_message = '';
$error_message = '';

// Approve booking
if (isset($_GET['approve'])) {
    $booking_id = sanitize($conn, $_GET['approve']);
    $sql = "UPDATE bookings SET status = 'approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    
    if ($stmt->execute()) {
        $success_message = "Booking approved successfully!";
    } else {
        $error_message = "Failed to approve booking";
    }
}

// Reject booking
if (isset($_GET['reject'])) {
    $booking_id = sanitize($conn, $_GET['reject']);
    $sql = "UPDATE bookings SET status = 'rejected' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    
    if ($stmt->execute()) {
        $success_message = "Booking rejected successfully!";
    } else {
        $error_message = "Failed to reject booking";
    }
}

// Delete booking
if (isset($_GET['delete'])) {
    $booking_id = sanitize($conn, $_GET['delete']);
    $sql = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    
    if ($stmt->execute()) {
        $success_message = "Booking deleted successfully!";
    } else {
        $error_message = "Failed to delete booking";
    }
}

// Get all bookings
$bookings = [];
$sql = "SELECT * FROM bookings ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

// Filter by status
$status_filter = isset($_GET['status']) ? sanitize($conn, $_GET['status']) : 'all';
if ($status_filter !== 'all') {
    $bookings = array_filter($bookings, function($booking) use ($status_filter) {
        return $booking['status'] === $status_filter;
    });
}
?>

<!-- Admin Header -->
<div class="admin-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0">Manage Bookings</h1>
                <p class="text-muted mb-0">View and manage all booking requests</p>
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

<!-- Bookings Management -->
<div class="container-fluid py-4">
    <!-- Filter Tabs -->
    <div class="admin-card mb-4">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link <?php echo $status_filter == 'all' ? 'active' : ''; ?>" href="?status=all">
                        All Bookings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $status_filter == 'pending' ? 'active' : ''; ?>" href="?status=pending">
                        Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $status_filter == 'approved' ? 'active' : ''; ?>" href="?status=approved">
                        Approved
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $status_filter == 'rejected' ? 'active' : ''; ?>" href="?status=rejected">
                        Rejected
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Bookings Table -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="mb-0">Bookings List</h5>
            <div class="card-actions">
                <button class="btn btn-sm btn-outline-primary" onclick="exportBookings()">
                    <i class="fas fa-download me-2"></i>Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($bookings)): ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="bookingsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Event Type</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td>#<?php echo $booking['id']; ?></td>
                                    <td><?php echo htmlspecialchars($booking['name']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['email']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['event_type']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($booking['date'])); ?></td>
                                    <td><?php echo htmlspecialchars($booking['location']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $booking['status'] == 'pending' ? 'warning' : ($booking['status'] == 'approved' ? 'success' : 'danger'); ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, H:i', strtotime($booking['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info" onclick="viewBooking(<?php echo $booking['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if ($booking['status'] == 'pending'): ?>
                                                <a href="?approve=<?php echo $booking['id']; ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Approve this booking?')">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="?reject=<?php echo $booking['id']; ?>" class="btn btn-sm btn-outline-warning" onclick="return confirm('Reject this booking?')">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="?delete=<?php echo $booking['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this booking?')">
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
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5>No bookings found</h5>
                    <p class="text-muted">There are no bookings matching the current filter.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingDetails">
                <!-- Booking details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<script>
// View booking details
function viewBooking(bookingId) {
    // Simulate loading booking details (in real app, this would be an AJAX call)
    const bookings = <?php echo json_encode($bookings); ?>;
    const booking = bookings.find(b => b.id == bookingId);
    
    if (booking) {
        const detailsHtml = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Customer Information</h6>
                    <p><strong>Name:</strong> ${booking.name}</p>
                    <p><strong>Email:</strong> ${booking.email}</p>
                    <p><strong>Phone:</strong> ${booking.phone}</p>
                </div>
                <div class="col-md-6">
                    <h6>Booking Information</h6>
                    <p><strong>Event Type:</strong> ${booking.event_type}</p>
                    <p><strong>Date:</strong> ${new Date(booking.date).toLocaleDateString()}</p>
                    <p><strong>Location:</strong> ${booking.location}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${booking.status == 'pending' ? 'warning' : (booking.status == 'approved' ? 'success' : 'danger')}">${booking.status}</span></p>
                </div>
            </div>
            ${booking.message ? `
                <div class="mt-3">
                    <h6>Message</h6>
                    <p>${booking.message}</p>
                </div>
            ` : ''}
            <div class="mt-3">
                <h6>Created</h6>
                <p>${new Date(booking.created_at).toLocaleString()}</p>
            </div>
        `;
        
        document.getElementById('bookingDetails').innerHTML = detailsHtml;
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }
}

// Export bookings
function exportBookings() {
    const table = document.getElementById('bookingsTable');
    let csv = [];
    
    // Get headers
    const headers = [];
    table.querySelectorAll('thead th').forEach(th => {
        headers.push(th.textContent.trim());
    });
    csv.push(headers.join(','));
    
    // Get rows
    table.querySelectorAll('tbody tr').forEach(tr => {
        const row = [];
        tr.querySelectorAll('td').forEach((td, index) => {
            if (index < 9) { // Exclude actions column
                row.push(td.textContent.trim());
                            }
        });
        csv.push(row.join(','));
    });
    
    // Download CSV
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'bookings_export.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Initialize DataTable
$(document).ready(function() {
    $('#bookingsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'desc']]
    });
});
</script>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
