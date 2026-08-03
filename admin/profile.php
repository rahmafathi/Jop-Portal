<?php
// admin/admin/profile.php
include '../includes/db.php';
// admin/profile.php
include '../includes/db.php';

// تعريف الـ Base URL
$base_url = "http://localhost/Jop-Portal/";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Profile | Job Portal</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/users.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="container-fluid p-0">
        <div class="row g-0">

            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-10 p-4">

                <h2 class="fw-bold mb-1">
                    <i class="fas fa-user-shield text-primary"></i>
                    Admin Profile
                </h2>

                <p class="text-muted mb-4">
                    Manage your administrative details.
                </p>

                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <div class="card shadow-sm border-0"></div>

                        <div class="card-body">

                            <!-- Admin Image -->
                            <div class="text-center mb-4">

                                <img src="https://www.svgrepo.com/show/384674/account-avatar-profile-user-11.svg"
                                     alt="Admin"
     class="rounded-circle border border-3 border-primary shadow"
     style="
        width:170px;
        height:170px;
        object-fit:cover;
        object-position:center;"
        >

                                <h4 class="mt-3 mb-1">
                                    System Administrator
                                </h4>

                                <span class="badge bg-primary px-3 py-2">
                                    Super Admin
                                </span>

                            </div>

                            <form>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user text-primary"></i>
                                        Admin Name
                                    </label>

                                    <input type="text" class="form-control" value="System Administrator" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-envelope text-primary"></i>
                                        Email Address
                                    </label>

                                    <input type="email" class="form-control" value="admin@jobportal.com" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user-tag text-primary"></i>
                                        Role
                                    </label>

                                    <input type="text" class="form-control" value="Super Admin" readonly>
                                </div>

                            </form>

                        </div>

                    </div>

                </div>
                <!-- End Main Content -->

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>