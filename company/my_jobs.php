<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
include_once '../includes/header.php';
include_once '../includes/nav-company.php';

$company_id = intval($_SESSION['company_id'] ?? 1);

// التحقق مما إذا كان هناك فلتر للحالة في الـ URL (open أو closed)
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

// بناء الاستعلام بناءً على وجود فلتر الحالة أم لا
$query = "SELECT * FROM jobs WHERE company_id = '$company_id'";

if ($status_filter === 'open' || $status_filter === 'closed') {
    $query .= " AND status = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
}

$query .= " ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!-- FontAwesome & Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* ==========================================
   My Jobs Page Styling - Enhanced Neon Typography
   ========================================== */

:root {
    --bg-main: #030712;
    --card-bg: #0f172a;
    --border-color: rgba(56, 189, 248, 0.15);
    --primary-neon: #38bdf8;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --neon-glow: 0 0 20px rgba(56, 189, 248, 0.25);
}

body {
    background-color: var(--bg-main) !important;
    color: var(--text-main) !important;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    letter-spacing: 0.3px;
}

/* العناوين والـ Header الخاص بالصفحة مع لمسة إضاءة */
.page-title {
    color: #ffffff;
    font-weight: 800;
    font-size: 1.9rem;
    letter-spacing: -0.5px;
    text-shadow: 0 0 15px rgba(56, 189, 248, 0.3);
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 0.95rem;
    font-weight: 400;
}

/* زر إضافة وظيفة جديدة (تنسيق نيون مطور) */
.btn-neon-primary {
    background: linear-gradient(135deg, #0284c7, var(--primary-neon)) !important;
    color: #030712 !important;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 10px 22px;
    border: none !important;
    border-radius: 12px !important;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(56, 189, 248, 0.4);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-neon-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(56, 189, 248, 0.7);
    color: #030712 !important;
}

/* الكارت الحامل للجدول بتصميم الجلاسمورفيزم الغامق */
.card-custom-table {
    background: var(--card-bg) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: 20px !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    position: relative;
    overflow: hidden;
}

.card-custom-table::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #0284c7, var(--primary-neon));
}

/* تنسيق الجدول وإلغاء أي خلفيات افتراضية */
.table {
    color: var(--text-main) !important;
    background-color: transparent !important;
    margin-bottom: 0 !important;
    border-color: rgba(255, 255, 255, 0.06) !important;
}

.table > thead {
    background-color: rgba(30, 41, 59, 0.7) !important;
    color: var(--text-muted) !important;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 1px;
}

.table > thead th {
    background-color: transparent !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    padding: 18px 16px !important;
    color: var(--text-muted) !important;
    font-weight: 700;
}

.table > tbody td {
    background-color: transparent !important;
    padding: 18px 16px !important;
    vertical-align: middle !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04) !important;
    color: #e2e8f0 !important;
    font-size: 0.95rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(56, 189, 248, 0.04) !important;
}

/* خط عناوين الوظائف داخل الجدول بوضوح ونقاء عالي */
.job-title-text {
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 1.05rem;
}

/* الـ Badges بتصميم نيون أنيق */
.badge-type {
    background-color: rgba(56, 189, 248, 0.1) !important;
    color: var(--primary-neon) !important;
    border: 1px solid rgba(56, 189, 248, 0.3);
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
}

.badge-status-open {
    background-color: rgba(16, 185, 129, 0.15) !important;
    color: #34d399 !important;
    border: 1px solid rgba(16, 185, 129, 0.3);
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.85rem;
}

.badge-status-closed {
    background-color: rgba(239, 68, 68, 0.15) !important;
    color: #f87171 !important;
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.85rem;
}

/* أزرار الإجراءات داخل الجدول */
.btn-action-view {
    background: rgba(13, 202, 240, 0.1) !important;
    color: #0dcaf0 !important;
    border: 1px solid rgba(13, 202, 240, 0.3) !important;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-action-view:hover {
    background: #0dcaf0 !important;
    color: #030712 !important;
    box-shadow: 0 0 15px rgba(13, 202, 240, 0.4);
}

.btn-action-edit {
    background: rgba(56, 189, 248, 0.1) !important;
    color: var(--primary-neon) !important;
    border: 1px solid rgba(56, 189, 248, 0.3) !important;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-action-edit:hover {
    background: var(--primary-neon) !important;
    color: #030712 !important;
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
}

.btn-action-delete {
    background: rgba(239, 68, 68, 0.1) !important;
    color: #f87171 !important;
    border: 1px solid rgba(239, 68, 68, 0.3) !important;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-action-delete:hover {
    background: #ef4444 !important;
    color: #ffffff !important;
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);
}
</style>

<div class="container my-5" dir="ltr">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="page-title mb-1">My Posted Jobs</h2>
            <p class="page-subtitle mb-0">Manage your job listings, view applicants, and update details seamlessly.</p>
        </div>

        <a href="add_jobs.php" class="btn btn-neon-primary">
            <i class="fas fa-plus"></i> Add New Job
        </a>
    </div>

    <?php
    if (function_exists('displayMessage')) {
        displayMessage();
    }
    ?>

    <div class="card card-custom-table border-0 p-3 p-md-4">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Type</th>
                        <th>Salary</th>
                        <th>Applicants</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php if ($result && mysqli_num_rows($result) > 0): ?>

                    <?php while ($job = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <span class="job-title-text">
                                    <?php echo htmlspecialchars($job['title']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge-type">
                                    <?php echo htmlspecialchars($job['job_type']); ?>
                                </span>
                            </td>

                            <td style="color: #cbd5e1; font-weight: 600;">
                                <?php
                                echo !empty($job['salary'])
                                    ? htmlspecialchars($job['salary'])
                                    : '<span class="text-muted fw-normal">Not Specified</span>';
                                ?>
                            </td>

                            <td>
                                <?php
                                $job_id = $job['id'];

                                $count_query = mysqli_query(
                                    $conn,
                                    "SELECT COUNT(*) AS total FROM application WHERE job_id='$job_id'"
                                );

                                $count = mysqli_fetch_assoc($count_query)['total'];
                                ?>

                                <?php if($count > 0): ?>

                                    <a href="view_applicants.php?job_id=<?php echo $job_id; ?>"
                                       class="btn btn-sm btn-action-view">
                                        <i class="fas fa-users me-1"></i> View (<?php echo $count; ?>)
                                    </a>

                                <?php else: ?>

                                    <span class="text-muted" style="font-size: 0.85rem;">
                                        No Applicants
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>
                                <?php 
                                $status_lower = strtolower($job['status']);
                                $badge_class = ($status_lower == 'open') ? 'badge-status-open' : 'badge-status-closed';
                                ?>
                                <span class="<?php echo $badge_class; ?>">
                                    <?php echo ucfirst(htmlspecialchars($job['status'])); ?>
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="edit_job.php?id=<?php echo $job_id; ?>"
                                       class="btn btn-sm btn-action-edit"
                                       title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <a href="delete_job.php?id=<?php echo $job_id; ?>"
                                       class="btn btn-sm btn-action-delete"
                                       title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this job?');">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </a>
                                </div>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="my-3">
                                <i class="fas fa-folder-open text-muted" style="font-size: 3.5rem; opacity: 0.5;"></i>
                            </div>
                            <h4 class="text-white fw-bold mb-2">No Jobs Found</h4>
                            <p class="text-muted mb-3" style="font-size: 0.95rem;">
                                No jobs found. Start by posting your first job vacancy!
                            </p>
                            <a href="add_jobs.php" class="btn btn-neon-primary">
                                <i class="fas fa-plus"></i> Add New Job
                            </a>
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php 
// تضمين الفوتر في نهاية الصفحة بشكل صحيح
include_once '../includes/footer.php'; 
?>