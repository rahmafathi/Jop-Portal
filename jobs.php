<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "includes/db.php";
include_once "includes/functions.php";
include_once "includes/header.php";
include_once "includes/nav.php";

// =====================================================
// Dynamic Search Filters
// =====================================================
$where = "WHERE 1";

if (!empty($_GET['title'])) {
    $title = mysqli_real_escape_string($conn, trim($_GET['title']));
    $where .= " AND jobs.title LIKE '%$title%'";
}

if (!empty($_GET['location'])) {
    $location = mysqli_real_escape_string($conn, trim($_GET['location']));
    $where .= " AND jobs.location LIKE '%$location%'";
}

if (!empty($_GET['type'])) {
    $type = mysqli_real_escape_string($conn, trim($_GET['type']));
    $where .= " AND jobs.job_type = '$type'";
}

// =====================================================
// Fetch Jobs Query
// =====================================================
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
        $where
        ORDER BY jobs.id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}
$total_jobs = mysqli_num_rows($result);
?>

<!-- Bootstrap CDN & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Custom Embedded Styling -->
<style>
body {
    background-color: #050b14 !important;
    color: #e2e8f0 !important;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif !important;
}

/* Hero Section */
.jobs-hero {
    padding: 55px 0 25px;
    text-align: center;
    position: relative;
}
.hero-small {
    color: #00d2ff;
    font-size: 13px;
    letter-spacing: 2.5px;
    font-weight: 700;
    text-transform: uppercase;
    display: inline-block;
    margin-bottom: 10px;
    padding: 6px 16px;
    background: rgba(0, 210, 255, 0.08);
    border: 1px solid rgba(0, 210, 255, 0.2);
    border-radius: 20px;
}
.jobs-hero h1 {
    color: #ffffff;
    font-weight: 800;
    font-size: 40px;
    margin-bottom: 10px;
    letter-spacing: -0.5px;
}
.jobs-hero p {
    color: #94a3b8;
    font-size: 16px;
    max-width: 600px;
    margin: 0 auto;
}

/* Search Box Card */
.search-section {
    margin-top: 10px;
    margin-bottom: 45px;
}
.search-box {
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(0, 210, 255, 0.2);
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
}

/* Input Fields - High Contrast & Eye Friendly */
.custom-input {
    background-color: #1e293b !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    color: #ffffff !important;
    border-radius: 12px !important;
    height: 50px;
    font-size: 14px;
    padding-left: 15px;
    transition: all 0.3s ease;
}
.custom-input:focus {
    border-color: #00d2ff !important;
    background-color: #1e293b !important;
    box-shadow: 0 0 15px rgba(0, 210, 255, 0.3) !important;
    color: #ffffff !important;
}
.custom-input::placeholder {
    color: #94a3b8 !important;
}

/* Styling options inside select */
.custom-input option {
    background-color: #1e293b;
    color: #ffffff;
}

.search-btn {
    background: linear-gradient(135deg, #0d6efd, #00d2ff) !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    border-radius: 12px !important;
    height: 50px;
    border: none !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(0, 210, 255, 0.25);
}
.search-btn:hover {
    box-shadow: 0 6px 20px rgba(0, 210, 255, 0.4);
    transform: translateY(-2px);
}

/* Section Header */
.badge-count {
    background: rgba(0, 210, 255, 0.1);
    color: #00d2ff;
    border: 1px solid rgba(0, 210, 255, 0.25);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
}

/* Job Cards Styling - Clean & Eye Friendly */
.job-card {
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 26px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.job-card:hover {
    transform: translateY(-5px);
    border-color: rgba(0, 210, 255, 0.5);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6), 0 0 20px rgba(0, 210, 255, 0.2);
}
.company-logo {
    width: 48px;
    height: 48px;
    background: rgba(0, 210, 255, 0.12);
    color: #00d2ff;
    border: 1px solid rgba(0, 210, 255, 0.3);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 20px;
    flex-shrink: 0;
    box-shadow: 0 0 12px rgba(0, 210, 255, 0.15);
}
.job-title-area h4 {
    color: #ffffff;
    font-size: 19px;
    font-weight: 700;
    margin: 0 0 4px 0;
}
.job-title-area span {
    color: #94a3b8;
    font-size: 14px;
}
.status-badge {
    font-size: 11px;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 700;
    text-transform: uppercase;
    background: rgba(34, 197, 94, 0.15);
    color: #4ade80;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

/* Meta Pills - Clean White & Light Theme Text */
.badge-pill {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #f1f5f9;
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
}
.badge-pill.badge-highlight {
    background: rgba(0, 210, 255, 0.1);
    color: #38bdf8;
    border-color: rgba(0, 210, 255, 0.3);
}

.job-desc {
    color: #cbd5e1;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
}

.job-footer {
    padding-top: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.job-id-text {
    color: #94a3b8;
    font-size: 12px;
    font-weight: 600;
}

/* Professional View Details Button */
.btn-view-details {
    background: linear-gradient(135deg, #0d6efd, #00d2ff) !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 600 !important;
    padding: 10px 20px !important;
    border-radius: 12px !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    font-size: 14px !important;
    box-shadow: 0 4px 15px rgba(0, 210, 255, 0.25) !important;
    transition: all 0.3s ease !important;
}
.btn-view-details:hover {
    background: linear-gradient(135deg, #0b5ed7, #00bfe6) !important;
    color: #ffffff !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(0, 210, 255, 0.4) !important;
}
.btn-view-details:active {
    transform: translateY(0) !important;
}

/* Empty State */
.empty-box {
    background: rgba(15, 23, 42, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 18px;
    padding: 60px 20px;
}
</style>

<!-- =====================================================
     Hero Section
===================================================== -->
<section class="jobs-hero">
    <div class="container">
        <span class="hero-small">FIND YOUR CAREER</span>
        <h1>Available Jobs</h1>
        <p>Explore the latest opportunities from trusted companies and build your future with confidence.</p>
    </div>
</section>

<!-- =====================================================
     Search Section
===================================================== -->
<section class="search-section">
    <div class="container">
        <div class="search-box">
            <form action="" method="GET">
                <div class="row g-3 align-items-center">
                    
                    <!-- Job Title -->
                    <div class="col-lg-4 col-md-6">
                        <input 
                            type="text" 
                            name="title" 
                            class="form-control custom-input" 
                            placeholder="🔍 Job Title" 
                            value="<?= isset($_GET['title']) ? htmlspecialchars($_GET['title']) : ''; ?>"
                        >
                    </div>

                    <!-- Location -->
                    <div class="col-lg-3 col-md-6">
                        <input 
                            type="text" 
                            name="location" 
                            class="form-control custom-input" 
                            placeholder="📍 Location" 
                            value="<?= isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>"
                        >
                    </div>

                    <!-- Job Type -->
                    <div class="col-lg-3 col-md-6">
                        <select name="type" class="form-select custom-input">
                            <option value="">💼 Job Type (All)</option>
                            <option value="full time" <?= (isset($_GET['type']) && $_GET['type'] == 'full time') ? 'selected' : ''; ?>>Full-time</option>
                            <option value="part time" <?= (isset($_GET['type']) && $_GET['type'] == 'part time') ? 'selected' : ''; ?>>Part-time</option>
                            <option value="remote" <?= (isset($_GET['type']) && $_GET['type'] == 'remote') ? 'selected' : ''; ?>>Remote</option>
                            <option value="internship" <?= (isset($_GET['type']) && $_GET['type'] == 'internship') ? 'selected' : ''; ?>>Internship</option>
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div class="col-lg-2 col-md-6">
                        <button type="submit" class="btn search-btn w-100">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</section>

<!-- =====================================================
     Latest Jobs Listing
===================================================== -->
<section class="jobs-section pb-5">
    <div class="container">

        <!-- Section Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-white fs-4 m-0">Latest Job Opportunities</h2>
            <span class="badge-count"><?= $total_jobs; ?> Jobs Available</span>
        </div>

        <!-- Jobs Grid -->
        <div class="row g-4">
            <?php if ($total_jobs > 0) { ?>
                <?php while ($job = mysqli_fetch_assoc($result)) { 
                    $companyName = $job['company_name'] ?? 'Company';
                ?>
                    <div class="col-lg-6">
                        <div class="job-card">
                            
                            <div>
                                <!-- Header Info -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="company-logo">
                                            <?= strtoupper(substr($companyName, 0, 1)); ?>
                                        </div>
                                        <div class="job-title-area">
                                            <h4><?= htmlspecialchars($job['title']); ?></h4>
                                            <span><?= htmlspecialchars($companyName); ?></span>
                                        </div>
                                    </div>
                                    <span class="status-badge">
                                        <?= htmlspecialchars($job['status'] ?? 'Open'); ?>
                                    </span>
                                </div>

                                <!-- Meta Badges -->
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="badge-pill">
                                        📍 <?= htmlspecialchars($job['location']); ?>
                                    </span>
                                    <span class="badge-pill badge-highlight">
                                        💼 <?= htmlspecialchars($job['job_type']); ?>
                                    </span>
                                    <span class="badge-pill">
                                        💰 <?= (!empty($job['salary'])) ? number_format((float)$job['salary']) . " EGP" : "Not specified"; ?>
                                    </span>
                                    <span class="badge-pill">
                                        📂 <?= !empty($job['category_name']) ? htmlspecialchars($job['category_name']) : 'General'; ?>
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="job-desc">
                                    <?= htmlspecialchars(substr($job['description'], 0, 140)); ?>
                                    <?= (strlen($job['description']) > 140) ? '...' : ''; ?>
                                </p>
                            </div>

                            <!-- Footer -->
                            <div class="job-footer">
                                <span class="job-id-text">Job ID: #<?= (int)$job['id']; ?></span>
                                <a href="job_details.php?id=<?= (int)$job['id']; ?>" class="btn-view-details">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                            </div>

                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <!-- Empty State -->
                <div class="col-12">
                    <div class="empty-box text-center py-5">
                        <i class="bi bi-search text-muted fs-1 mb-3 d-block"></i>
                        <h3 class="text-white fs-4">No Jobs Found</h3>
                        <p class="text-muted m-0">There are no available jobs matching your criteria at the moment.</p>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</section>

<?php
include_once "includes/footer.php";
?>