<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
include_once '../includes/header.php';
include_once '../includes/nav.php';

$company_id = $_SESSION['company_id'] ?? 1;

$query = "SELECT * FROM jobs WHERE company_id = '$company_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title mb-1">My Posted Jobs</h2>
            <p class="text-muted">Manage your job listings and manage job status.</p>
        </div>
        <a href="add_jobs.php" class="btn btn-primary">+ Add New Job</a>
    </div>

    <?php if (function_exists('displayMessage')) displayMessage(); ?>

    <div class="card shadow-sm border-0 p-4">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Job Title</th>
                        <th scope="col">Type</th>
                        <th scope="col">Salary</th>
                        <th scope="col">Applicants</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($job = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?php echo htmlspecialchars($job['title']); ?></td>
                                <td>
                                    <span class="badge bg-secondary text-uppercase px-2 py-1" style="font-size: 0.75rem;">
                                        <?php echo htmlspecialchars($job['job_type']); ?>
                                    </span>
                                </td>
                                <td><?php echo !empty($job['salary']) ? '$' . number_format($job['salary'], 2) : 'Not Specified'; ?></td>
                                <td>
                                    <span class="text-muted">No Applicants</span>
                                </td>
                                <td>
                                    <!-- زر الحالة (Open) - ممكن نخليه يظهر حالتها بس بدون لينك غلط -->
                                    <span class="badge bg-success px-2 py-1">
                                        <?php echo htmlspecialchars($job['status']); ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <!-- زر الحذف فقط المتاح حالياً للتأكد إنه شغال -->
                                    <a href="delete_job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <p class="text-muted mb-2">No jobs found. Click "Add New Job" to post your first job!</p>
                                <a href="add_jobs.php" class="btn btn-primary btn-sm mt-2">Add New Job</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
include_once '../includes/footer.php'; 
?>