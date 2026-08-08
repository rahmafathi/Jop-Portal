<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';

if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
    header("Location: my_jobs.php");
    exit();
}

$job_id = intval($_GET['job_id']);
$company_id = intval($_SESSION['company_id'] ?? 1);

$job_check = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $job_id AND company_id = $company_id");
if (!$job_check || mysqli_num_rows($job_check) === 0) {
    include_once '../includes/header.php';
    include_once '../includes/nav-company.php';
    echo "<div class='container my-5'><div class='alert alert-danger shadow-sm'>Job not found or unauthorized access.</div></div>";
    include_once '../includes/footer.php';
    exit();
}
$job = mysqli_fetch_assoc($job_check);

$query = "SELECT application.*, users.name, users.email, users.phone, users.cv_file 
          FROM application 
          JOIN users ON application.seeker_id = users.id 
          WHERE application.job_id = $job_id 
          ORDER BY application.applied_at DESC";
$result = mysqli_query($conn, $query);

// تضمين الهيدر والناف بار
include_once '../includes/header.php';
include_once '../includes/nav.php';
?>

<!-- FontAwesome & Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* ==========================================
    Job Applicants Page Styling - Dark Neon Theme
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

    .table {
        color: var(--text-main) !important;
        background-color: transparent !important;
        margin-bottom: 0 !important;
        border-color: rgba(255, 255, 255, 0.06) !important;
    }

    .table>thead {
        background-color: rgba(30, 41, 59, 0.5) !important;
        color: var(--text-muted) !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
    }

    .table>thead th {
        background-color: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        padding: 18px 16px !important;
        color: var(--text-muted) !important;
        font-weight: 700;
    }

    .table>tbody td {
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

    .applicant-name {
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    .applicant-contact {
        color: #cbd5e1 !important;
    }

    .btn-view-cv {
        background: rgba(56, 189, 248, 0.1);
        color: var(--primary-neon);
        border: 1px solid rgba(56, 189, 248, 0.3);
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-view-cv:hover {
        background: var(--primary-neon);
        color: #030712;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
    }
</style>

<div class="container my-5" dir="ltr">

    <div class="card card-custom-table border-0">

        <div class="card-header card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="page-title mb-1">
                    <i class="fas fa-user-tie me-2" style="color: var(--primary-neon);"></i> Applicants for:
                    <?php echo htmlspecialchars($job['title']); ?>
                </h2>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Review candidates who applied for this position.
                </p>
            </div>

            <a href="my_jobs.php" class="btn btn-back-custom">
                <i class="fas fa-arrow-left"></i> Back to My Jobs
            </a>
        </div>

        <div class="card-body p-3 p-md-4">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th scope="col">Applicant Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Applied Date</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($result && mysqli_num_rows($result) > 0): ?>

                            <?php while ($app = mysqli_fetch_assoc($result)): ?>

                                <tr>
                                    <td class="applicant-name">
                                        <?php echo htmlspecialchars($app['name'] ?? 'Candidate'); ?>
                                    </td>

                                    <td class="applicant-contact">
                                        <?php echo htmlspecialchars($app['email'] ?? 'N/A'); ?>
                                    </td>

                                    <td class="text-muted" style="font-size: 0.9rem;">
                                        <?php echo htmlspecialchars($app['applied_at'] ?? 'N/A'); ?>
                                    </td>

                                    <td class="text-end">
                                        <?php if (!empty($app['cv_file'])): ?>
                                            <a href="../uploads/cvs/<?php echo htmlspecialchars($app['cv_file']); ?>"
                                                target="_blank" class="btn btn-sm btn-view-cv">
                                                <i class="fas fa-file-pdf me-1"></i> View CV
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-size: 0.85rem;">No CV File</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="my-3">
                                        <i class="fas fa-folder-open text-muted"
                                            style="font-size: 3.5rem; opacity: 0.5;"></i>
                                    </div>
                                    <h4 class="text-white fw-bold mb-2">No Candidates Found</h4>
                                    <p class="text-muted mb-0">No candidates have applied for this job yet.</p>
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
include_once '../includes/footer.php';
?>