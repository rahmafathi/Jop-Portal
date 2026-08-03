<?php
// admin/admin/companies.php
include '../includes/db.php';

if (isset($_GET['delete'])) {
    $cid = intval($_GET['delete']);
    if ($conn) {
        $conn->query("DELETE FROM companies WHERE id = $cid");
    }
    header("Location: companies.php");
    exit();
}

$companies = [];
if ($conn) {
    try {
        $res = $conn->query("SELECT * FROM companies ORDER BY id DESC");
        if ($res) { $companies = $res->fetch_all(MYSQLI_ASSOC); }
    } catch (Exception $e) { $companies = []; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Companies | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ربط ملف الـ CSS الخاص بالصفحة -->
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
                <a href="companies.php" class="active"><i class="fa-solid fa-building"></i> Companies</a>
                <a href="jobs.php"><i class="fa-solid fa-list-check"></i> Manage Jobs</a>
                <a href="profile.php"><i class="fa-solid fa-user-gear"></i> Admin Profile</a>
            </div>
            <div class="p-3">
                <a href="../../logout.php" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <h3 class="fw-bold mb-1">Registered Companies</h3>
            <p class="text-muted small mb-4">Manage registered employer accounts.</p>

            <div class="table-card p-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Company Name</th>
                                <th>Email</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($companies)): foreach ($companies as $comp): ?>
                            <tr>
                                <td>#<?= $comp['id'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="company-icon"><i class="fa-solid fa-building"></i></div>
                                        <span class="fw-bold"><?= htmlspecialchars($comp['company_name'] ?? 'N/A') ?></span>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($comp['email'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($comp['location'] ?? 'Not specified') ?></td>
                                <td>
                                    <a href="companies.php?delete=<?= $comp['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Delete this company?');">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">No companies found.</td></tr>
                            <?php endif; ?>
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