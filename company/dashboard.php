<?php
// 1. بدء الـ Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// استدعاء ملفات الاتصال والدوال
require_once "../includes/functions.php"; 
require_once "../includes/db.php";
include_once '../includes/nav-company.php';
include_once "../includes/header.php";

// إسناد قيمة user_id المسجل أو استخدام رقم مستخدم من نوع company
$user_id = intval($_SESSION['user_id'] ?? 1);

// جلب الـ company_id المرتبط بـ user_id الحالي من جدول companies
$company_id = 0;
$comp_query = mysqli_query($conn, "SELECT id FROM companies WHERE user_id = $user_id LIMIT 1");
if ($comp_query && mysqli_num_rows($comp_query) > 0) {
    $company_data = mysqli_fetch_assoc($comp_query);
    $company_id = intval($company_data['id']);
    $_SESSION['company_id'] = $company_id;
}
?>

<!-- Bootstrap Icons & FontAwesome -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ============================================================
   COMPANY DASHBOARD CUSTOM STYLING (DARK NEON THEME)
============================================================ */
body {
    background-color: #030712 !important;
    color: #f1f5f9 !important;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

.dashboard-container {
    padding: 30px 0;
}

/* Dashboard Header */
.dashboard-header h2 {
    color: #ffffff;
    font-weight: 800;
    letter-spacing: -0.5px;
}

.btn-post-job {
    background: linear-gradient(135deg, #0d6efd, #00d2ff);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(0, 210, 255, 0.3);
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-post-job:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 210, 255, 0.5);
    color: #ffffff;
}

/* Stat Cards */
.stat-card {
    background: #0f172a;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 20px;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    overflow: hidden;
    position: relative;
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: rgba(0, 210, 255, 0.3) !important;
    box-shadow: 0 15px 35px rgba(0, 210, 255, 0.15);
}

.stat-card .card-body {
    padding: 24px;
}

.stat-card h6 {
    color: #94a3b8;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.stat-card h2 {
    color: #ffffff;
    font-size: 32px;
    font-weight: 800;
}

.stat-icon-box {
    width: 55px;
    height: 55px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

/* Card Variants Icons & Accents */
.stat-card.primary-accent .stat-icon-box {
    background: rgba(13, 110, 253, 0.15);
    color: #3b82f6;
    border: 1px solid rgba(13, 110, 253, 0.3);
}
.stat-card.success-accent .stat-icon-box {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}
.stat-card.info-accent .stat-icon-box {
    background: rgba(0, 210, 255, 0.15);
    color: #00d2ff;
    border: 1px solid rgba(0, 210, 255, 0.3);
}
.stat-card.secondary-accent .stat-icon-box {
    background: rgba(100, 116, 139, 0.15);
    color: #94a3b8;
    border: 1px solid rgba(100, 116, 139, 0.3);
}

.stat-btn {
    background: rgba(30, 41, 59, 0.8);
    color: #cbd5e1;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    padding: 10px;
    transition: all 0.2s ease;
    text-decoration: none;
    text-align: center;
    display: block;
    width: 100%;
    margin-top: 15px;
}

.stat-btn:hover {
    background: #00d2ff;
    color: #030712;
    border-color: #00d2ff;
    box-shadow: 0 0 12px rgba(0, 210, 255, 0.4);
}

/* Recent Jobs Table Card */
.custom-table-card {
    background: #0f172a;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    overflow: hidden;
}

.custom-table-card .card-header {
    background: rgba(15, 23, 42, 0.95);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 20px 24px;
}

.custom-table-card .card-header h5 {
    color: #ffffff;
    font-size: 18px;
    font-weight: 700;
}

.btn-outline-custom {
    color: #00d2ff;
    border: 1px solid rgba(0, 210, 255, 0.3);
    background: transparent;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 14px;
    transition: all 0.2s ease;
}

.btn-outline-custom:hover {
    background: rgba(0, 210, 255, 0.12);
    color: #00d2ff;
    border-color: #00d2ff;
}

/* Table Customization */
.table {
    color: #f1f5f9;
    margin-bottom: 0;
}

.table > :not(caption) > * > * {
    background-color: transparent;
    color: #f1f5f9;
    border-bottom-color: rgba(255, 255, 255, 0.06);
    padding: 16px 24px;
}

.table thead th {
    background-color: rgba(30, 41, 59, 0.5) !important;
    color: #94a3b8;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.table tbody tr {
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(30, 41, 59, 0.4);
}

/* Badges */
.badge-open {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 12px;
}

.badge-closed {
    background: rgba(100, 116, 139, 0.15);
    color: #94a3b8;
    border: 1px solid rgba(100, 116, 139, 0.3);
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 12px;
}

.btn-edit-action {
    background: rgba(30, 41, 59, 0.8);
    color: #cbd5e1;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-edit-action:hover {
    background: rgba(0, 210, 255, 0.15);
    color: #00d2ff;
    border-color: rgba(0, 210, 255, 0.3);
}
</style>

<div class="container dashboard-container" dir="ltr">
    
    <!-- 3. عرض الرسائل -->
    <?php 
    if (function_exists('displayMessage')) {
        displayMessage(); 
    }
    ?>

    <!-- Header Section + Quick Action Button -->
    <div class="d-flex justify-content-between align-items-center mb-4 dashboard-header flex-wrap gap-3">
        <div>
            <h2>Company Dashboard</h2>
            <p class="text-muted mb-0" style="font-size: 14px;">Welcome back! Manage your job postings and applicants smoothly.</p>
        </div>
        <a href="add_jobs.php" class="btn btn-post-job">
            <i class="fas fa-plus-circle"></i> Post New Job
        </a>
    </div>

    <?php
    // جلب الإحصائيات معتمدة على الأسماء الصحيحة بالجداول
    $jobs_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs WHERE company_id = $company_id");
    $total_jobs_count = ($jobs_res) ? mysqli_fetch_assoc($jobs_res)['total'] : 0;

    $total_applicants_count = 0;
    if ($company_id > 0) {
        $app_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM application a JOIN jobs j ON a.job_id = j.id WHERE j.company_id = $company_id");
        if ($app_res) {
            $total_applicants_count = mysqli_fetch_assoc($app_res)['total'];
        }
    }

    $active_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs WHERE company_id = $company_id AND status = 'open'");
    $active_jobs_count = ($active_res) ? mysqli_fetch_assoc($active_res)['total'] : 0;

    $closed_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs WHERE company_id = $company_id AND status = 'closed'");
    $closed_jobs_count = ($closed_res) ? mysqli_fetch_assoc($closed_res)['total'] : 0;
    ?>

    <!-- 4. Stat Cards -->
    <div class="row g-4 mb-5">
        <!-- Card 1: Total Jobs -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stat-card primary-accent h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Jobs</h6>
                            <h2 class="mb-0"><?php echo $total_jobs_count; ?></h2>
                        </div>
                        <div class="stat-icon-box">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                    <a href="my_jobs.php" class="stat-btn">View My Jobs</a>
                </div>
            </div>
        </div>

        <!-- Card 2: Applicants -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stat-card success-accent h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase mb-1">Applicants</h6>
                            <h2 class="mb-0"><?php echo $total_applicants_count; ?></h2>
                        </div>
                        <div class="stat-icon-box">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <a href="applicants.php" class="stat-btn">View Applicants</a>
                </div>
            </div>
        </div>

        <!-- Card 3: Active Jobs (Open) -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stat-card info-accent h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase mb-1">Active Jobs</h6>
                            <h2 class="mb-0"><?php echo $active_jobs_count; ?></h2>
                        </div>
                        <div class="stat-icon-box">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <a href="my_jobs.php?status=open" class="stat-btn">View Active Jobs</a>
                </div>
            </div>
        </div>

        <!-- Card 4: Closed Jobs -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stat-card secondary-accent h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase mb-1">Closed Jobs</h6>
                            <h2 class="mb-0"><?php echo $closed_jobs_count; ?></h2>
                        </div>
                        <div class="stat-icon-box">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <a href="my_jobs.php?status=closed" class="stat-btn">View Closed Jobs</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Recent Posted Jobs Table -->
    <div class="card custom-table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-info"></i> Recently Posted Jobs</h5>
            <a href="my_jobs.php" class="btn btn-sm btn-outline-custom">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent_jobs = mysqli_query($conn, "SELECT * FROM jobs WHERE company_id = $company_id ORDER BY id DESC LIMIT 5");
                        if ($recent_jobs && mysqli_num_rows($recent_jobs) > 0):
                            while ($job = mysqli_fetch_assoc($recent_jobs)):
                                $status_class = ($job['status'] == 'open') ? 'badge-open' : 'badge-closed';
                        ?>
                                <tr>
                                    <td class="fw-bold text-white"><?php echo htmlspecialchars($job['title']); ?></td>
                                    <td><span class="<?php echo $status_class; ?>"><?php echo ucfirst($job['status']); ?></span></td>
                                    <td class="text-muted" style="font-size: 14px;"><?php echo date('M d, Y', strtotime($job['created_at'])); ?></td>
                                    <td class="text-end">
                                        <a href="edit_job.php?id=<?php echo $job['id']; ?>" class="btn-edit-action">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                        <?php 
                            endwhile;
                        else: 
                        ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">No jobs posted yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php
include_once "../includes/footer.php";
?>