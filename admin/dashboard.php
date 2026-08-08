<?php
// admin/admin/dashboard.php
include '../includes/db.php';

function getCount($conn, $query) {
    if (!$conn) return 0;
    try {
        $res = $conn->query($query);
        if ($res) {
            $row = $res->fetch_assoc();
            return $row['total'] ?? 0;
        }
    } catch (Exception $e) { return 0; }
    return 0;
}

// تعديل الاستعلام بحيث يقوم بحساب عدد المستخدمين بغض النظر عن حالة أو شكل عمود الدور (role) أو جلب كل جدول المستخدمين إذا لم يكن عمود role مستخدمًا بالشكل المقيد
$total_users        = getCount($conn, "SELECT COUNT(*) as total FROM users");
$total_companies    = getCount($conn, "SELECT COUNT(*) as total FROM companies");
$total_jobs         = getCount($conn, "SELECT COUNT(*) as total FROM jobs");
$total_applications = getCount($conn, "SELECT COUNT(*) as total FROM application");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Job Portal</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/users.css?v=<?php echo time(); ?>">
    
    <!-- Professional Dashboard Styling -->
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
            position: relative;
            overflow: hidden;
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

        /* Table Card Styling */
        .table-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(112, 144, 176, 0.08);
        }

        .table thead th {
            background-color: transparent;
            color: #8f9bba;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.8px;
            border-bottom: 1px solid #edf2f7;
            padding: 16px 20px;
            text-transform: uppercase;
        }

        .table tbody td {
            padding: 18px 20px;
            color: #2b3674;
            border-bottom: 1px solid #f8f9fc;
            font-size: 0.95rem;
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fc;
        }
        
        /* Custom Badge Look */
        .badge {
            font-weight: 600;
            letter-spacing: 0.3px;
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        
     <?php include 'sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #2b3674; font-size: 1.75rem;">Dashboard Overview</h3>
                    <p class="text-muted small mb-0" style="font-size: 0.9rem;">Welcome back! Here is what is happening today.</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Seekers</span>
                                <h3 class="fw-bold mt-1 mb-0" style="color: #2b3674;"><?= $total_users ?></h3>
                            </div>
                            <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-user-graduate"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Companies</span>
                                <h3 class="fw-bold mt-1 mb-0" style="color: #2b3674;"><?= $total_companies ?></h3>
                            </div>
                            <div class="icon-box bg-success bg-opacity-10 text-success"><i class="fa-solid fa-building"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Active Jobs</span>
                                <h3 class="fw-bold mt-1 mb-0" style="color: #2b3674;"><?= $total_jobs ?></h3>
                            </div>
                            <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-briefcase"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Applications</span>
                                <h3 class="fw-bold mt-1 mb-0" style="color: #2b3674;"><?= $total_applications ?></h3>
                            </div>
                            <div class="icon-box bg-info bg-opacity-10 text-info"><i class="fa-solid fa-paper-plane"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Jobs Table -->
            <div class="table-card p-4">
                <h5 class="fw-bold mb-3" style="color: #2b3674; font-size: 1.15rem;">Recent Job Posts</h5>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Job Title</th>
                                <th>Type</th>
                                <th>Salary Range</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($conn)) {
                                try {
                                    $recent_jobs = $conn->query("SELECT * FROM jobs ORDER BY id DESC LIMIT 5");
                                    if ($recent_jobs && $recent_jobs->num_rows > 0):
                                        while($job = $recent_jobs->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-muted fw-semibold">#<?= $job['id'] ?></td>
                                <td class="fw-bold" style="color: #2b3674;"><?= htmlspecialchars($job['title']) ?></td>
                                <td><span class="badge bg-light text-secondary border px-2 py-1"><?= htmlspecialchars($job['job_type'] ?? 'Full Time') ?></span></td>
                                <td class="text-success fw-bold"><?= htmlspecialchars($job['salary'] ?? 'N/A') ?></td>
                                <td><span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><?= ucfirst($job['status'] ?? 'Open') ?></span></td>
                            </tr>
                            <?php 
                                        endwhile; 
                                    else: 
                            ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">No job postings available at the moment.</td></tr>
                            <?php 
                                    endif;
                                } catch (Exception $e) {
                                    echo '<tr><td colspan="5" class="text-center text-muted py-4">No job postings available at the moment.</td></tr>';
                                }
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>