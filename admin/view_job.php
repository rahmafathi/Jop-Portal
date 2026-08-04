<?php
// admin/view_job.php
include '../includes/db.php';

if(isset($_GET['id'])){
    $job_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT j.*, c.company_name, c.email as company_email FROM jobs j LEFT JOIN companies c ON j.company_id = c.id WHERE j.id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $job = $result->fetch_assoc();
    } else {
        header('location: jobs.php');
        exit;
    }
} else {
    header('location: jobs.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Job Details | Job Portal</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/users.css?v=<?php echo time(); ?>">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f7f6; }
        .professional-card { background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid #edf2f7; }
        .header-section { background: linear-gradient(135deg, #2b3674 0%, #192550 100%); color: white; border-radius: 16px 16px 0 0; padding: 30px; }
        .info-box { background: #f8f9fa; border-radius: 12px; padding: 20px; height: 100%; border: 1px solid #e2e8f0; transition: all 0.3s ease; }
        .info-box:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.05); }
        .info-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; color: #a0aec0; font-weight: 700; margin-bottom: 5px; display: block; }
        .info-value { font-size: 1.05rem; color: #2b3674; font-weight: 600; }
        .content-box { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 25px; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #2b3674;">Job Overview</h3>
                    <p class="text-muted small mb-0">Detailed view of the selected job listing.</p>
                </div>
                <div>
                    <a href="jobs.php" class="btn btn-light border fw-semibold px-3"><i class="fa-solid fa-arrow-left me-1"></i> Back to Jobs</a>
                </div>
            </div>

            <!-- Professional Details Card -->
            <div class="professional-card mb-4">
                <!-- Header Banner inside Card -->
                <div class="header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <span class="badge bg-light text-dark px-3 py-1 mb-2 fw-bold"><?= htmlspecialchars($job['job_type'] ?? 'Full Time'); ?></span>
                        <h2 class="fw-bold mb-1 text-white"><?= htmlspecialchars($job['title']); ?></h2>
                        <p class="text-white-50 mb-0"><i class="fa-solid fa-building me-1"></i> <?= htmlspecialchars($job['company_name'] ?? 'Direct Admin Post'); ?></p>
                    </div>
                    <div>
                        <?php if(($job['status'] ?? 'open') == 'open'): ?>
                            <span class="badge bg-success px-3 py-2 fs-6 shadow-sm"><i class="fa-solid fa-circle-check me-1"></i> Open Position</span>
                        <?php else: ?>
                            <span class="badge bg-danger px-3 py-2 fs-6 shadow-sm"><i class="fa-solid fa-circle-xmark me-1"></i> Closed</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Body Content -->
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-label"><i class="fa-solid fa-location-dot me-1 text-primary"></i> Location</span>
                                <span class="info-value"><?= htmlspecialchars($job['location'] ?? 'Remote'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-label"><i class="fa-solid fa-wallet me-1 text-success"></i> Salary Range</span>
                                <span class="info-value text-success fw-bold"><?= htmlspecialchars($job['salary'] ?? 'Not Specified'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-label"><i class="fa-solid fa-briefcase me-1 text-warning"></i> Job Type</span>
                                <span class="info-value"><?= htmlspecialchars($job['job_type'] ?? 'Full Time'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-label"><i class="fa-solid fa-calendar me-1 text-info"></i> Posted On</span>
                                <span class="info-value"><?= isset($job['created_at']) ? date('M d, Y', strtotime($job['created_at'])) : 'Recent'; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div class="content-box">
                        <h5 class="fw-bold mb-3" style="color: #2b3674;"><i class="fa-solid fa-align-left text-primary me-2"></i> Job Description</h5>
                        <div class="text-secondary" style="line-height: 1.8; white-space: pre-line;">
                            <?= htmlspecialchars($job['description']); ?>
                        </div>
                    </div>

                    <!-- Requirements if available -->
                    <?php if(!empty($job['requirements'])): ?>
                    <div class="content-box">
                        <h5 class="fw-bold mb-3" style="color: #2b3674;"><i class="fa-solid fa-list-check text-success me-2"></i> Requirements & Qualifications</h5>
                        <div class="text-secondary" style="line-height: 1.8; white-space: pre-line;">
                            <?= htmlspecialchars($job['requirements']); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>