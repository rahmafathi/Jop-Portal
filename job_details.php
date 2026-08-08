<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "includes/db.php";
include_once "includes/functions.php";
include_once "includes/header.php";
include_once "includes/nav-seeker.php";

// =====================================
// Check Job ID
// =====================================
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Job ID not found");
}

$job_id = (int) $_GET['id'];

// =====================================
// Get Job Details
// =====================================
$sql = "SELECT 
            jobs.id,
            jobs.title,
            jobs.description,
            jobs.salary,
            jobs.location,
            jobs.job_type,
            jobs.status,
            jobs.category_id,
            jobs.company_id,
            companies.company_name,
            categories.category_name
        FROM jobs
        LEFT JOIN companies ON jobs.company_id = companies.id
        LEFT JOIN categories ON jobs.category_id = categories.id
        WHERE jobs.id = $job_id
        LIMIT 1";

$result = mysqli_query($conn, $sql);

// =====================================
// Check Query
// =====================================
if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

// =====================================
// Check Job Exists
// =====================================
if (mysqli_num_rows($result) == 0) {
    die("Job not found");
}

$job = mysqli_fetch_assoc($result);
$companyName = $job['company_name'] ?? 'Company';
?>

<!-- Bootstrap CDN & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Custom Embedded Styling (Enhanced Contrast & Comfort) -->
<style>
body {
    background-color: #070d1b !important;
    color: #f1f5f9 !important;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif !important;
}

/* Back Button Styling */
.btn-back {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #e2e8f0 !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    font-weight: 600 !important;
    border-radius: 12px !important;
    padding: 8px 18px !important;
    transition: all 0.3s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
}
.btn-back:hover {
    background: rgba(0, 210, 255, 0.15) !important;
    color: #38bdf8 !important;
    border-color: rgba(56, 189, 248, 0.5) !important;
    transform: translateX(-3px);
}

/* Main Cards Styling */
.details-card {
    background: rgba(15, 23, 42, 0.98) !important;
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 20px !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.7) !important;
}

/* Company Logo / Avatar Icon */
.company-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(0, 210, 255, 0.2), rgba(13, 110, 253, 0.2));
    color: #38bdf8;
    border: 1px solid rgba(56, 189, 248, 0.4);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 26px;
    box-shadow: 0 0 20px rgba(0, 210, 255, 0.25);
    flex-shrink: 0;
}

/* Info Box Item */
.info-box {
    background: rgba(30, 41, 59, 0.8) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 14px !important;
    padding: 18px !important;
    transition: all 0.3s ease;
}
.info-box:hover {
    border-color: rgba(56, 189, 248, 0.4) !important;
    background: rgba(30, 41, 59, 1) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}
.info-box i {
    font-size: 22px;
    color: #38bdf8 !important;
}
.info-box strong {
    color: #ffffff !important;
    font-size: 13px;
    display: block;
    margin-top: 6px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.info-box .info-value {
    color: #cbd5e1 !important;
    font-size: 15px;
    font-weight: 500;
    margin-top: 3px;
}

/* Dividers */
hr {
    border-color: rgba(255, 255, 255, 0.12) !important;
}

/* Section Headings */
h2, h4, h5 {
    color: #ffffff !important;
}
.job-description-text {
    color: #e2e8f0 !important;
    font-size: 16px;
    line-height: 1.9;
}

/* Badges */
.badge-status-open {
    background: rgba(34, 197, 94, 0.2) !important;
    color: #4ade80 !important;
    border: 1px solid rgba(34, 197, 94, 0.4) !important;
    padding: 8px 16px;
    font-weight: 700;
    border-radius: 20px;
}
.badge-status-closed {
    background: rgba(239, 68, 68, 0.2) !important;
    color: #f87171 !important;
    border: 1px solid rgba(239, 68, 68, 0.4) !important;
    padding: 8px 16px;
    font-weight: 700;
    border-radius: 20px;
}

/* Action Buttons */
.btn-apply-now {
    background: linear-gradient(135deg, #0d6efd, #00d2ff) !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 700 !important;
    padding: 14px 20px !important;
    font-size: 16px !important;
    border-radius: 14px !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 6px 20px rgba(0, 210, 255, 0.35);
    transition: all 0.3s ease !important;
}
.btn-apply-now:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 210, 255, 0.5);
    color: #ffffff !important;
}

.btn-closed-disabled {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #94a3b8 !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    font-weight: 600 !important;
    padding: 14px 20px !important;
    border-radius: 14px !important;
}
</style>

<div class="container py-5">
    
    <!-- ============================= -->
    <!-- Back Button -->
    <!-- ============================= -->
    <div class="mb-4">
        <a href="jobs.php" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> Back to Jobs
        </a>
    </div>

    <div class="row g-4">

        <!-- ============================= -->
        <!-- Job Details Main Column -->
        <!-- ============================= -->
        <div class="col-lg-8">
            <div class="card details-card border-0">
                <div class="card-body p-4 p-md-5">

                    <!-- Header with Company Avatar & Title -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="company-avatar">
                            <?= strtoupper(substr($companyName, 0, 1)); ?>
                        </div>
                        <div>
                            <h2 class="fw-bold mb-1 fs-3 text-white">
                                <?= htmlspecialchars($job['title']); ?>
                            </h2>
                            <p class="text-info m-0 fs-6 fw-semibold">
                                <i class="bi bi-building me-1"></i> <?= htmlspecialchars($companyName); ?>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- ============================= -->
                    <!-- Job Information Grid -->
                    <!-- ============================= -->
                    <div class="row g-3 my-3">

                        <!-- Location -->
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-geo-alt"></i>
                                <strong>Location</strong>
                                <div class="info-value">
                                    <?= htmlspecialchars($job['location']); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Job Type -->
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-briefcase"></i>
                                <strong>Job Type</strong>
                                <div class="info-value">
                                    <?= htmlspecialchars($job['job_type']); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-grid"></i>
                                <strong>Category</strong>
                                <div class="info-value">
                                    <?php
                                    if (!empty($job['category_name'])) {
                                        echo htmlspecialchars($job['category_name']);
                                    } else {
                                        echo "Not specified";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Salary -->
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-cash-coin"></i>
                                <strong>Salary</strong>
                                <div class="info-value text-success fw-bold">
                                    <?php
                                    if (isset($job['salary']) && $job['salary'] !== '') {
                                        echo number_format((float)$job['salary']) . " EGP";
                                    } else {
                                        echo "Not specified";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ============================= -->
                    <!-- Description Section -->
                    <!-- ============================= -->
                    <h4 class="fw-bold mb-3 mt-4 fs-5 text-white">
                        <i class="bi bi-file-text me-2 text-info"></i> Job Description
                    </h4>

                    <div class="job-description-text">
                        <?= nl2br(htmlspecialchars($job['description'])); ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- ============================= -->
        <!-- Side Card (Sidebar Actions) -->
        <!-- ============================= -->
        <div class="col-lg-4">
            <div class="card details-card border-0">
                <div class="card-body p-4">

                    <!-- Status -->
                    <div class="mb-4">
                        <span class="text-secondary d-block mb-2 fs-6 fw-semibold">Job Status</span>
                        <div>
                            <?php if (strtolower(trim($job['status'])) === 'open') { ?>
                                <span class="badge badge-status-open fs-6">
                                    <i class="bi bi-check-circle-fill me-1"></i> Open
                                </span>
                            <?php } else { ?>
                                <span class="badge badge-status-closed fs-6">
                                    <i class="bi bi-x-circle-fill me-1"></i> Closed
                                </span>
                            <?php } ?>
                        </div>
                    </div>

                    <hr>

                    <!-- Company Summary -->
                    <div class="mb-4">
                        <span class="text-secondary d-block mb-1 fs-6 fw-semibold">Company</span>
                        <h5 class="fw-bold m-0 text-white">
                            <?= htmlspecialchars($companyName); ?>
                        </h5>
                    </div>

                    <!-- Apply Button -->
                    <?php if (strtolower(trim($job['status'])) === 'open') { ?>
                        <a href="apply.php?job_id=<?= (int)$job['id']; ?>" class="btn btn-apply-now w-100">
                            <i class="bi bi-send-fill"></i> Apply Now
                        </a>
                    <?php } else { ?>
                        <button class="btn btn-closed-disabled w-100" disabled>
                            <i class="bi bi-lock-fill me-1"></i> Job Closed
                        </button>
                    <?php } ?>

                </div>
            </div>
        </div>

    </div>
</div>

<?php
include_once "includes/footer.php";
?>