<?php
// 1. بدء الـ Session وفحص الصلاحيات
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// استدعاء ملف الدوال وملف اتصال قاعدة البيانات
require_once "../includes/functions.php"; // أو المسار الخاص بـ functions.php حسب هيكلة مشروعك
require_once "../includes/db.php";        // أو المسار الخاص بـ db.php

// التأكد من أن المستخدم سجل دخوله وأنه من نوع Company
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'company') {
    redirect("../login.php");
}
$company_id = $_SESSION['user_id'];

// 2. استدعاء הـ Header
include_once "../header.php";

?>

<div class="container my-5" dir="ltr">
    
    <!-- 3. استدعاء عرض الرسائل مباشرة بعد الـ Header -->
    <?php displayMessage(); ?>

    <h2 class="mb-4 text-dark fw-bold">Company Dashboard</h2>

    <?php
    // جلب الإحصائيات من قاعدة البيانات
    
    // 1. اجمالي عدد الوظائف المنشورة للشركة
    $all_jobs = selectWhere($conn, 'jobs', 'company_id', $company_id);
    $total_jobs_count = count($all_jobs);

    // 2. عدد المتقدمين على وظائف الشركة
    $applications_query = mysqli_query($conn, "SELECT a.* FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.company_id = '$company_id'");
    $total_applicants_count = ($applications_query) ? mysqli_num_rows($applications_query) : 0;

    // 3. عدد الوظائف النشطة
    $active_jobs_query = mysqli_query($conn, "SELECT * FROM jobs WHERE company_id = '$company_id' AND status = 'active'");
    $active_jobs_count = ($active_jobs_query) ? mysqli_num_rows($active_jobs_query) : 0;

    // 4. عدد الوظائف المغلقة
    $closed_jobs_query = mysqli_query($conn, "SELECT * FROM jobs WHERE company_id = '$company_id' AND status = 'closed'");
    $closed_jobs_count = ($closed_jobs_query) ? mysqli_num_rows($closed_jobs_query) : 0;
    ?>

    <!-- 4. إنشاء 4 Bootstrap Stat Cards في صف واحد Responsive -->
    <div class="row g-4">

        <!-- Card 1: Published Jobs -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 bg-primary text-white">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="card-title text-uppercase mb-1 opacity-75">Total Jobs</h6>
                            <h2 class="display-6 fw-bold mb-0"><?php echo $total_jobs_count; ?></h2>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                    <a href="my_jobs.php" class="btn btn-light text-primary fw-semibold w-100 mt-2">
                        My Jobs
                    </a>
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
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <a href="applicants.php" class="btn btn-light text-success fw-semibold w-100 mt-2">
                        View Applicants
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 3: Active Jobs -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0 bg-info text-white">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="card-title text-uppercase mb-1 opacity-75">Active Jobs</h6>
                            <h2 class="display-6 fw-bold mb-0"><?php echo $active_jobs_count; ?></h2>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <a href="my_jobs.php?status=active" class="btn btn-light text-info fw-semibold w-100 mt-2">
                        View Jobs
                    </a>
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
                        <div class="fs-1 opacity-50">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <a href="my_jobs.php?status=closed" class="btn btn-light text-secondary fw-semibold w-100 mt-2">
                        View Closed Jobs
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
include_once "../footer.php";
?>