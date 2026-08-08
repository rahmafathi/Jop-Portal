<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../includes/db.php";

// بيانات الأدمن
$admin_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$admin_id'");
$admin = mysqli_fetch_assoc($result);

//================ UPDATE PROFILE ================

if(isset($_POST['save_profile'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);

    mysqli_query($conn,"
        UPDATE users
        SET
        name='$name',
        email='$email'
        WHERE id='$admin_id'
    ");

    header("Location: settings.php");
    exit();
}

//================ CHANGE PASSWORD ================

if(isset($_POST['change_password'])){
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if(password_verify($old_password, $admin['password'])){
        if($new_password == $confirm_password){
            $new = password_hash($new_password, PASSWORD_DEFAULT);

            mysqli_query($conn,"
            UPDATE users
            SET password='$new'
            WHERE id='$admin_id'
            ");

            header("Location: settings.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" id="html-root" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Admin Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/users.css?v=<?php echo time();?>">

    <!-- Theme & Professional Styling -->
    <style>
        :root {
            --bg-color: #f4f7fe;
            --card-bg: #ffffff;
            --text-color: #2b3674;
            --text-muted: #8f9bba;
            --input-bg: #f8f9fc;
            --input-border: #e0e5f2;
            --card-shadow: 0px 10px 30px rgba(112, 144, 176, 0.08);
        }

        [data-bs-theme="dark"] {
            --bg-color: #0b1437;
            --card-bg: #111c44;
            --text-color: #ffffff;
            --text-muted: #a3aed0;
            --input-bg: #1b254b;
            --input-border: #2b3674;
            --card-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .settings-card {
            background: var(--card-bg);
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .settings-card .card-header {
            background: transparent;
            border-bottom: 1px solid var(--input-border);
            padding: 20px 24px;
        }

        .settings-card .card-body {
            padding: 24px;
        }

        .form-control {
            height: 48px;
            border-radius: 12px;
            border: 1px solid var(--input-border);
            padding-left: 16px;
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.95rem;
            background-color: var(--input-bg);
        }

        .form-control:focus {
            background-color: var(--card-bg);
            border-color: #4318ff;
            color: var(--text-color);
            box-shadow: 0 0 0 3px rgba(67, 24, 255, 0.1);
        }

        .form-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            font-weight: 700;
            margin-bottom: 8px;
        }

        /* Page Title Icon Wrapper */
        .page-title-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(67, 24, 255, 0.1);
            color: #4318ff;
            border-radius: 14px;
            font-size: 1.2rem;
        }

        /* Theme Option Cards */
        .theme-option {
            cursor: pointer;
            border: 2px solid var(--input-border);
            border-radius: 14px;
            padding: 16px;
            text-align: center;
            transition: all 0.2s ease;
            background: var(--input-bg);
        }

        .theme-option:hover {
            border-color: #4318ff;
        }

        .theme-option.active {
            border-color: #4318ff;
            background: rgba(67, 24, 255, 0.05);
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">

        <?php include 'sidebar.php'; ?>

        <div class="col-md-10 p-4">

            <!-- Header Section -->
            <div class="d-flex align-items-center mb-4">
                <div class="page-title-icon me-3">
                    <i class="fas fa-gear"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0" style="color: var(--text-color); font-size: 1.75rem;">System Settings</h2>
                    <p class="text-muted small mb-0" style="color: var(--text-muted) !important;">Manage your admin credentials, website preferences, and dark/light themes.</p>
                </div>
            </div>

            <!-- ================= Admin Information ================= -->
            <div class="card settings-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold" style="color: var(--text-color);">
                        <i class="fas fa-user text-primary me-2"></i>Admin Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($admin['name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($admin['email']); ?>" required>
                            </div>
                        </div>
                        <button class="btn btn-primary px-4 py-2 fw-semibold rounded-pill" name="save_profile">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- ================= Appearance / Theme Settings ================= -->
            <div class="card settings-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold" style="color: var(--text-color);">
                        <i class="fas fa-palette text-warning me-2"></i>Appearance & Theme (Dark / Light)
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3" style="color: var(--text-muted) !important;">Choose your preferred visual mode for the dashboard interface.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="theme-option" id="lightThemeBtn" onclick="setTheme('light')">
                                <i class="fas fa-sun fa-2x text-warning mb-2"></i>
                                <h6 class="fw-bold mb-1" style="color: var(--text-color);">Light Mode</h6>
                                <p class="text-muted small mb-0">Clean white and crisp interface</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="theme-option" id="darkThemeBtn" onclick="setTheme('dark')">
                                <i class="fas fa-moon fa-2x text-info mb-2"></i>
                                <h6 class="fw-bold mb-1" style="color: var(--text-color);">Dark Mode</h6>
                                <p class="text-muted small mb-0">Modern dark sleek environment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= Website Settings ================= -->
            <div class="card settings-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold" style="color: var(--text-color);">
                        <i class="fas fa-globe text-success me-2"></i>Website System Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Website Name</label>
                            <input type="text" class="form-control" value="Job Portal" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Website Version</label>
                            <input type="text" class="form-control" value="1.0" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">System Description</label>
                            <textarea class="form-control" rows="3" readonly>Online Job Portal Management System</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= Change Password ================= -->
            <div class="card settings-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold" style="color: var(--text-color);">
                        <i class="fas fa-lock text-danger me-2"></i>Change Security Password
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="old_password" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-danger px-4 py-2 fw-semibold rounded-pill">
                            <i class="fas fa-key me-2"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts for Theme Handling -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function setTheme(theme) {
        const htmlRoot = document.getElementById('html-root');
        htmlRoot.setAttribute('data-bs-theme', theme);
        localStorage.setItem('admin_theme', theme);

        // Active class toggle
        if(theme === 'dark') {
            document.getElementById('darkThemeBtn').classList.add('active');
            document.getElementById('lightThemeBtn').classList.remove('active');
        } else {
            document.getElementById('lightThemeBtn').classList.add('active');
            document.getElementById('darkThemeBtn').classList.remove('active');
        }
    }

    // Load saved theme on page startup
    window.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('admin_theme') || 'light';
        setTheme(savedTheme);
    });
</script>

</body>
</html>