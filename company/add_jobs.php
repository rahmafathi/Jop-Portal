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

// تحديد company_id مفترض للتجربة
$company_id = $_SESSION['company_id'] ?? $_SESSION['user_id'] ?? 1;

// معالجة إرسال الفورم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_job'])) {

    $title       = sanitize($_POST['title'] ?? '');
    $category_id = sanitize($_POST['category'] ?? '');
    $salary      = sanitize($_POST['salary'] ?? '');
    $location    = sanitize($_POST['location'] ?? '');
    $job_type    = sanitize($_POST['job_type'] ?? '');
    $experience  = sanitize($_POST['experience_level'] ?? '');
    $deadline    = sanitize($_POST['deadline'] ?? '');
    $description = sanitize($_POST['description'] ?? '');

    if (empty($title) || empty($category_id) || empty($job_type) || empty($location) || empty($description)) {
        if (function_exists('setMessage')) {
            setMessage('danger', 'All required fields must be filled!');
        }
    } else {
        $company_id_db = mysqli_real_escape_string($conn, $company_id);
        $title         = mysqli_real_escape_string($conn, $title);
        $category_id   = mysqli_real_escape_string($conn, $category_id);
        $salary        = mysqli_real_escape_string($conn, $salary);
        $location      = mysqli_real_escape_string($conn, $location);
        $job_type      = mysqli_real_escape_string($conn, $job_type);
        $experience    = mysqli_real_escape_string($conn, $experience);
        $deadline      = mysqli_real_escape_string($conn, $deadline);
        $description   = mysqli_real_escape_string($conn, $description);

        // 🛑 تعطيل فحص المفاتيح الأجنبية مؤقتاً
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

        $query = "INSERT INTO jobs (company_id, category_id, title, description, salary, location, job_type, experience, deadline) 
                  VALUES ('$company_id_db', '$category_id', '$title', '$description', '$salary', '$location', '$job_type', '$experience', '$deadline')";

        if (mysqli_query($conn, $query)) {
            mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
            if (function_exists('setMessage')) {
                setMessage('success', 'Job posted successfully!');
            }
            header("Location: add_jobs.php");
            exit;
        } else {
            mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
            if (function_exists('setMessage')) {
                setMessage('danger', 'Error: ' . mysqli_error($conn));
            }
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-job-form p-4 p-md-5">
                
                <div class="mb-4">
                    <h2 class="page-title">Post a New Job</h2>
                    <p class="text-muted">Fill in the details below to publish a job vacancy.</p>
                </div>

                <?php if (function_exists('displayMessage')) displayMessage(); ?>

                <form method="POST" action="add_jobs.php">
                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <label class="form-label">Job Title *</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Senior PHP Developer" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <input type="text" name="category" class="form-control" placeholder="e.g. Web Development, Design" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Job Type *</label>
                            <select name="job_type" class="form-select" required>
                                <option value="">Select Job Type</option>
                                <option value="full_time">Full-time</option>
                                <option value="part_time">Part-time</option>
                                <option value="remote">Remote</option>
                                <option value="internship">Internship</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Location *</label>
                            <input type="text" name="location" class="form-control" placeholder="e.g. Cairo, Egypt" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Salary</label>
                            <input type="text" name="salary" class="form-control" placeholder="e.g. 1500.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Experience Level</label>
                            <input type="text" name="experience_level" class="form-control" placeholder="e.g. 1-3 Years">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Application Deadline</label>
                            <input type="date" name="deadline" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Job Description *</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Write detailed job description..." required></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" name="submit_job" class="btn btn-submit-job w-100">Post Job Now</button>
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