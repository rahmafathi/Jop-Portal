<?php
// 1. بدء الـ Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
include_once '../includes/header.php';
include_once '../includes/nav-company.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$company_id = intval($_SESSION['company_id'] ?? 0);

// جلب بيانات الوظيفة مع التأكد أنها تخص الشركة المسجلة حالياً لأمان أفضل
$query = "SELECT * FROM jobs WHERE id = $id AND company_id = $company_id LIMIT 1";
$result = mysqli_query($conn, $query);
$job = ($result) ? mysqli_fetch_assoc($result) : null;

if (!$job) {
    echo "<div class='container my-5'>
            <div class='alert alert-danger custom-alert-danger'>
                <i class='fas fa-exclamation-triangle me-2'></i> Job not found or unauthorized action.
            </div>
          </div>";
    include_once '../includes/footer.php';
    exit;
}

// تحديث البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
    $job_type = mysqli_real_escape_string($conn, $_POST['job_type'] ?? '');
    $salary = mysqli_real_escape_string($conn, $_POST['salary'] ?? '');
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? '');

    $update = "UPDATE jobs SET
                title = '$title',
                job_type = '$job_type',
                salary = '$salary',
                status = '$status'
                WHERE id = $id AND company_id = $company_id";

    if (mysqli_query($conn, $update)) {
        if (function_exists('setMessage')) {
            setMessage('success', 'Job updated successfully!');
        } else {
            $_SESSION['success'] = "Job updated successfully";
        }
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
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.page-title {
    color: #ffffff;
    font-weight: 800;
    font-size: 1.8rem;
    letter-spacing: -0.5px;
}

.form-label {
    font-weight: 600;
    color: #94a3b8;
    margin-bottom: 8px;
    font-size: 0.9rem;
    letter-spacing: 0.3px;
}

.form-control, 
.form-select {
    background-color: rgba(30, 41, 59, 0.6) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 12px !important;
    padding: 12px 16px;
    font-size: 0.95rem;
    color: #f1f5f9 !important;
    transition: all 0.3s ease;
}

.form-control:focus, 
.form-select:focus {
    background-color: rgba(30, 41, 59, 0.9) !important;
    border-color: #00d2ff !important;
    box-shadow: 0 0 15px rgba(0, 210, 255, 0.2) !important;
    outline: none;
    color: #ffffff !important;
}

.form-control::placeholder {
    color: #64748b !important;
}

.form-select option {
    background-color: #0f172a !important;
    color: #f1f5f9 !important;
}

.btn-update-job {
    background: linear-gradient(135deg, #0d6efd, #00d2ff) !important;
    color: #ffffff !important;
    font-weight: 700;
    font-size: 1rem;
    padding: 12px 20px;
    border: none !important;
    border-radius: 12px !important;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 210, 255, 0.3);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-update-job:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 210, 255, 0.5);
    color: #ffffff !important;
}

.btn-cancel-custom {
    background: rgba(30, 41, 59, 0.8) !important;
    color: #cbd5e1 !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 12px !important;
    font-weight: 600;
    padding: 12px 20px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
}

.btn-cancel-custom:hover {
    background: rgba(100, 116, 139, 0.2) !important;
    color: #ffffff !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
}

.custom-alert-danger {
    background-color: rgba(239, 68, 68, 0.15) !important;
    border: 1px solid rgba(239, 68, 68, 0.3) !important;
    color: #f87171 !important;
    border-radius: 12px;
}
</style>

<div class="container my-5" dir="ltr">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card card-job-form p-4 p-md-5">

                <div class="mb-4">
                    <h2 class="page-title">Edit Job</h2>
                    <p class="text-muted mb-0" style="font-size: 14px;">
                        Update the information for this job listing.
                    </p>
                </div>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Job Title</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               value="<?php echo htmlspecialchars($job['title']); ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Job Type</label>
                        <select name="job_type" class="form-select" required>
                            <option value="Full Time" <?php if(trim($job['job_type']) == "Full Time") echo "selected"; ?>>Full Time</option>
                            <option value="Part Time" <?php if(trim($job['job_type']) == "Part Time") echo "selected"; ?>>Part Time</option>
                            <option value="Remote" <?php if(trim($job['job_type']) == "Remote") echo "selected"; ?>>Remote</option>
                            <option value="Internship" <?php if(trim($job['job_type']) == "Internship") echo "selected"; ?>>Internship</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Salary</label>
                        <input type="text"
                               name="salary"
                               class="form-control"
                               value="<?php echo htmlspecialchars($job['salary']); ?>"
                               placeholder="e.g. $1000 - $1500">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="open" <?php if(strtolower(trim($job['status'])) == "open") echo "selected"; ?>>Open</option>
                            <option value="closed" <?php if(strtolower(trim($job['status'])) == "closed") echo "selected"; ?>>Closed</option>
                        </select>
                    </div>

                    <div class="d-flex gap-3 pt-2">
                        <button type="submit"
                                name="update"
                                class="btn btn-update-job flex-grow-1">
                            <i class="fas fa-save"></i> Update Job
                        </button>

                        <a href="my_jobs.php"
                           class="btn btn-cancel-custom flex-grow-1">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>