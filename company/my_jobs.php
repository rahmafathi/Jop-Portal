<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../includes/functions.php"; 
require_once "../includes/db.php";

$company_id = 1; 

if (isset($_GET['action']) && isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);
    $action = $_GET['action'];

    if ($action === 'toggle_status') {
        $current_status = $_GET['status'] ?? 'open';
        $new_status = ($current_status === 'open') ? 'closed' : 'open';
        mysqli_query($conn, "UPDATE jobs SET status = '$new_status' WHERE id = '$job_id' AND company_id = '$company_id'");
    } elseif ($action === 'delete') {
        mysqli_query($conn, "DELETE FROM jobs WHERE id = '$job_id' AND company_id = '$company_id'");
    }
    
    header("Location: my_jobs.php");
    exit();
}

if (file_exists("../includes/header.php")) {
    include_once "../includes/header.php";
} elseif (file_exists("../header.php")) {
    include_once "../header.php";
}
?>

<!-- Bootstrap & FontAwesome احتياطي للتأكد من التنسيق -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/css/company-job.css?v=2">

<div class="container my-5" dir="ltr">

    <?php 
    if (function_exists('displayMessage')) {
        displayMessage(); 
    }
    ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold mb-0">My Posted Jobs</h2>
        <a href="add_jobs.php" class="btn btn-primary fw-semibold">
            <i class="fas fa-plus-circle me-1"></i> Add New Job
        </a>
    </div>

    <?php
    $status_filter = "";
    if (isset($_GET['status'])) {
        $filter = $_GET['status'];
        if ($filter === 'active' || $filter === 'open') {
            $status_filter = " AND status = 'open' ";
        } elseif ($filter === 'closed') {
            $status_filter = " AND status = 'closed' ";
        }
    }

    $jobs_sql = "SELECT j.*, 
                (SELECT COUNT(*) FROM application a WHERE a.job_id = j.id) as applicants_count 
                FROM jobs j 
                WHERE j.company_id = '$company_id' $status_filter 
                ORDER BY j.created_at DESC";

    $jobs_result = mysqli_query($conn, $jobs_sql);
    ?>

    <div class="card jobs-card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle jobs-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4">Job Title</th>
                            <th scope="col">Type</th>
                            <th scope="col">Salary</th>
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
                                        <span class="badge bg-info text-dark">
                                            <?php echo htmlspecialchars($job['job_type'] ?? 'Full Time'); ?>
                                        </span>
                                    </td>

                                    <td class="fw-bold text-success">
                                        <?php 
                                            // عرض المرتب سواء كان نص أو رقم بدون استخدام number_format نهائياً لتفادي الخطأ
                                            echo !empty($job['salary']) ? htmlspecialchars($job['salary']) : 'N/A';
                                        ?>
                                    </td>

                                    <td>
                                        <a href="applicants.php?job_id=<?php echo $job['id']; ?>" class="badge bg-secondary text-decoration-none">
                                            <i class="fas fa-users me-1"></i> <?php echo $job['applicants_count']; ?>
                                        </a>
                                    </td>

                                    <td>
                                        <?php if (($job['status'] ?? 'open') === 'open'): ?>
                                            <span class="badge bg-success">Open</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Closed</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-end pe-4">
                                        <a href="my_jobs.php?action=toggle_status&job_id=<?php echo $job['id']; ?>&status=<?php echo $job['status'] ?? 'open'; ?>" 
                                           class="btn btn-sm btn-outline-secondary me-1" 
                                           title="Toggle Status">
                                            <i class="fas <?php echo ($job['status'] ?? 'open') === 'open' ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                                        </a>

                                        <a href="my_jobs.php?action=delete&job_id=<?php echo $job['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this job?');" 
                                           title="Delete Job">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-folder-open fa-2x text-muted mb-2 d-block"></i>
                                    <span>No jobs found. Click <strong>"Add New Job"</strong> to post your first job!</span>
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