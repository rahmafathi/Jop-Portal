<?php
// 1. بدء الـ Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// استدعاء ملفات الاتصال والدوال من مجلد includes
require_once "../includes/functions.php"; 
require_once "../includes/db.php";

// تحديد ID الشركة (لو مسجلة دخول هياخد id بتاعك، لو مش مسجلة هياخد 1 للتجربة)
$company_id = $_SESSION['user_id'] ?? 1;

// --- 2. معالجة عمليات الحذف وتغيير الحالة (Active / Closed) ---

// أ) مسح الوظيفة (Delete)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $job_id = intval($_GET['id']);
    
    $delete_query = "DELETE FROM jobs WHERE id = '$job_id' AND company_id = '$company_id'";
    if (mysqli_query($conn, $delete_query)) {
        if (function_exists('setMessage')) {
            setMessage('success', 'Job deleted successfully.');
        }
    } else {
        if (function_exists('setMessage')) {
            setMessage('danger', 'Failed to delete job.');
        }
    }
    header("Location: my_jobs.php");
    exit();
}

// ب) تغيير حالة الوظيفة (Toggle Status)
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    $job_id = intval($_GET['id']);
    
    $status_check = mysqli_query($conn, "SELECT status FROM jobs WHERE id = '$job_id' AND company_id = '$company_id'");
    if ($status_check && mysqli_num_rows($status_check) > 0) {
        $row = mysqli_fetch_assoc($status_check);
        $new_status = ($row['status'] === 'active') ? 'closed' : 'active';
        
        mysqli_query($conn, "UPDATE jobs SET status = '$new_status' WHERE id = '$job_id' AND company_id = '$company_id'");
        if (function_exists('setMessage')) {
            setMessage('success', 'Job status updated successfully.');
        }
    }
    header("Location: my_jobs.php");
    exit();
}

// --- 3. استدعاء الـ Header من فولدر includes ---
if (file_exists("../includes/header.php")) {
    include_once "../includes/header.php";
} elseif (file_exists("../header.php")) {
    include_once "../header.php";
}
?>

<div class="container my-5" dir="ltr">
    
    <!-- عرض الرسائل التنبيهية -->
    <?php 
    if (function_exists('displayMessage')) {
        displayMessage(); 
    }
    ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">My Posted Jobs</h2>
            <p class="text-muted mb-0">Manage all jobs you have posted, track status, or add new listings.</p>
        </div>
        <a href="add_jops.php" class="btn btn-primary fw-semibold px-4">
            <i class="fas fa-plus me-2"></i>Post New Job
        </a>
    </div>

    <?php
    // فلترة الوظائف إذا جاء خيار status في الرابط (مثل my_jobs.php?status=active)
    $status_filter = "";
    if (isset($_GET['status']) && in_array($_GET['status'], ['active', 'closed'])) {
        $clean_status = mysqli_real_escape_string($conn, $_GET['status']);
        $status_filter = " AND status = '$clean_status'";
    }

    // جلب كل الوظائف الخاصة بهذه الشركة
    $jobs_sql = "SELECT * FROM jobs WHERE company_id = '$company_id' $status_filter ORDER BY id DESC";
    $jobs_result = mysqli_query($conn, $jobs_sql);
    ?>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <?php if ($jobs_result && mysqli_num_rows($jobs_result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Job Title</th>
                                <th>Category / Type</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($job = mysqli_fetch_assoc($jobs_result)): ?>
                                <tr>
                                    <!-- المسمى الوظيفي -->
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($job['title'] ?? 'Untitled'); ?></div>
                                        <small class="text-muted">
                                            Posted on: <?php echo isset($job['created_at']) ? date('M d, Y', strtotime($job['created_at'])) : 'N/A'; ?>
                                        </small>
                                    </td>

                                    <!-- التخصص / نوع الوظيفة -->
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?php echo htmlspecialchars($job['job_type'] ?? $job['type'] ?? 'Full Time'); ?>
                                        </span>
                                    </td>

                                    <!-- المكان -->
                                    <td>
                                        <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                        <?php echo htmlspecialchars($job['location'] ?? 'Not Specified'); ?>
                                    </td>

                                    <!-- الحالة (Active / Closed) -->
                                    <td>
                                        <?php if (isset($job['status']) && strtolower($job['status']) === 'active'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-2 rounded-pill">Closed</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- الإجراءات (Actions) -->
                                    <td class="text-end pe-4">
                                        <!-- زر تغيير الحالة (Toggle) -->
                                        <a href="my_jobs.php?action=toggle&id=<?php echo $job['id']; ?>" 
                                           class="btn btn-sm btn-outline-secondary me-1" 
                                           title="Toggle Status (Active/Closed)">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>

                                        <!-- زر تعديل الوظيفة -->
                                        <a href="add_jops.php?edit_id=<?php echo $job['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary me-1" 
                                           title="Edit Job">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- زر مسح الوظيفة -->
                                        <a href="my_jobs.php?action=delete&id=<?php echo $job['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Delete Job"
                                           onclick="return confirm('Are you sure you want to delete this job?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <!-- في حالة عدم وجود وظائف -->
                <div class="text-center py-5">
                    <i class="fas fa-briefcase fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted fw-normal">No jobs found!</h5>
                    <p class="text-muted small">You haven't posted any jobs yet, or no jobs match the current filter.</p>
                    <a href="add_jops.php" class="btn btn-primary btn-sm mt-2">Post a Job Now</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// --- 4. استدعاء الـ Footer من فولدر includes ---
if (file_exists("../includes/footer.php")) {
    include_once "../includes/footer.php";
} elseif (file_exists("../footer.php")) {
    include_once "../footer.php";
}
?>