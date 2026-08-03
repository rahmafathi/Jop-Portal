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

    $old_password=$_POST['old_password'];

    $new_password=$_POST['new_password'];

    $confirm_password=$_POST['confirm_password'];

    if(password_verify($old_password,$admin['password'])){

        if($new_password==$confirm_password){

            $new=password_hash($new_password,PASSWORD_DEFAULT);

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

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Settings</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet"
href="../assets/css/users.css?v=<?php echo time();?>">

</head>

<body>

<div class="container-fluid p-0">

<div class="row g-0">

<?php include 'sidebar.php'; ?>

<div class="col-md-10 p-4">

<h2 class="fw-bold mb-4">

<i class="fas fa-gear text-primary"></i>

Settings

</h2>
<!-- ================= Admin Information ================= -->

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            <i class="fas fa-user text-primary"></i>

            Admin Information

        </h5>

    </div>

    <div class="card-body">

        <form method="POST">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Full Name

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?= htmlspecialchars($admin['name']); ?>"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Email

                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($admin['email']); ?>"
                        required>

                </div>

            </div>

            <button
                class="btn btn-primary"
                name="save_profile">

                <i class="fas fa-save"></i>

                Save Changes

            </button>

        </form>

    </div>

</div>



<!-- ================= Website Settings ================= -->

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            <i class="fas fa-globe text-success"></i>

            Website Settings

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Website Name

                </label>

                <input
                    type="text"
                    class="form-control"
                    value="Job Portal"
                    readonly>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Website Version

                </label>

                <input
                    type="text"
                    class="form-control"
                    value="1.0"
                    readonly>

            </div>

            <div class="col-md-12">

                <label class="form-label">

                    Description

                </label>

                <textarea
                    class="form-control"
                    rows="3"
                    readonly>Online Job Portal Management System</textarea>

            </div>

        </div>

    </div>

</div>
<!-- ================= Change Password ================= -->

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            <i class="fas fa-lock text-danger"></i>

            Change Password

        </h5>

    </div>

    <div class="card-body">

        <form method="POST">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="form-label">

                        Current Password

                    </label>

                    <input
                        type="password"
                        name="old_password"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label">

                        New Password

                    </label>

                    <input
                        type="password"
                        name="new_password"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label">

                        Confirm Password

                    </label>

                    <input
                        type="password"
                        name="confirm_password"
                        class="form-control"
                        required>

                </div>

            </div>

            <button
                type="submit"
                name="change_password"
                class="btn btn-danger">

                <i class="fas fa-key"></i>

                Update Password

            </button>

        </form>

    </div>

</div>

</div>
<!-- End Main Content -->

</div>
<!-- End Row -->

</div>
<!-- End Container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>