<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
include_once '../includes/header.php';
include_once '../includes/nav.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: my_jobs.php");
    exit();
}

$job_id = intval($_GET['id']);
$company_id = $_SESSION['company_id'] ?? 1;

$query = "SELECT * FROM jobs WHERE id = $job_id AND company_id = '$company_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Job not found.</div></div>";
    include_once '../includes/footer.php';
    exit();
}

$job = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_job'])) {
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $category    = mysqli_real_escape_string($conn, $_POST['category'] ?? '');
    $salary      = mysqli_real_escape_string($conn, $_POST['salary']);
    $location    = mysqli_real_escape_string($conn, $_POST['location']);
    $job_type    = mysqli_real_escape_string($conn, $_POST['job_type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status      = mysqli_real_escape_string($conn, $_POST['status']);

    $update_query = "UPDATE jobs SET 
                        title = '$title', 
                        category = '$category', 
                        salary = '$salary', 
                        location = '$location', 
                        job_type = '$job_type', 
                        description = '$description', 
                        status = '$status' 
                      WHERE id = $job_id AND company_id = '$company_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: my_jobs.php");
        exit();
    } else {
        $error_msg = mysqli_error($conn);
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card p-4 p-md-5 shadow-sm border-0">
                <h2 class="page-title mb-1">Edit Job</h2>
                <p class="text-muted mb-4">Update your job vacancy details.</p>

                <?php if (isset($error_msg)): ?>
                    <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Job Title *</label>
                            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($job['title']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($job['category'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Job Type *</label>
                            <select name="job_type" class="form-select" required>
                                <option value="full time" <?php if($job['job_type'] == 'full time') echo 'selected'; ?>>Full-time</option>
                                <option value="part time" <?php if($job['job_type'] == 'part time') echo 'selected'; ?>>Part-time</option>
                                <option value="remote" <?php if($job['job_type'] == 'remote') echo 'selected'; ?>>Remote</option>
                                <option value="internship" <?php if($job['job_type'] == 'internship') echo 'selected'; ?>>Internship</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location *</label>
                            <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($job['location']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Salary</label>
                            <input type="text" name="salary" class="form-control" value="<?php echo htmlspecialchars($job['salary']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="open" <?php if($job['status'] == 'open') echo 'selected'; ?>>Open</option>
                                <option value="closed" <?php if($job['status'] == 'closed') echo 'selected'; ?>>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Job Description *</label>
                            <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($job['description']); ?></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" name="update_job" class="btn btn-primary w-100 py-2">Update Job Details</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>