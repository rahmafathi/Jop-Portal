<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
include_once '../includes/header.php';
include_once '../includes/nav.php';

if (!function_exists('sanitize')) {
    function sanitize($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}

$company_id = $_SESSION['company_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_job'])) {

    $title       = sanitize($_POST['title'] ?? '');
    $category    = sanitize($_POST['category'] ?? '');
    $salary      = sanitize($_POST['salary'] ?? '');
    $location    = sanitize($_POST['location'] ?? '');
    $job_type    = sanitize($_POST['job_type'] ?? '');
    $description = sanitize($_POST['description'] ?? '');

    if (empty($title) || empty($job_type) || empty($location) || empty($description)) {
        if (function_exists('setMessage')) {
            setMessage('danger', 'All required fields must be filled!');
        }
    } else {
        $company_id_db = mysqli_real_escape_string($conn, $company_id);
        $title         = mysqli_real_escape_string($conn, $title);
        $salary        = mysqli_real_escape_string($conn, $salary);
        $location      = mysqli_real_escape_string($conn, $location);
        $job_type      = mysqli_real_escape_string($conn, $job_type);
        $description   = mysqli_real_escape_string($conn, $description);

        // تم حذف الأعمدة الغير موجودة في الداتا بيز لتجنب أي أخطاء
        $query = "INSERT INTO jobs (company_id, title, description, salary, location, job_type, status) 
                  VALUES ('$company_id_db', '$title', '$description', '$salary', '$location', '$job_type', 'open')";

        if (mysqli_query($conn, $query)) {
            header("Location: my_jobs.php");
            exit;
        } else {
            echo "<div class='container my-4'><div class='alert alert-danger'><strong>Database Error:</strong> " . mysqli_error($conn) . "</div></div>";
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-job-form p-4 p-md-5 shadow-sm border-0">
                <div class="mb-4">
                    <h2 class="page-title">Post a New Job</h2>
                    <p class="text-muted">Fill in the details below to publish a job vacancy.</p>
                </div>
                <form method="POST" action="add_jobs.php">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Job Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <input type="text" name="category" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Job Type *</label>
                            <select name="job_type" class="form-select" required>
                                <option value="">Select Job Type</option>
                                <option value="full time">Full-time</option>
                                <option value="part time">Part-time</option>
                                <option value="remote">Remote</option>
                                <option value="internship">Internship</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location *</label>
                            <input type="text" name="location" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Salary</label>
                            <input type="text" name="salary" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Job Description *</label>
                            <textarea name="description" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" name="submit_job" class="btn btn-primary w-100 py-2">Post Job Now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
include_once '../includes/footer.php'; 
?>