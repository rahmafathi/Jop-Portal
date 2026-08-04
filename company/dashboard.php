<?php
// 1. بدء الـ Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// استدعاء ملفات الاتصال والدوال
require_once "../includes/functions.php"; 
require_once "../includes/db.php";

// إسناد قيمة user_id المسجل أو استخدام رقم مستخدم من نوع company (مثل المستخدم رقم 1 لديكِ)
$user_id = intval($_SESSION['user_id'] ?? 1);

// جلب الـ company_id المرتبط بـ user_id الحالي من جدول companies
$company_id = 0;
$comp_query = mysqli_query($conn, "SELECT id FROM companies WHERE user_id = $user_id LIMIT 1");
if ($comp_query && mysqli_num_rows($comp_query) > 0) {
    $company_data = mysqli_fetch_assoc($comp_query);
    $company_id = intval($company_data['id']);
        $_SESSION['company_id'] = $company_id;

}

// 2. استدعاء الـ Header
include_once "../includes/header.php";
?>

<div class="container my-5" dir="ltr">
    
    <!-- 3. عرض الرسائل -->
    <?php 
    if (function_exists('displayMessage')) {
        displayMessage(); 
    }
    ?>

    <!-- Header Section + Quick Action Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold mb-0">Company Dashboard</h2>
        <a href="add_jobs.php" class="btn btn-primary fw-bold shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Post New Job
        </a>
    </div>

    <?php
    // جلب الإحصائيات معتمدة على الأسماء الصحيحة بالجداول (jobs, application)

    // 1. إجمالي عدد الوظائف
    $jobs_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs WHERE company_id = $company_id");
    $total_jobs_count = ($jobs_res) ? mysqli_fetch_assoc($jobs_res)['total'] : 0;

    // 2. عدد المتقدمين (جدول application المفرد)
    $total_applicants_count = 0;
    if ($company_id > 0) {
        $app_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM application a JOIN jobs j ON a.job_id = j.id WHERE j.company_id = $company_id");
        if ($app_res) {
            $total_applicants_count = mysqli_fetch_assoc($app_res)['total'];
        }
    }

    // 3. عدد الوظائف النشطة (Status = 'open' حسب الـ DB)
    $active_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs WHERE company_id = $company_id AND status = 'open'");
    $active_jobs_count = ($active_res) ? mysqli_fetch_assoc($active_res)['total'] : 0;

    // 4. عدد الوظائف المغلقة (Status = 'closed')
    $closed_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs WHERE company_id = $company_id AND status = 'closed'");
    $closed_jobs_count = ($closed_res) ? mysqli_fetch_assoc($closed_res)['total'] : 0;
    ?>

    <!-- 4. Stat Cards -->
    <div class="row g-4 mb-5">
        <!-- Card 1: Total Jobs -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 bg-primary text-white">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="card-title text-uppercase mb-1 opacity-75">Total Jobs</h6>
                            <h2 class="display-6 fw-bold mb-0"><?php echo $total_jobs_count; ?></h2>
                        </div>
                        <div class="fs-1 opacity-50"><i class="fas fa-briefcase"></i></div>
                    </div>
                    <a href="my_jobs.php" class="btn btn-light text-primary fw-semibold w-100 mt-2">My Jobs</a>
                </div>
            </div>
        </div>

        <!-- Card 2: Applicants -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 bg-success text-white">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="card-title text-uppercase mb-1 opacity-75">Applicants</h6>
                            <h2 class="display-6 fw-bold mb-0"><?php echo $total_applicants_count; ?></h2>
                        </div>
                        <div class="fs-1 opacity-50"><i class="fas fa-users"></i></div>
                    </div>
                    <a href="applicants.php" class="btn btn-light text-success fw-semibold w-100 mt-2">View Applicants</a>
                </div>
            </div>
        </div>

        <!-- Card 3: Active Jobs (Open) -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 bg-info text-white">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="card-title text-uppercase mb-1 opacity-75">Active Jobs</h6>
                            <h2 class="display-6 fw-bold mb-0"><?php echo $active_jobs_count; ?></h2>
                        </div>
                        <div class="fs-1 opacity-50"><i class="fas fa-check-circle"></i></div>
                    </div>
                    <a href="my_jobs.php?status=open" class="btn btn-light text-info fw-semibold w-100 mt-2">View Jobs</a>
                </div>
            </div>
        </div>

        <!-- Card 4: Closed Jobs -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 bg-secondary text-white">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="card-title text-uppercase mb-1 opacity-75">Closed Jobs</h6>
                            <h2 class="display-6 fw-bold mb-0"><?php echo $closed_jobs_count; ?></h2>
                        </div>
                        <div class="fs-1 opacity-50"><i class="fas fa-times-circle"></i></div>
                    </div>
                    <a href="my_jobs.php?status=closed" class="btn btn-light text-secondary fw-semibold w-100 mt-2">View Closed Jobs</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Recent Posted Jobs Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-list me-2 text-primary"></i> Recently Posted Jobs</h5>
            <a href="my_jobs.php" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
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
                                $status_badge = ($job['status'] == 'open') ? 'bg-success' : 'bg-secondary';
                        ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($job['title']); ?></td>
                                    <td><span class="badge <?php echo $status_badge; ?>"><?php echo ucfirst($job['status']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($job['created_at'])); ?></td>
                                    <td class="text-end">
                                        <a href="edit_job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-light border me-1"><i class="fas fa-edit"></i> Edit</a>
                                    </td>
                                </tr>
                        <?php 
                            endwhile;
                        else: 
                        ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No jobs posted yet.</td>
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