<?php
// admin/profile.php
include '../includes/db.php';

// تعريف الـ Base URL عشان الـ CSS يشتغل صح
$base_url = "http://localhost/Jop-Portal/";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile | JobPortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ربط ملف الـ CSS بالـ base_url بشكل صحيح -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/users.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        
        <?php include 'sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <h3 class="fw-bold mb-1">Admin Profile</h3>
            <p class="text-muted small mb-4">Manage your administrative details.</p>

            <div class="stat-card p-4 col-md-6">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Admin Name</label>
                        <input type="text" class="form-control" value="System Administrator" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control" value="admin@jobportal.com" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <input type="text" class="form-control" value="Super Admin" readonly>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>