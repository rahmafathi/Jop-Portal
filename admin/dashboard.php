<?php
// admin/admin/dashboard.php
include '../includes/db.php';

function getCount($conn, $query) {
    if (!$conn) return 0;
    try {
        $res = $conn->query($query);
        if ($res) {
            $row = $res->fetch_assoc();
            return $row['total'] ?? 0;
        }
    } catch (Exception $e) { return 0; }
    return 0;
}

$total_users        = getCount($conn, "SELECT COUNT(*) as total FROM users WHERE role='seeker'");
$total_companies    = getCount($conn, "SELECT COUNT(*) as total FROM companies");
$total_jobs         = getCount($conn, "SELECT COUNT(*) as total FROM jobs");
$total_applications = getCount($conn, "SELECT COUNT(*) as total FROM applications");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Job Portal</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        
        <!-- Sidebar Navigation -->
        <div class="col-md-2 sidebar d-flex flex-column">
            <div class="brand">
                <i class="fa-solid fa-briefcase text-primary me-2"></i> JobPortal
            </div>
            <div class="mt-3 flex-grow-1">
                <a href="dashboard.php" class="active"><i class="fa-solid fa-chart-pie"></i> Overview</a>
                <a href="users.php"><i class="fa-solid fa-users"></i> Job Seekers</a>
                <a href="companies.php"><i class="fa-solid fa-building"></i> Companies</a>
                <a href="jobs.php"><i class="fa-solid fa-list-check"></i> Manage Jobs</a>
                <a href="profile.php"><i class="fa-solid fa-user-gear"></i> Admin Profile</a>
            </div>
            <div class="p-3">
                <a href="../logout.php" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #2b3674;">Dashboard Overview</h3>
                    <p class="text-muted small mb-0">Welcome back! Here is what is happening today.</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold">Total Seekers</span>
                                <h3 class="fw-bold mt-1 mb-0" style="color: #2b3674;"><?= $total_users ?></h3>
                            </div>
                            <div class="icon-box bg-light text-primary"><i class="fa-solid fa-user-graduate"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold">Companies</span>
                                <h3 class="fw-bold mt-1 mb-0" style="color: #2b3674;"><?= $total_companies ?></h3>
                            </div>
                            <div class="icon-box bg-light text-success"><i class="fa-solid fa-building"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold">Active Jobs</span>
                                <h3 class="fw-bold mt-1 mb-0" style="color: #2b3674;"><?= $total_jobs ?></h3>
                            </div>
                            <div class="icon-box bg-light text-warning"><i class="fa-solid fa-briefcase"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold">Applications</span>
                                <h3 class="fw-bold mt-1 mb-0" style="color: #2b3674;"><?= $total_applications ?></h3>
                            </div>
                            <div class="icon-box bg-light text-info"><i class="fa-solid fa-paper-plane"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Jobs Table -->
            <div class="table-card p-4">
                <h5 class="fw-bold mb-3" style="color: #2b3674;">Recent Job Posts</h5>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Job Title</th>
                                <th>Type</th>
                                <th>Salary Range</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($conn)) {
                                try {
                                    $recent_jobs = $conn->query("SELECT * FROM jobs ORDER BY id DESC LIMIT 5");
                                    if ($recent_jobs && $recent_jobs->num_rows > 0):
                                        while($job = $recent_jobs->fetch_assoc()):
                            ?>
                            <tr>
                                <td>#<?= $job['id'] ?></td>
                                <td class="fw-semibold" style="color: #2b3674;"><?= htmlspecialchars($job['title']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($job['job_type'] ?? 'Full Time') ?></span></td>
                                <td class="text-success fw-semibold"><?= htmlspecialchars($job['salary'] ?? 'N/A') ?></td>
                                <td><span class="badge bg-success-subtle text-success px-2 py-1"><?= ucfirst($job['status'] ?? 'Active') ?></span></td>
                            </tr>
                            <?php 
                                        endwhile; 
                                    else: 
                            ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">No job postings available at the moment.</td></tr>
                            <?php 
                                    endif;
                                } catch (Exception $e) {
                                    echo '<tr><td colspan="5" class="text-center text-muted py-4">No job postings available at the moment.</td></tr>';
                                }
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>