<?php

include_once "includes/header.php";
include_once "includes/nav.php";
include_once "includes/functions.php";
include_once "includes/db.php";
$message = "";

if (isset($_POST['register'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // التحقق من تطابق الباسورد
    if ($password != $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // التحقق من وجود الإيميل
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

        if (mysqli_num_rows($check) > 0) {
            $message = "Email already exists!";
        } else {
            // تشفير الباسورد
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // إدخال البيانات
            $insert = mysqli_query($conn, "INSERT INTO users(name,email,password,phone,role)
            VALUES('$name','$email','$hashedPassword','$phone','$role')");

            if ($insert) {
                // الحصول على رقم المستخدم الجديد
                $user_id = mysqli_insert_id($conn);

                // إذا كان المستخدم شركة، أضف له سجل في جدول companies
                if ($role == "company") {
                    $insertCompany = mysqli_query($conn, "
                        INSERT INTO companies
                        (user_id, company_name, description, website, location, logo)
                        VALUES
                        ('$user_id', '$name', '', '', '', '')
                    ");
                }

                // رسالة نجاح
                setMessage("success", "Registration Successful. Please Login.");

                // تحويل إلى صفحة تسجيل الدخول
                redirect("login.php");

            } else {
                setMessage("danger", "Registration Failed.");
                $message = "Registration Failed.";
            }
        }
    }
}

?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="login-page">
    <div class="login-wrapper">

        <!-- الكارد الرئيسية الواحدة لكل المحتوى -->
        <div class="login-contaier">

            <div class="login-header">
                <div class="login-icon-box">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <h3>Create Account</h3>
                <p>Sign up to get started with Job Portal</p>
            </div>

            <?php if (!empty($message)): ?>
                <div style="color: #f87171; background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.2); padding: 10px; border-radius: 10px; margin-bottom: 20px; width: 100%; font-size: 14px; text-align: center;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- تم إضافة وسم الفورم هنا ليحتوي كل حقول الإدخال والزر بشكل سليم -->
            <form action="register.php" method="POST" class="form-part">

                <div class="form-in-part">
                    <label for="name"><i class="bi bi-person me-1"></i> Full Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter your full name" required>
                </div>

                <div class="form-in-part">
                    <label for="email"><i class="bi bi-envelope me-1"></i> Email</label>
                    <input type="email" name="email" id="email" placeholder="admin@gmail.com" required>
                </div>

                <div class="form-in-part">
                    <label for="phone"><i class="bi bi-telephone me-1"></i> Phone</label>
                    <input type="text" name="phone" id="phone" placeholder="Enter your phone number" required>
                </div>

                <div class="form-in-part">
                    <label for="password"><i class="bi bi-key me-1"></i> Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••" required>
                </div>

                <div class="form-in-part">
                    <label for="confirm_password"><i class="bi bi-key-fill me-1"></i> Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••" required>
                </div>

                <div class="form-in-part">
                    <label for="role"><i class="bi bi-person-badge me-1"></i> Role</label>
                    <select name="role" id="role" required>
                        <option value="">Select Role</option>
                        <option value="company">Company</option>
                        <option value="job_seeker">Job Seeker</option>
                    </select>
                </div>

                <button type="submit" name="register" class="login-btn">
                    <span>Register</span> <i class="bi bi-arrow-right"></i>
                </button>

            </form>

            <div class="login-card-footer">
                Already have an account? <a href="login.php">Login here</a>
            </div>

        </div>

    </div>
</div>

<?php
include_once "includes/footer.php";
?>