<?php
// admin/admin/users.php
include '../includes/db.php';

if (isset($_GET['delete'])) {
    $uid = intval($_GET['delete']);
    if ($conn) {
        $conn->query("DELETE FROM users WHERE id = $uid");
    }
    header("Location: users.php");
    exit();
}

$users = [];
if ($conn) {
    try {
        $res = $conn->query("SELECT * FROM users WHERE role = 'user' OR role IS NULL ORDER BY id DESC");
        if ($res) { $users = $res->fetch_all(MYSQLI_ASSOC); }
    } catch (Exception $e) { $users = []; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seekers | Admin Dashboard</title>
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
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        
        <?php include 'sidebar.php'; ?>
        
        <div class="col-md-10 p-4">
            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="color: #2b3674; font-size: 1.75rem;">Job Seekers</h3>
                <p class="text-muted small mb-0" style="font-size: 0.9rem;">List of registered candidates.</p>
            </div>

            <div class="table-card p-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): foreach ($users as $u): ?>
                            <tr>
                                <td class="text-muted fw-semibold">#<?= $u['id'] ?></td>
                                <td class="fw-bold" style="color: #2b3674;"><?= htmlspecialchars($u['name'] ?? $u['username'] ?? 'User') ?></td>
                                <td class="text-secondary"><?= htmlspecialchars($u['email'] ?? 'N/A') ?></td>
                                <td>
                                    <a href="users.php?delete=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-semibold" onclick="return confirm('Delete this user?');">
                                        <i class="fa-solid fa-trash-can me-1"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">No job seekers found.</td></tr>
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