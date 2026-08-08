<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../includes/db.php";

// احصل على اسم الملف الحالي ديناميكياً لتجنب مشاكل التوجيه 404
$current_page = basename($_SERVER['PHP_SELF']);

// حماية CSRF: توليد توكن إذا لم يوجد
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// معالجة العمليات (Delete, Accept, Reject) بطريقة آمنة
if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['token'])) {
    if ($_GET['token'] === $_SESSION['csrf_token']) {
        $id = intval($_GET['id']);
        $action = $_GET['action'];
        
        if ($conn) {
            if ($action == 'delete') {
                $stmt = mysqli_prepare($conn, "DELETE FROM application WHERE id = ?");
            } elseif ($action == 'accept') {
                $stmt = mysqli_prepare($conn, "UPDATE application SET status = 'accepted' WHERE id = ?");
            } elseif ($action == 'reject') {
                $stmt = mysqli_prepare($conn, "UPDATE application SET status = 'rejected' WHERE id = ?");
            }
            
            if (isset($stmt)) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
        header("Location: " . $current_page);
        exit();
    }
}

// استعلام واحد فعال لجلب الإحصائيات لتحسين الأداء
$stats = ['pending' => 0, 'accepted' => 0, 'rejected' => 0, 'total' => 0];
if ($conn) {
    $stats_query = mysqli_query($conn, "SELECT status, COUNT(*) as count FROM application GROUP BY status");
    while ($row = mysqli_fetch_assoc($stats_query)) {
        if (isset($stats[$row['status']])) {
            $stats[$row['status']] = $row['count'];
        }
        $stats['total'] += $row['count'];
    }
}

// Get Applications Query
$sql = "
SELECT 
    application.*,
    users.name,
    users.cv_file AS cv,
    jobs.title
FROM application
JOIN users ON application.seeker_id = users.id
JOIN jobs ON application.job_id = jobs.id
ORDER BY application.applied_at DESC
";

$result = $conn ? mysqli_query($conn, $sql) : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications Management | Admin Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/users.css?v=<?php echo time(); ?>">

    <!-- Professional Dashboard Styling -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
        }

        /* Stats Cards */
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

        /* Table Card */
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

        /* Status Badges */
        .badge {
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
            padding: 8px 16px;
            border-radius: 50rem !important;
            text-transform: uppercase;
        }

        .badge-pending {
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #d97706 !important;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-accepted {
            background-color: rgba(16, 185, 129, 0.15) !important;
            color: #059669 !important;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-rejected {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #dc2626 !important;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Action Column Control */
        .action-column {
            width: 230px;
            text-align: right;
        }

        /* Buttons Customization */
        .btn-action-accept {
            background: rgba(5, 150, 105, 0.1);
            color: #059669;
            border: none;
            border-radius: 10px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-action-accept:hover {
            background: #059669;
            color: #ffffff;
        }

        .btn-action-reject {
            background: rgba(217, 119, 6, 0.1);
            color: #d97706;
            border: none;
            border-radius: 10px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-action-reject:hover {
            background: #d97706;
            color: #ffffff;
        }

        .btn-action-delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: none;
            border-radius: 10px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-action-delete:hover {
            background: #ef4444;
            color: #ffffff;
        }

        .btn-cv {
            background: rgba(67, 24, 255, 0.1);
            color: #4318ff;
            border: none;
            border-radius: 10px;
            padding: 6px 14px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-cv:hover {
            background: #4318ff;
            color: #ffffff;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <div class="row g-0">

            <?php include 'sidebar.php'; ?>

            <div class="col-md-10 p-4">

                <!-- Header Title -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold mb-1" style="color: #2b3674; font-size: 1.75rem;">Applications Management</h3>
                        <p class="text-muted small mb-0" style="font-size: 0.9rem;">Review and manage all job applications submitted by candidates</p>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small fw-semibold text-uppercase"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Applications</span>
                                    <h3 class="fw-bold mt-1 mb-0" style="color: #2b3674;"><?= $stats['total']; ?></h3>
                                </div>
                                <div class="icon-box bg-primary bg-opacity-10 text-primary"><i
                                        class="fa-solid fa-file-lines"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small fw-semibold text-uppercase"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;">Pending</span>
                                    <h3 class="fw-bold mt-1 mb-0 text-warning"><?= $stats['pending']; ?></h3>
                                </div>
                                <div class="icon-box bg-warning bg-opacity-10 text-warning"><i
                                        class="fa-solid fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small fw-semibold text-uppercase"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;">Accepted</span>
                                    <h3 class="fw-bold mt-1 mb-0 text-success"><?= $stats['accepted']; ?></h3>
                                </div>
                                <div class="icon-box bg-success bg-opacity-10 text-success"><i
                                        class="fa-solid fa-check-circle"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small fw-semibold text-uppercase"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;">Rejected</span>
                                    <h3 class="fw-bold mt-1 mb-0 text-danger"><?= $stats['rejected']; ?></h3>
                                </div>
                                <div class="icon-box bg-danger bg-opacity-10 text-danger"><i
                                        class="fa-solid fa-times-circle"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications Table Card -->
                <div class="table-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold mb-0" style="color: #2b3674;"><i
                                class="fa-solid fa-users me-2 text-primary"></i>Applications List</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="80">#ID</th>
                                    <th>Applicant</th>
                                    <th>Job Title</th>
                                    <th>CV</th>
                                    <th>Status</th>
                                    <th>Applied At</th>
                                    <th class="action-column">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td class="text-muted fw-bold">#<?= $row['id']; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="category-icon"
                                                        style="width: 36px; height: 36px; background: rgba(67, 24, 255, 0.1); color: #4318ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                                        <i class="fa-solid fa-user"></i></div>
                                                    <span class="fw-bold"
                                                        style="color: #2b3674;"><?= htmlspecialchars($row['name']); ?></span>
                                                </div>
                                            </td>
                                            <td class="fw-semibold text-primary"><?= htmlspecialchars($row['title']); ?></td>
                                            <td>
                                                <?php 
                                                if (!empty($row['cv'])) {
                                                    $physical_path = "../uploads/cvs/" . $row['cv'];
                                                    
                                                    if (file_exists($physical_path)) {
                                                        $file_ext = strtolower(pathinfo($row['cv'], PATHINFO_EXTENSION));
                                                        
                                                        $icon = "fa-file";
                                                        $label = "View File";
                                                        
                                                        if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                                            $icon = "fa-image";
                                                            $label = "View Image";
                                                        } elseif ($file_ext == 'pdf') {
                                                            $icon = "fa-file-pdf";
                                                            $label = "View PDF";
                                                        } elseif (in_array($file_ext, ['doc', 'docx'])) {
                                                            $icon = "fa-file-word";
                                                            $label = "View Word";
                                                        }
                                                        ?>
                                                        <p class="mb-0">
                                                            <a href="<?= $physical_path; ?>" target="_blank" class="btn-cv">
                                                                <i class="fa-solid <?= $icon; ?> me-1"></i> <?= $label; ?>
                                                            </a>
                                                        </p>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <span class="text-danger small" title="الملف غير موجود في مجلد uploads/cvs">
                                                            <i class="fa-solid fa-triangle-exclamation"></i> File Missing
                                                        </span>
                                                        <?php
                                                    }
                                                } else { ?>
                                                    <span class="text-muted small">No File</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                $current_status = strtolower($row['status']);
                                                if ($current_status == "pending") {
                                                    echo "<span class='badge badge-pending'><i class='fa-solid fa-clock me-1'></i> Pending</span>";
                                                } elseif ($current_status == "accepted") {
                                                    echo "<span class='badge badge-accepted'><i class='fa-solid fa-check me-1'></i> Accepted</span>";
                                                } else {
                                                    echo "<span class='badge badge-rejected'><i class='fa-solid fa-xmark me-1'></i> Rejected</span>";
                                                }
                                                ?>
                                            </td>
                                            <td class="text-secondary fw-medium">
                                                <?= date("d M Y - h:i A", strtotime($row['applied_at'])); ?></td>
                                            <td class="action-column">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="<?= $current_page; ?>?action=accept&id=<?= $row['id']; ?>&token=<?= $_SESSION['csrf_token']; ?>"
                                                        class="btn-action-accept"
                                                        onclick="return confirm('Accept this application?')" title="Accept">
                                                        <i class="fa-solid fa-check"></i> Accept
                                                    </a>
                                                    <a href="<?= $current_page; ?>?action=reject&id=<?= $row['id']; ?>&token=<?= $_SESSION['csrf_token']; ?>"
                                                        class="btn-action-reject"
                                                        onclick="return confirm('Reject this application?')" title="Reject">
                                                        <i class="fa-solid fa-xmark"></i> Reject
                                                    </a>
                                                    <a href="<?= $current_page; ?>?action=delete&id=<?= $row['id']; ?>&token=<?= $_SESSION['csrf_token']; ?>"
                                                        class="btn-action-delete"
                                                        onclick="return confirm('Delete this application?')" title="Delete">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="fas fa-file-lines fa-3x mb-3 opacity-50 text-primary"></i>
                                            <h5 class="fw-bold text-dark">No Applications Found</h5>
                                            <p class="text-muted mb-0">Candidates' job applications will appear here once
                                                submitted.</p>
                                        </td>
                                    </tr>
                                <?php } ?>
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