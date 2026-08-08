<?php
// admin/admin/profile.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

// تعريف الـ Base URL
$base_url = "http://localhost/Jop-Portal/";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | Job Portal</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/users.css?v=<?php echo time(); ?>">

    <!-- Centered & Pro Styling -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
        }

        .profile-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(112, 144, 176, 0.08);
            overflow: hidden;
            position: relative;
        }

        .profile-header-bg {
            height: 100px;
            background: linear-gradient(135deg, #2b3674 0%, #1b254b 100%);
            width: 100%;
        }

        .profile-avatar-container {
            margin-top: -50px;
            position: relative;
            display: inline-block;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            object-fit: cover;
            object-position: center;
            border: 4px solid #ffffff;
            background-color: #ffffff;
            box-shadow: 0px 6px 15px rgba(43, 54, 116, 0.15);
        }

        .form-control {
            height: 46px;
            border-radius: 12px;
            border: 1px solid #e0e5f2;
            padding-left: 16px;
            color: #2b3674;
            font-weight: 500;
            font-size: 0.9rem;
            background-color: #f8f9fc;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #2b3674;
            box-shadow: 0 0 0 3px rgba(43, 54, 116, 0.1);
        }

        .form-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8f9bba;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .badge-role {
            background: rgba(43, 54, 116, 0.1);
            color: #2b3674;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 5px 14px;
            border-radius: 50rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Perfect Icon Alignment Wrapper */
        .page-title-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            background: rgba(43, 54, 116, 0.1);
            color: #2b3674;
            border-radius: 12px;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <div class="row g-0">

            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-10 p-4 d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">

                <div class="w-100" style="max-width: 650px;">
                    
                    <!-- Page Header -->
                    <div class="mb-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center gap-2 mb-1">
                            <div class="page-title-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h2 class="fw-bold mb-0" style="color: #2b3674; font-size: 1.75rem;">
                                Admin Profile
                            </h2>
                        </div>
                        <p class="text-muted small mb-0" style="font-size: 0.9rem;">
                            Manage your administrative details and system credentials cleanly.
                        </p>
                    </div>

                    <!-- Profile Card -->
                    <div class="profile-card">
                        
                        <!-- Top Dark Blue Banner -->
                        <div class="profile-header-bg"></div>

                        <div class="card-body px-4 pb-4 pt-0 text-center">

                            <!-- Admin Image & Avatar Wrapper -->
                            <div class="profile-avatar-container mb-2">
                                <img src="https://www.svgrepo.com/show/384674/account-avatar-profile-user-11.svg"
                                     alt="Admin"
                                     class="rounded-circle profile-avatar">
                            </div>

                            <h4 class="fw-bold mb-1" style="color: #2b3674;">
                                System Administrator
                            </h4>

                            <div class="mb-3">
                                <span class="badge badge-role">
                                    <i class="fa-solid fa-shield-halved me-1"></i> Super Admin
                                </span>
                            </div>

                            <form class="text-start mt-4">

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user text-primary me-1"></i> Admin Name
                                    </label>
                                    <input type="text" class="form-control" value="System Administrator" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-envelope text-primary me-1"></i> Email Address
                                    </label>
                                    <input type="email" class="form-control" value="admin@jobportal.com" readonly>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label">
                                        <i class="fas fa-user-tag text-primary me-1"></i> System Role
                                    </label>
                                    <input type="text" class="form-control" value="Super Administrator" readonly>
                                </div>

                            </form>

                        </div>

                    </div>

                </div>
                <!-- End Main Content Center Wrapper -->

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>