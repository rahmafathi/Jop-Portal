<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'job_seeker') {
    header("Location: ../login.php");
    exit();
}

include_once "../includes/db.php";
include_once "../includes/header.php";

if (!isset($_GET['id'])) {
    die("Job not found");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT jobs.*, companies.company_name, categories.category_name
FROM jobs
JOIN companies ON jobs.company_id = companies.id
JOIN categories ON jobs.category_id = categories.id
WHERE jobs.id='$id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Job not found");
}

$job = mysqli_fetch_assoc($result);
?>

<style>
    :root {
        --bg-main: #0b0f19;
        --card-bg: rgba(17, 24, 39, 0.85);
        --border-color: rgba(255, 255, 255, 0.08);
        --primary-neon: #38bdf8;
        --accent-glow: rgba(56, 189, 248, 0.25);
        --text-main: #f3f4f6;
        --text-muted: #9ca3af;
    }

    body {
        background-color: var(--bg-main) !important;
        color: var(--text-main) !important;
    }

    .details-wrapper {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .details-card {
        background: var(--card-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 20px var(--accent-glow);
    }

    .job-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 5px;
    }

    .company-name {
        font-size: 18px;
        color: var(--primary-neon) !important;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 30px 0;
        background: rgba(0,0,0,0.2);
        padding: 20px;
        border-radius: 12px;
    }

    .info-item p {
        margin: 0;
        color: var(--text-muted);
        font-size: 14px;
    }

    .info-item strong {
        display: block;
        color: var(--text-main);
        font-size: 16px;
    }

    .section-title {
        color: var(--primary-neon);
        font-size: 20px;
        margin-top: 30px;
        margin-bottom: 15px;
        border-left: 4px solid var(--primary-neon);
        padding-left: 15px;
    }

    .description-text {
        color: var(--text-muted);
        line-height: 1.8;
        font-size: 16px;
    }

    /* Buttons Styling */
    .btn-action-container {
        margin-top: 40px;
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .pro-btn {
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    /* Professional Back Button */
    .btn-back {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: var(--text-main);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    .btn-back:hover {
        background: rgba(56, 189, 248, 0.1);
        border-color: rgba(56, 189, 248, 0.4);
        color: var(--primary-neon);
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.25);
        transform: translateY(-2px);
    }
</style>

<div class="details-wrapper">
    <div class="details-card">
        <h2 class="job-title"><?= htmlspecialchars($job['title']); ?></h2>
        <h5 class="company-name"><?= htmlspecialchars($job['company_name']); ?></h5>
        
        <div class="info-grid">
            <div class="info-item"><p>Category</p><strong><?= htmlspecialchars($job['category_name']); ?></strong></div>
            <div class="info-item"><p>Location</p><strong><?= htmlspecialchars($job['location']); ?></strong></div>
            <div class="info-item"><p>Salary</p><strong><?= htmlspecialchars($job['salary']); ?></strong></div>
            <div class="info-item"><p>Job Type</p><strong><?= htmlspecialchars($job['job_type']); ?></strong></div>
            <div class="info-item"><p>Experience</p><strong><?= htmlspecialchars($job['experience']); ?></strong></div>
        </div>

        <h4 class="section-title">Job Description</h4>
        <div class="description-text"><?= nl2br(htmlspecialchars($job['description'])); ?></div>

        <h4 class="section-title">Requirements</h4>
        <div class="description-text"><?= nl2br(htmlspecialchars($job['requirements'])); ?></div>

        <div class="btn-action-container">
            <a href="../seeker/jobs.php" class="pro-btn btn-back">
                <i class="fas fa-arrow-left"></i> Back to Jobs
            </a>
        </div>
    </div>
</div>

<?php
include_once "../includes/footer.php";
?>