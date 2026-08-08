<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';

if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}

$company_id = intval($_SESSION['company_id']);

$sql = "SELECT 
            application.id,
            users.name,
            users.email,
            users.phone,
            jobs.title,
            application.status,
            application.applied_at
        FROM application
        INNER JOIN jobs ON application.job_id = jobs.id
        INNER JOIN users ON application.seeker_id = users.id
        WHERE jobs.company_id = '$company_id'
        ORDER BY application.applied_at DESC";

$result = mysqli_query($conn, $sql);

// تضمين الهيدر والناف بار
include_once '../includes/header.php';
include_once '../includes/nav-company.php';
?>

<!-- FontAwesome & Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* ==========================================
    View Applicants Page Styling - Dark Neon Theme
    ========================================== */

:root {
    --bg-main: #030712;
    --card-bg: #0f172a;
    --border-color: rgba(255, 255, 255, 0.08);
    --primary-neon: #38bdf8;
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
}

body {
    background-color: var(--bg-main) !important;
    color: var(--text-main) !important;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
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

/* رأس الكارت وتنسيقه */
.card-header-custom {
    background: rgba(15, 23, 42, 0.95) !important;
    border-bottom: 1px solid var(--border-color) !important;
    padding: 22px 28px;
    border-top-left-radius: 20px !important;
    border-top-right-radius: 20px !important;
}

.page-title {
    color: var(--text-main);
    font-weight: 800;
    font-size: 1.6rem;
    letter-spacing: -0.5px;
    text-shadow: 0 0 15px rgba(56, 189, 248, 0.2);
}

/* زر الرجوع (Back) */
.btn-back-custom {
    background: rgba(56, 189, 248, 0.08) !important;
    color: var(--primary-neon) !important;
    border: 1px solid rgba(56, 189, 248, 0.25) !important;
    border-radius: 12px !important;
    font-weight: 600;
    padding: 9px 18px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-back-custom:hover {
    background: var(--primary-neon) !important;
    color: #030712 !important;
    box-shadow: 0 0 20px rgba(56, 189, 248, 0.4);
    border-color: var(--primary-neon) !important;
}

/* تنسيق الجدول وإلغاء أي خلفيات بيضاء */
.table {
    color: var(--text-main) !important;
    background-color: transparent !important;
    margin-bottom: 0 !important;
    border-color: rgba(255, 255, 255, 0.06) !important;
}

.table > thead {
    background-color: rgba(30, 41, 59, 0.5) !important;
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

/* ألوان البيانات المحددة لتكون واضحة */
.applicant-name {
    color: #ffffff !important;
    font-weight: 700 !important;
}

.applicant-contact {
    color: #cbd5e1 !important;
}

.job-title-highlight {
    color: var(--primary-neon) !important;
    font-weight: 600 !important;
}

/* الـ Badges لحالة المتقدم */
.badge-pending {
    background-color: rgba(245, 158, 11, 0.12) !important;
    color: #fbbf24 !important;
    border: 1px solid rgba(245, 158, 11, 0.3);
    padding: 7px 14px;
    border-radius: 10px;
    font-weight: 600;
    display: inline-block;
}

.badge-accepted {
    background-color: rgba(16, 185, 129, 0.12) !important;
    color: #34d399 !important;
    border: 1px solid rgba(16, 185, 129, 0.3);
    padding: 7px 14px;
    border-radius: 10px;
    font-weight: 600;
    display: inline-block;
}

.badge-rejected {
    background-color: rgba(239, 68, 68, 0.12) !important;
    color: #f87171 !important;
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 7px 14px;
    border-radius: 10px;
    font-weight: 600;
    display: inline-block;
}
</style>

<div class="container my-5" dir="ltr">

    <div class="card card-custom-table border-0">

        <div class="card-header card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h3 class="page-title mb-0">
                <i class="fas fa-users me-2" style="color: var(--primary-neon);"></i> View Applicants
            </h3>

            <a href="dashboard.php" class="btn btn-back-custom">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="card-body p-3 p-md-4">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Applicant Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Job Title</th>
                            <th>Status</th>
                            <th>Applied At</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if($result && mysqli_num_rows($result) > 0): ?>

                        <?php while($row = mysqli_fetch_assoc($result)): ?>

                        <tr>
                            <td class="text-muted fw-bold">#<?= $row['id']; ?></td>

                            <td class="applicant-name"><?= htmlspecialchars($row['name']); ?></td>

                            <td class="applicant-contact"><?= htmlspecialchars($row['email']); ?></td>

                            <td class="applicant-contact"><?= htmlspecialchars($row['phone']); ?></td>

                            <td class="job-title-highlight"><?= htmlspecialchars($row['title']); ?></td>

                            <td>
                                <?php
                                $status = strtolower($row['status']);
                                if($status == "pending"){
                                    echo "<span class='badge-pending'>Pending</span>";
                                } elseif($status == "accepted"){
                                    echo "<span class='badge-accepted'>Accepted</span>";
                                } else {
                                    echo "<span class='badge-rejected'>Rejected</span>";
                                }
                                ?>
                            </td>

                            <td class="text-muted" style="font-size: 0.9rem;"><?= $row['applied_at']; ?></td>
                        </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="my-3">
                                    <i class="fas fa-folder-open text-muted" style="font-size: 3.5rem; opacity: 0.5;"></i>
                                </div>
                                <h4 class="text-white fw-bold mb-2">No Applicants Found</h4>
                                <p class="text-muted mb-0">There are no job applications submitted yet.</p>
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php 
// تضمين الفوتر
include_once '../includes/footer.php'; 
?>