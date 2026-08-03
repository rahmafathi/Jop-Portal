<?php
// admin/admin/jobs.php
include '../../includes/db.php';

if (isset($_GET['delete'])) {
    $jid = intval($_GET['delete']);
    if ($conn) {
        $conn->query("DELETE FROM jobs WHERE id = $jid");
    }
    header("Location: jobs.php");
    exit();
}

$jobs = [];
if ($conn) {
    try {
        $res = $conn->query("SELECT jobs.*, companies.company_name FROM jobs LEFT JOIN companies ON jobs.company_id = companies.id ORDER BY jobs.id DESC");
        if ($res) { $jobs = $res->fetch_all(MYSQLI_ASSOC); }
    } catch (Exception $e) { $jobs = []; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Jobs | Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- ربط الـ CSS بمسار كامل ومباشر يمنع أي لخبطة -->
<link rel="stylesheet" href="../assets/css/users.css?v=<?php echo time(); ?>"></head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        
        <!-- القائمة الجانبية Sidebar -->
        <div class="col-md-2 sidebar d-flex flex-column">
            <div class="brand">
                <i class="fa-solid fa-briefcase text-primary me-2"></i> JobPortal
            </div>
            <div class="mt-3 flex-grow-1">
                <a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Overview</a>
                <a href="users.php"><i class="fa-solid fa-users"></i> Job Seekers</a>
                <a href="companies.php"><i class="fa-solid fa-building"></i> Companies</a>
                <a href="jobs.php" class="active"><i class="fa-solid fa-list-check"></i> Manage Jobs</a>
                <a href="profile.php"><i class="fa-solid fa-user-gear"></i> Admin Profile</a>
            </div>
            <div class="p-3">
                <a href="../../logout.php" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>

        <!-- محتوى الصفحة الرئيسي -->
        <div class="col-md-10 p-4">
            <h3 class="fw-bold mb-1">Manage Jobs</h3>
            <p class="text-muted small mb-4">Control and remove active job postings.</p>

            <div class="table-card p-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Job Title</th>
                                <th>Company</th>
                                <th>Type</th>
                                <th>Salary</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($jobs)): foreach ($jobs as $job): ?>
                            <tr>
                                <td>#<?= $job['id'] ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($job['title']) ?></td>
                                <td><?= htmlspecialchars($job['company_name'] ?? 'N/A') ?></td>
                                <td><span class="badge bg-light text-primary border"><?= htmlspecialchars($job['job_type'] ?? 'Full Time') ?></span></td>
                                <td class="text-success fw-semibold"><?= htmlspecialchars($job['salary'] ?? 'N/A') ?></td>
                                <td>
                                    <a href="jobs.php?delete=<?= $job['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Delete this job?');">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No jobs posted yet.</td></tr>
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