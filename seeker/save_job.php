<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'job_seeker') {
    redirect('../login.php');
}

$userId = $_SESSION['user_id'];

// استخدام Prepared Statement لجلب الوظائف المحفوظة بأمان تام
$sql = "SELECT j.*, c.company_name, cat.category_name 
        FROM saved_jobs sj 
        INNER JOIN jobs j ON sj.job_id = j.id 
        INNER JOIN companies c ON j.company_id = c.id 
        INNER JOIN categories cat ON j.category_id = cat.id 
        WHERE sj.seeker_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

require_once '../includes/header.php';
require_once '../includes/nav-seeker.php';
?>

<!-- Professional UI Styling (Dark & Neon Theme) -->
<style>
    :root {
        --bg-main: #0b0f19;
        --card-bg: #111827;
        --border-color: rgba(255, 255, 255, 0.07);
        --primary-neon: #38bdf8;
        --text-main: #f3f4f6;
        --text-muted: #9ca3af;
    }

    body {
        background-color: var(--bg-main) !important;
        color: var(--text-main) !important;
    }

    .saved-page-wrapper {
        max-width: 1200px;
        margin: 45px auto;
        padding: 0 20px;
    }

.page-main-title {
        font-size: 34px;
        font-weight: 800;
        background: linear-gradient(135deg, #f3f4f6 30%, #38bdf8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 35px;
        letter-spacing: 0.8px;
        text-shadow: 0 0 25px rgba(56, 189, 248, 0.2);
        display: flex;
        align-items: center;
        justify-content: center; /* دي بتخليه في النص تماماً مع الأيقونة */
        gap: 12px;
    }

    .page-main-title i {
        -webkit-text-fill-color: initial; /* عشان الأيقونة تحتفظ بلونها الأصفر الجميل بتاعها */
        filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.4));
    }

    /* Ultra Pro Saved Job Card Design */
    .pro-job-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
        position: relative;
    }

    .pro-job-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #0284c7, #38bdf8);
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }

    .pro-job-card:hover {
        border-color: rgba(56, 189, 248, 0.4);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.5), 0 0 20px rgba(56, 189, 248, 0.15);
        transform: translateY(-5px);
    }

    .pro-job-card:hover::before {
        opacity: 1;
    }

    .pro-job-card .card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        gap: 16px;
    }

    .job-header-area {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .job-title-top {
        font-size: 19px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
        line-height: 1.4;
    }

    .job-company-top {
        font-size: 13.5px;
        color: var(--primary-neon) !important;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .job-body-area {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .pro-badge {
        background: rgba(56, 189, 248, 0.08) !important;
        color: var(--primary-neon) !important;
        border: 1px solid rgba(56, 189, 248, 0.2);
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 11.5px;
        width: fit-content;
        letter-spacing: 0.3px;
    }

    .job-desc {
        font-size: 13.5px;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
    }

    /* Action Buttons Group */
    .job-buttons-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding-top: 6px;
        border-top: 1px solid rgba(255, 255, 255, 0.04);
        margin-top: 4px;
    }

    .pro-btn {
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 10px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .pro-btn-view {
        background: rgba(56, 189, 248, 0.08);
        color: var(--primary-neon);
        border: 1px solid rgba(56, 189, 248, 0.25);
    }
    .pro-btn-view:hover {
        background: var(--primary-neon);
        color: #0b0f19;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
    }

    .pro-btn-remove {
        background: rgba(239, 68, 68, 0.08);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.25);
    }
    .pro-btn-remove:hover {
        background: #ef4444;
        color: #fff;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);
    }

    /* Empty Box */
    .pro-alert-box {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        border-radius: 18px;
        padding: 50px 20px;
        text-align: center;
        font-size: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .pro-alert-box h4 {
        color: var(--text-main);
        font-weight: 700;
        margin-bottom: 12px;
        font-size: 22px;
    }

    .pro-browse-btn {
        background: linear-gradient(135deg, #0284c7, #38bdf8);
        color: #ffffff;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        padding: 10px 25px;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin-top: 15px;
    }
    .pro-browse-btn:hover {
        background: linear-gradient(135deg, #0369a1, #0284c7);
        box-shadow: 0 0 22px rgba(56, 189, 248, 0.7);
        color: #fff;
    }
</style>

<div class="saved-page-wrapper">
<h2 class="page-main-title text-center">
        <i class="bi bi-bookmark-fill text-warning"></i> My Saved Jobs
    </h2>
    
    <div class="row g-4">
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($job = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="pro-job-card">
                        <div class="card-body">
                            <div class="job-header-area">
                                <h4 class="job-title-top"><?= sanitize($job['title']); ?></h4>
                                <h6 class="job-company-top"><i class="bi bi-building"></i> <?= sanitize($job['company_name']); ?></h6>
                            </div>
                            
                            <div class="job-body-area">
                                <span class="badge pro-badge"><?= sanitize($job['category_name']); ?></span>
                                <p class="job-desc"><?= substr(sanitize($job['description']), 0, 90); ?>...</p>
                            </div>

                            <div class="job-buttons-group">
                                <a href="../job_details.php?id=<?= $job['id']; ?>" class="pro-btn pro-btn-view">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <!-- تم إضافة redirect=saved هنا لكي يتم التوجيه لنفس الصفحة تلقائياً -->
                                <a href="toggle_save.php?job_id=<?= $job['id']; ?>&redirect=saved" class="pro-btn pro-btn-remove" onclick="return confirm('Are you sure you want to remove this job from saved items?');">
                                    <i class="bi bi-trash"></i> Remove
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="pro-alert-box">
                    <h4>No saved jobs yet.</h4>
                    <p class="text-muted">Explore available jobs and save your favorites to review them later.</p>
                    <a href="jobs.php" class="pro-browse-btn">Browse Jobs Now</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
// تنظيف الـ Statement بعد الانتهاء
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}
require_once '../includes/footer.php'; 
?>