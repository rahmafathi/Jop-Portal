<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
include_once '../includes/header.php';
include_once '../includes/nav.php';

if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
    header("Location: my_jobs.php");
    exit();
}

$job_id = intval($_GET['job_id']);
$company_id = $_SESSION['company_id'] ?? 1;

// التأكد من أن الوظيفة تخص هذه الشركة
$job_check = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $job_id AND company_id = '$company_id'");
if (!$job_check || mysqli_num_rows($job_check) === 0) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Job not found or unauthorized.</div></div>";
    include_once '../includes/footer.php';
    exit();
}
$job = mysqli_fetch_assoc($job_check);

// جلب المتقدمين لهذه الوظيفة مع بيانات المستخدمين
$query = "SELECT applications.*, users.name, users.email FROM applications 
          JOIN users ON applications.user_id = users.id 
          WHERE applications.job_id = $job_id";
$result = mysqli_query($conn, $query);
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title mb-1">Applicants for: <?php echo htmlspecialchars($job['title']); ?></h2>
            <p class="text-muted">Review candidates who applied for this position.</p>
        </div>
        <a href="my_jobs.php" class="btn btn-secondary">Back to My Jobs</a>
    </div>

    <div class="card shadow-sm border-0 p-4">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Applicant Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Applied Date</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($app = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?php echo htmlspecialchars($app['name'] ?? 'Candidate'); ?></td>
                                <td><?php echo htmlspecialchars($app['email'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($app['created_at'] ?? 'N/A'); ?></td>
                                <td class="text-end">
                                    <?php if (!empty($app['cv_file'])): ?>
                                        <a href="../uploads/<?php echo htmlspecialchars($app['cv_file']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">View CV</a>
                                    <?php else: ?>
                                        <span class="text-muted">No CV File</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <p class="text-muted mb-0">No candidates have applied for this job yet.</p>
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