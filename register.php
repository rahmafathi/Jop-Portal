<?php

include_once "includes/header.php";
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

            // if ($insert) {

            //     $message = "Registration Successful";

            // } else {

            //     $message = "Registration Failed";

            // }


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

}

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

</head>

<body>

<h2>Register</h2>

<?php
if($message!=""){
    echo "<p>$message</p>";
}
?>

<form method="POST">

    <label>Full Name</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Phone</label><br>
    <input type="text" name="phone" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>Confirm Password</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <label>Role</label><br>

    <select name="role" required>

        <option value="">Select Role</option>

        <option value="company">Company</option>

        <option value="job_seeker">Job Seeker</option>

    </select>

    <br><br>

    <button type="submit" name="register">Register</button>

</form>

</body>

</html>



<?php

include_once "includes/footer.php";

?>