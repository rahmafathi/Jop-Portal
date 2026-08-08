<?php
// 1. بدء الـ Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
include_once '../includes/header.php';
include_once '../includes/nav-company.php';

if (!function_exists('sanitize')) {
    function sanitize($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}

if (!isset($_SESSION['company_id'])) {
    die("Company not found");
}

$company_id = $_SESSION['company_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_job'])) {

    $title = sanitize($_POST['title'] ?? '');
    $category_id = sanitize($_POST['category_id'] ?? '');
    $salary = sanitize($_POST['salary'] ?? '');
    $location = sanitize($_POST['location'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $job_type = sanitize($_POST['job_type'] ?? '');
    $description = sanitize($_POST['description'] ?? '');

    if (
        empty($title) ||
        empty($category_id) ||
        empty($job_type) ||
        empty($location) ||
        empty($email) ||
        empty($description)
    ) {
        if (function_exists('setMessage')) {
            setMessage('danger', 'All required fields must be filled!');
        }
    } else {
        $company_id_db = mysqli_real_escape_string($conn, $company_id);
        $title = mysqli_real_escape_string($conn, $title);
        $category_id = mysqli_real_escape_string($conn, $category_id);
        $salary = mysqli_real_escape_string($conn, $salary);
        $location = mysqli_real_escape_string($conn, $location);
        $email = mysqli_real_escape_string($conn, $email);
        $job_type = mysqli_real_escape_string($conn, $job_type);
        $description = mysqli_real_escape_string($conn, $description);

        $query = "INSERT INTO jobs
        (company_id, title, description, salary, location, email, job_type, status, category_id)
        VALUES
        ('$company_id_db', '$title', '$description', '$salary', '$location', '$email', '$job_type', 'open', '$category_id')";

        if (mysqli_query($conn, $query)) {
            header("Location: my_jobs.php");
            exit;
        } else {
            echo "<div class='container my-4'>
                    <div class='alert alert-danger custom-alert-danger'>
                        <strong>Database Error:</strong> " . mysqli_error($conn) . "
                    </div>
                  </div>";
        }
    }
}
?>

<!-- FontAwesome & Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body {
    background-color: #030712 !important;
    color: #f1f5f9 !important;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

.card-job-form {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 20px !important;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
}

.page-title {
    color: #ffffff;
    font-weight: 800;
    font-size: 1.8rem;
}

.form-label {
    font-weight: 600;
    color: #94a3b8;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.form-control, 
.form-select {
    background-color: rgba(30, 41, 59, 0.6) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 12px !important;
    padding: 12px 16px;
    font-size: 0.95rem;
    color: #f1f5f9 !important;
}

.form-control:focus, 
.form-select:focus {
    background-color: rgba(30, 41, 59, 0.9) !important;
    border-color: #00d2ff !important;
    box-shadow: 0 0 15px rgba(0, 210, 255, 0.2) !important;
    color: #ffffff !important;
    outline: none;
}

/* إصلاح ظهور خيارات القائمة المنسدلة بوضوح تام بدون أي مشاكل */
.form-select option {
    background-color: #0f172a !important;
    color: #f1f5f9 !important;
    padding: 10px;
}

.btn-submit-job {
    background: linear-gradient(135deg, #0d6efd, #00d2ff) !important;
    color: #ffffff !important;
    font-weight: 700;
    padding: 14px 20px;
    border: none !important;
    border-radius: 12px !important;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 210, 255, 0.3);
}

.btn-submit-job:hover {
    color: #ffffff !important;
    transform: translateY(-2px);
}
</style>

<div class="container my-5" dir="ltr">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-job-form p-4 p-md-5">
                <div class="mb-4">
                    <h2 class="page-title">Post a New Job</h2>
                    <p class="text-muted mb-0" style="font-size: 14px;">Fill in the details below to publish a job vacancy.</p>
                </div>

                <form method="POST" action="">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Job Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Senior Frontend Developer" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php
                                $categories = mysqli_query($conn, "SELECT * FROM categories");
                                while ($row = mysqli_fetch_assoc($categories)) {
                                ?>
                                    <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['category_name']); ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Job Type</label>
                            <select name="job_type" class="form-select" required>
                                <option value="">Select Job Type</option>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Remote">Remote</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="e.g. Cairo, Egypt or Remote" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="hr@company.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Salary (Optional)</label>
                            <input type="text" name="salary" class="form-control" placeholder="e.g. $1000 - $1500">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Job Description</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Provide a detailed overview..." required></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" name="submit_job" class="btn btn-submit-job w-100">
                                <i class="fas fa-paper-plane me-1"></i> Post Job Now
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>