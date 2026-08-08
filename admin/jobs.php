<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../includes/db.php";

//====================== DELETE JOB ======================

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($conn) {
        mysqli_query($conn, "DELETE FROM jobs WHERE id='$id'");
    }
    header("Location: jobs.php");
    exit();
}

//====================== STATISTICS ======================

$total_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs")) ?? ['total' => 0];
$open_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs WHERE status='open'")) ?? ['total' => 0];
$closed_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs WHERE status='closed'")) ?? ['total' => 0];
$total_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM application")) ?? ['total' => 0];

//====================== GET JOBS ======================

$query = "
SELECT jobs.*,
companies.company_name,
categories.category_name
FROM jobs
LEFT JOIN companies
ON jobs.company_id = companies.id
LEFT JOIN categories
ON jobs.category_id = categories.id
ORDER BY jobs.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs Management | Admin Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/users.css?v=<?php echo time(); ?>">

    <!-- Professional Styling Matching Dashboard -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
        }

        /* Stats Cards Styling */
        .stat-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(112, 144, 176, 0.08);
            transition: all 0.3s ease-in-out;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 15px 35px rgba(112, 144, 176, 0.15);
        }

        .icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        /* Job Cards Styling */
        .job-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(112, 144, 176, 0.08);
            transition: all 0.3s ease-in-out;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 15px 35px rgba(112, 144, 176, 0.15);
        }

        .badge {
            font-weight: 600;
            letter-spacing: 0.3px;
            font-size: 0.75rem;
            padding: 6px 12px;
        }

        .card-body p {
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: #718096;
        }

        .card-body hr {
            border-color: #edf2f7;
            opacity: 1;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0px 15px 35px rgba(112, 144, 176, 0.2);
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">

        <?php include 'sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #2b3674; font-size: 1.75rem;">Jobs Management</h3>
                    <p class="text-muted small mb-0" style="font-size: 0.9rem;">Manage all jobs posted by companies</p>
                </div>
            </div>

            <!-- ====================== STATISTICS ====================== -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Jobs</span>
                                <h3 class="fw-bold mt-1 mb-0" style="color: #2b3674;"><?= $total_jobs['total']; ?></h3>
                            </div>
                            <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="fas fa-briefcase"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Open Jobs</span>
                                <h3 class="fw-bold mt-1 mb-0 text-success"><?= $open_jobs['total']; ?></h3>
                            </div>
                            <div class="icon-box bg-success bg-opacity-10 text-success"><i class="fas fa-door-open"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Closed Jobs</span>
                                <h3 class="fw-bold mt-1 mb-0 text-danger"><?= $closed_jobs['total']; ?></h3>
                            </div>
                            <div class="icon-box bg-danger bg-opacity-10 text-danger"><i class="fas fa-lock"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Applications</span>
                                <h3 class="fw-bold mt-1 mb-0 text-info"><?= $total_applications['total']; ?></h3>
                            </div>
                            <div class="icon-box bg-info bg-opacity-10 text-info"><i class="fas fa-paper-plane"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====================== JOB CARDS ====================== -->
            <div class="row g-4">
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($job = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-lg-6">
                    <div class="card job-card h-100 p-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <!-- Job Title -->
                                    <h5 class="fw-bold mb-2" style="color: #2b3674;"><?= htmlspecialchars($job['title']); ?></h5>
                                    <!-- Company Name -->
                                    <p class="mb-1 fw-semibold text-primary">
                                        <i class="fas fa-building me-1"></i> <?= htmlspecialchars($job['company_name'] ?? 'N/A'); ?>
                                    </p>
                                    <!-- Category Name -->
                                    <p class="mb-0 text-secondary">
                                        <i class="fas fa-layer-group me-1"></i> <?= htmlspecialchars($job['category_name'] ?? 'N/A'); ?>
                                    </p>
                                </div>
                                <div>
                                    <?php if (strtolower($job['status']) == "open"): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Open</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Closed</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr>

                            <div class="row g-2">
                                <div class="col-6">
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i> <?= htmlspecialchars($job['location'] ?? 'N/A'); ?>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2">
                                        <i class="fas fa-money-bill-wave text-success me-1"></i> <?= htmlspecialchars($job['salary'] ?? 'N/A'); ?>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0">
                                        <i class="fas fa-briefcase text-warning me-1"></i> <?= htmlspecialchars($job['job_type'] ?? 'N/A'); ?>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0">
                                        <i class="fas fa-calendar text-muted me-1"></i> <?= date("d M Y", strtotime($job['created_at'])); ?>
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-end gap-2">
                                <!-- View Button triggers Modal -->
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#jobModal<?= $job['id']; ?>">
                                    <i class="fas fa-eye me-1"></i> View
                                </button>
                                <!-- Delete Button -->
                                <a href="jobs.php?delete=<?= $job['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold" onclick="return confirm('Delete this Job?');">
                                    <i class="fas fa-trash-can me-1"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Details Modal -->
                <div class="modal fade" id="jobModal<?= $job['id']; ?>" tabindex="-1" aria-labelledby="jobModalLabel<?= $job['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-3">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold" id="jobModalLabel<?= $job['id']; ?>" style="color: #2b3674;">
                                    <i class="fas fa-info-circle text-primary me-2"></i>Job Details
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="text-muted small fw-semibold text-uppercase">Job Title</label>
                                    <h6 class="fw-bold text-dark mt-1"><?= htmlspecialchars($job['title']); ?></h6>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="text-muted small fw-semibold text-uppercase">Company</label>
                                        <p class="fw-semibold text-primary mt-1 mb-0"><?= htmlspecialchars($job['company_name'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted small fw-semibold text-uppercase">Category</label>
                                        <p class="fw-semibold text-secondary mt-1 mb-0"><?= htmlspecialchars($job['category_name'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="text-muted small fw-semibold text-uppercase">Location</label>
                                        <p class="text-dark mt-1 mb-0"><i class="fas fa-map-marker-alt text-danger me-1"></i><?= htmlspecialchars($job['location'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted small fw-semibold text-uppercase">Salary</label>
                                        <p class="text-dark mt-1 mb-0"><i class="fas fa-money-bill-wave text-success me-1"></i><?= htmlspecialchars($job['salary'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="text-muted small fw-semibold text-uppercase">Job Type</label>
                                        <p class="text-dark mt-1 mb-0"><i class="fas fa-briefcase text-warning me-1"></i><?= htmlspecialchars($job['job_type'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted small fw-semibold text-uppercase">Status</label>
                                        <p class="mt-1 mb-0">
                                            <?php if (strtolower($job['status']) == "open"): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Open</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">Closed</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-muted small fw-semibold text-uppercase">Posted Date</label>
                                    <p class="text-muted mt-1 mb-0"><i class="fas fa-calendar me-1"></i><?= date("d M Y - h:i A", strtotime($job['created_at'])); ?></p>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-briefcase fa-3x text-muted mb-3 opacity-50"></i>
                            <h4 class="fw-bold" style="color: #2b3674;">No Jobs Found</h4>
                            <p class="text-muted mb-0">There are no jobs available yet.</p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>