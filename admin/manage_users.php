<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Check if user is admin
if (!isAdmin()) {
    redirect('../index.php');
}

// Handle user actions
$success_message = '';
$error_message = '';

// Delete user
if (isset($_GET['delete'])) {
    $user_id = sanitize($conn, $_GET['delete']);
    
    // Don't allow deletion of admin user
    if ($user_id == 1) {
        $error_message = "Cannot delete admin user";
    } else {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            $success_message = "User deleted successfully!";
        } else {
            $error_message = "Failed to delete user";
        }
    }
}

// Get all users (excluding admin)
$users = [];
$sql = "SELECT * FROM users WHERE email != 'admin@photostudio.com' ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Get user statistics
$total_users = count($users);
$recent_users = array_filter($users, function($user) {
    return strtotime($user['created_at']) > strtotime('-30 days');
});
$recent_users_count = count($recent_users);
?>

<!-- Admin Header -->
<div class="admin-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0">Manage Users</h1>
                <p class="text-muted mb-0">View and manage registered users</p>
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

<!-- Users Management -->
<div class="container-fluid py-4">
    <!-- User Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-primary">
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
        
        <div class="col-md-4">
            <div class="stat-card bg-success">
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $recent_users_count; ?></h3>
                    <p>New Users</p>
                    <small class="text-muted">Last 30 days</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card bg-info">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $total_users > 0 ? round(($recent_users_count / $total_users) * 100, 1) : 0; ?>%</h3>
                    <p>Growth Rate</p>
                    <small class="text-muted">Monthly increase</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Users List -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="mb-0">Registered Users</h5>
            <div class="card-actions">
                <button class="btn btn-sm btn-outline-primary" onclick="exportUsers()">
                    <i class="fas fa-download me-2"></i>Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($users)): ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="usersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registration Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>#<?php echo $user['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['phone'] ?: 'Not provided'); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info" onclick="viewUser(<?php echo $user['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary" onclick="emailUser('<?php echo htmlspecialchars($user['email']); ?>')">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                            <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user? This action cannot be undone.')">
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
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5>No users found</h5>
                                            <p class="text-muted">No users have registered yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetails">
                <!-- User details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<script>
// View user details
function viewUser(userId) {
    const users = <?php echo json_encode($users); ?>;
    const user = users.find(u => u.id == userId);
    
    if (user) {
        const detailsHtml = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Personal Information</h6>
                    <p><strong>Name:</strong> ${user.name}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Phone:</strong> ${user.phone || 'Not provided'}</p>
                </div>
                <div class="col-md-6">
                    <h6>Account Information</h6>
                    <p><strong>User ID:</strong> #${user.id}</p>
                    <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                    <p><strong>Member Since:</strong> ${new Date(user.created_at).toLocaleDateString()}</p>
                </div>
            </div>
            <div class="mt-3">
                <h6>Recent Activity</h6>
                <p class="text-muted">User activity tracking would be implemented here</p>
            </div>
        `;
        
        document.getElementById('userDetails').innerHTML = detailsHtml;
        new bootstrap.Modal(document.getElementById('userModal')).show();
    }
}

// Email user
function emailUser(email) {
    window.location.href = `mailto:${email}`;
}

// Export users
function exportUsers() {
    const table = document.getElementById('usersTable');
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
            if (index < 6) { // Exclude actions column
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
    a.download = 'users_export.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Initialize DataTable
$(document).ready(function() {
    $('#usersTable').DataTable({
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

<style>
.user-avatar {
    width: 35px;
    height: 35px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.table td {
    vertical-align: middle;
}
</style>
