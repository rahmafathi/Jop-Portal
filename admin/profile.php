<?php
// admin/admin/profile.php
include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile | JobPortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/users.css?v=<?php echo time(); ?>"></head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        
        <div class="col-md-2 sidebar d-flex flex-column">
            <div class="brand">
                <i class="fa-solid fa-briefcase text-primary me-2"></i> JobPortal
            </div>
            <div class="mt-3 flex-grow-1">
                <a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Overview</a>
                <a href="users.php"><i class="fa-solid fa-users"></i> Job Seekers</a>
                <a href="companies.php"><i class="fa-solid fa-building"></i> Companies</a>
                <a href="jobs.php"><i class="fa-solid fa-list-check"></i> Manage Jobs</a>
                <a href="profile.php" class="active"><i class="fa-solid fa-user-gear"></i> Admin Profile</a>
            </div>
            <div class="p-3">
                <a href="../../logout.php" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <h3 class="fw-bold mb-1">Admin Profile</h3>
            <p class="text-muted small mb-4">Manage your administrative details.</p>

            <div class="stat-card p-4 col-md-6">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Admin Name</label>
                        <input type="text" class="form-control" value="System Administrator" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control" value="admin@jobportal.com" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <input type="text" class="form-control" value="Super Admin" readonly>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>