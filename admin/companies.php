<?php
// admin/admin/companies.php
include '../includes/db.php';

if (isset($_GET['delete'])) {
    $cid = intval($_GET['delete']);
    if ($conn) {
        $conn->query("DELETE FROM companies WHERE id = $cid");
    }
    header("Location: companies.php");
    exit();
}

$companies = [];
if ($conn) {
    try {
        $res = $conn->query("SELECT * FROM companies ORDER BY id DESC");
        if ($res) { $companies = $res->fetch_all(MYSQLI_ASSOC); }
    } catch (Exception $e) { $companies = []; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Companies | Admin Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/users.css?v=<?php echo time(); ?>">

    <!-- Professional Styling matching Dashboard -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
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

        .company-icon {
            width: 36px;
            height: 36px;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        
        <?php include 'sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="color: #2b3674; font-size: 1.75rem;">Registered Companies</h3>
                <p class="text-muted small mb-0" style="font-size: 0.9rem;">Manage registered employer accounts.</p>
            </div>

            <div class="table-card p-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Company Name</th>
                                <th>Email</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($companies)): foreach ($companies as $comp): ?>
                            <tr>
                                <td class="text-muted fw-semibold">#<?= $comp['id'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="company-icon"><i class="fa-solid fa-building"></i></div>
                                        <span class="fw-bold" style="color: #2b3674;"><?= htmlspecialchars($comp['company_name'] ?? 'N/A') ?></span>
                                    </div>
                                </td>
                                <td class="text-secondary"><?= htmlspecialchars($comp['email'] ?? 'N/A') ?></td>
                                <td class="text-muted"><?= htmlspecialchars($comp['location'] ?? 'Not specified') ?></td>
                                <td>
                                    <a href="companies.php?delete=<?= $comp['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-semibold" onclick="return confirm('Delete this company?');">
                                        <i class="fa-solid fa-trash-can me-1"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">No companies found.</td></tr>
                            <?php endif; ?>
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