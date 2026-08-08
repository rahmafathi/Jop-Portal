<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/functions.php';
require_once '../includes/db.php';

checkLogin();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'job_seeker') {
    redirect('../login.php');
}

$userId = $_SESSION['user_id'];

// Get User Data
$userQuery = mysqli_query($conn, "SELECT id, name, email, phone FROM users WHERE id = $userId");
$user = mysqli_fetch_assoc($userQuery);

if (!$user) {
    session_destroy();
    redirect('../login.php');
}

// Stats
$applicationsQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM application WHERE seeker_id = $userId");
$totalApplications = mysqli_fetch_assoc($applicationsQuery)['total'];

$savedJobsQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM saved_jobs WHERE seeker_id = $userId");
$savedJobs = mysqli_fetch_assoc($savedJobsQuery)['total'];

$profileFields = [$user['name'], $user['email'], $user['phone']];
$completedFields = 0;
foreach ($profileFields as $field) { if (!empty($field)) $completedFields++; }
$profileCompletion = round(($completedFields / count($profileFields)) * 100);

// Recent Apps
$recentApplicationsQuery = mysqli_query($conn, "SELECT j.id, j.title, c.company_name, a.status, a.applied_at FROM application a INNER JOIN jobs j ON a.job_id = j.id INNER JOIN companies c ON j.company_id = c.id WHERE a.seeker_id = $userId ORDER BY a.applied_at DESC LIMIT 5");
$recentApplications = [];
while ($row = mysqli_fetch_assoc($recentApplicationsQuery)) { $recentApplications[] = $row; }

// Latest Jobs
$latestJobsQuery = mysqli_query($conn, "SELECT j.id, j.title, j.description, j.salary, j.location, j.job_type, j.experience, c.company_name, cat.category_name FROM jobs j INNER JOIN companies c ON j.company_id = c.id INNER JOIN categories cat ON j.category_id = cat.id WHERE j.status = 'open' ORDER BY j.created_at DESC LIMIT 5");
$latestJobs = [];
while ($row = mysqli_fetch_assoc($latestJobsQuery)) { $latestJobs[] = $row; }

require_once '../includes/header.php';
require_once '../includes/nav-seeker.php';
?>

<style>
    body { background-color: #0F172A; color: #F8FAFC; }
    .card { background-color: #1E293B !important; color: #F8FAFC !important; }
    .text-muted { color: #94a3b8 !important; }
    
    /* جدول البيانات */
    .table { color: #F8FAFC !important; background-color: transparent !important; }
    .table th, .table td { background-color: transparent !important; color: #F8FAFC !important; border-color: #334155 !important; }
    .table thead th { border-bottom: 2px solid #334155 !important; color: #94a3b8 !important; }
    .table-responsive { background-color: transparent !important; }

    /* تأثير تفاعلي للكارد القابلة للضغط لتظهر بوضوح عند الـ Hover */
    .card-hover-effect {
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        border: 1px solid rgba(245, 158, 11, 0.2) !important; /* إطار خفيف يوضح أنها مميزة */
    }
    .card-hover-effect:hover {
        transform: translateY(-4px);
        background-color: #273548 !important; /* تفتيح لون الكارد قليلاً عند الـ Hover */
        border-color: #F59E0B !important; /* تغيير لون الإطار للون تحذيري/مميز (أصفر) */
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.15) !important;
    }
    .card-hover-effect:hover .hover-icon {
        opacity: 1;
        transform: translateX(0);
    }
    .hover-icon {
        opacity: 0.5;
        transform: translateX(-5px);
        transition: all 0.3s ease-in-out;
    }

    /* تصميم احترافي للأزرار */
    .btn {
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        transition: all 0.3s ease-in-out;
        border-radius: 10px;
    }
    .btn-primary {
        background-color: #3B82F6 !important;
        border-color: #3B82F6 !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }
    .btn-primary:hover {
        background-color: #2563EB !important;
        border-color: #2563EB !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(59, 130, 246, 0.4);
    }
    .btn-outline-primary {
        border-color: #3B82F6;
        color: #3B82F6;
        background-color: transparent;
    }
    .btn-outline-primary:hover {
        background-color: #3B82F6;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
    }

    .progress { background-color: #0F172A; }
    .bg-primary { background-color: #3B82F6 !important; }
    .bg-success { background-color: #22C55E !important; }
    .bg-warning { background-color: #F59E0B !important; }
    .bg-danger { background-color: #EF4444 !important; }
    .badge.bg-light { background-color: #334155 !important; color: #F8FAFC !important; }
</style>

<div class="container py-5">
    <div class="mb-4">
        <h2 class="fw-bold">Welcome, <?= sanitize($user['name']); ?> 👋</h2>
        <p class="text-muted">Here is your job seeker dashboard.</p>
    </div>

    <?php displayMessage(); ?>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;">
                        <i class="bi bi-send-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Applications</h6>
                        <h3 class="fw-bold mb-0"><?= $totalApplications; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Saved Jobs Card (Linked with Hover and Click Indicators) -->
        <div class="col-md-4">
            <a href="../seeker/save_job.php" class="text-decoration-none">
                <div class="card shadow-sm rounded-4 h-100 card-hover-effect">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning text-dark rounded-3 d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;">
                                <i class="bi bi-bookmark-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Saved Jobs</h6>
                                <h3 class="fw-bold mb-0 text-light"><?= $savedJobs; ?></h3>
                            </div>
                        </div>
                        <!-- أيقونة توضيحية تظهر عند الـ Hover تشير للذهاب للصفحة -->
                        <div class="text-warning fs-4 hover-icon">
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success text-white rounded-3 d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;">
                            <i class="bi bi-person-check-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Profile Completion</h6>
                            <h3 class="fw-bold mb-0"><?= $profileCompletion; ?>%</h3>
                        </div>
                    </div>
                    <div class="progress" style="height:8px;">
                        <div class="progress-bar bg-success" style="width: <?= $profileCompletion; ?>%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- أزرار التحكم الرئيسية المحسنة -->
    <div class="d-flex flex-wrap gap-3 mb-5">
        <a href="../seeker/jobs.php" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-search fs-5"></i> Browse Jobs
        </a>
        <a href="profile.php" class="btn btn-outline-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-person-gear fs-5"></i> Edit Profile
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-5">
        <div class="card-body">
            <h4 class="fw-bold mb-4"><i class="bi bi-send"></i> Recent Applications</h4>
            <?php if (empty($recentApplications)): ?>
                <p class="text-center text-muted">You haven't applied for any jobs yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Job</th><th>Company</th><th>Status</th><th>Applied At</th></tr></thead>
                        <tbody>
                            <?php foreach ($recentApplications as $app): ?>
                                <tr>
                                    <td><strong><?= sanitize($app['title']); ?></strong></td>
                                    <td><?= sanitize($app['company_name']); ?></td>
                                    <td><span class="badge <?= ($app['status'] == 'accepted' ? 'bg-success' : ($app['status'] == 'rejected' ? 'bg-danger' : 'bg-warning text-dark')) ?>"><?= sanitize(ucfirst($app['status'])); ?></span></td>
                                    <td><?= date('d M Y', strtotime($app['applied_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-4">
        <h4 class="fw-bold"><i class="bi bi-briefcase"></i> Latest Jobs For You</h4>
        <p class="text-muted">Latest available job opportunities.</p>
    </div>

    <div class="row g-4">
        <?php foreach ($latestJobs as $job): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div><h5 class="fw-bold mb-1"><?= sanitize($job['title']); ?></h5><small class="text-muted"><i class="bi bi-building"></i> <?= sanitize($job['company_name']); ?></small></div>
                                <span class="badge bg-light"><?= sanitize($job['category_name']); ?></span>
                            </div>
                            <p class="text-muted small"><?= sanitize($job['description']); ?></p>
                            <div class="small mb-2"><i class="bi bi-geo-alt"></i> <?= sanitize($job['location']); ?></div>
                        </div>
                        <!-- زر عرض الوظيفة المحسن داخل الكارد -->
                        <a href="../seeker/job_details.php?id=<?= $job['id']; ?>" class="btn btn-primary w-100 mt-3 d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-arrow-right-circle"></i> View Job
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>