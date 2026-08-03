<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
include_once '../includes/header.php';
include_once '../includes/nav.php';

// تحديد الـ company_id بشكل آمن ومباشر
$company_id = $_SESSION['company_id'] ?? 1;

// جلب الوظائف الخاصة بالشركة الحالية فقط من قاعدة البيانات
$jobs_query = mysqli_query($conn, "SELECT * FROM jobs WHERE company_id = '$company_id' ORDER BY created_at DESC");
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Posted Jobs</h2>
        <a href="add_jobs.php" class="btn btn-primary">+ Add New Job</a>
    </div>

    <?php if (function_exists('displayMessage')) displayMessage(); ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4">Job Title</th>
                            <th class="py-3">Type</th>
                            <th class="py-3">Salary</th>
                            <th class="py-3">Applicants</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($jobs_query) > 0): ?>
                            <?php while ($job = mysqli_fetch_assoc($jobs_query)): ?>
                                <tr>
                                    <td class="px-4 fw-bold"><?php echo htmlspecialchars($job['title']); ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo htmlspecialchars($job['job_type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $job['salary'] ? '$' . number_format($job['salary'], 2) : 'Not Specified'; ?></td>
                                    <td>
                                        <a href="#" class="text-decoration-none">View</a>
                                    </td>
                                    <td>
                                        <?php if ($job['status'] == 'open'): ?>
                                            <span class="badge bg-success">Open</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Closed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end px-4">
                                        <a href="edit_job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="delete_job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-folder2-open fs-1 d-block mb-2"></i>
                                        <p class="mb-2">No jobs found. Click "Add New Job" to post your first job!</p>
                                        <a href="add_jobs.php" class="btn btn-sm btn-primary mt-2">Add New Job</a>
                                    </div>
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
include_once '../includes/footer.php'; 
?>