<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// استدعاء ملفات الاتصال والدوال من مجلد includes
require_once "../includes/functions.php"; 
require_once "../includes/db.php";

// معالجة حذف الوظيفة بواسطة الأدمن
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);
    mysqli_query($conn, "DELETE FROM jobs WHERE id = '$job_id'");
    header("Location: jobs.php");
    exit();
}

// استدعاء الـ Header
if (file_exists("../includes/header.php")) {
    include_once "../includes/header.php";
} elseif (file_exists("../header.php")) {
    include_once "../header.php";
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container my-5" dir="ltr">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold mb-0"><i class="fas fa-user-shield me-2 text-primary"></i>Admin - All Posted Jobs</h2>
    </div>

    <?php
    // جلب كافة الوظائف من قاعدة البيانات لعرضها للأدمن
    $jobs_sql = "SELECT j.*, 
                (SELECT COUNT(*) FROM application a WHERE a.job_id = j.id) as applicants_count 
                FROM jobs j 
                ORDER BY j.created_at DESC";

    $jobs_result = mysqli_query($conn, $jobs_sql);
    ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="ps-4">Job Title</th>
                            <th scope="col">Company ID</th>
                            <th scope="col">Type</th>
                            <th scope="col">Applicants</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($jobs_result && mysqli_num_rows($jobs_result) > 0): ?>
                            <?php while ($job = mysqli_fetch_assoc($jobs_result)): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($job['title']); ?></div>
                                        <div class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($job['location'] ?? 'Cairo'); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">Company #<?php echo htmlspecialchars($job['company_id']); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?php echo htmlspecialchars($job['job_type'] ?? 'Full Time'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-users me-1"></i> <?php echo $job['applicants_count']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (($job['status'] ?? 'open') === 'open'): ?>
                                            <span class="badge bg-success">Open</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Closed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="jobs.php?action=delete&job_id=<?php echo $job['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this job from the system?');" 
                                           title="Delete Job">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No jobs found in the system.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
if (file_exists("../includes/footer.php")) {
    include_once "../includes/footer.php";
} elseif (file_exists("../footer.php")) {
    include_once "../footer.php";
}
?>