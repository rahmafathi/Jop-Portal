<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "includes/header.php";
include_once "includes/nav.php";
include_once "includes/functions.php";
include_once "includes/db.php";

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        redirect('admin/dashboard.php');
    } elseif ($_SESSION['role'] == 'company') {
        redirect('company/dashboard.php');
    } elseif ($_SESSION['role'] == 'job_seeker') {
        redirect('seeker/dashboard.php');
    } else {
        redirect('index.php');
    }
}

// Validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = sanitize($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        setMessage('danger', 'Please enter your email and password');
    } else {
        $user = login($conn, $email, $password);

        if (!empty($user)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // إذا كان Company هنجيب company_id
            if ($user['role'] == 'company') {
                $user_id = $user['id'];
                $sql = "SELECT * FROM companies WHERE user_id = '$user_id'";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    $company = mysqli_fetch_assoc($result);
                    $_SESSION['company_id'] = $company['id'];
                }
            }

            if ($user['role'] == 'admin') {
                redirect('admin/dashboard.php');
            } elseif ($user['role'] == 'company') {
                redirect('company/dashboard.php');
            } elseif ($user['role'] == 'job_seeker') {
                redirect('seeker/dashboard.php');
            } else {
                redirect('index.php');
            }
        } else {
            setMessage('danger', 'Invalid email or password');
        }
    }
}
?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="login-page">
    <div class="login-wrapper">

        <?php displayMessage(); ?>

        <div class="login-contaier">

            <div class="login-header">
                <div class="login-icon-box">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h3>Welcome Back</h3>
                <p>Sign in to your Job Portal account</p>
            </div>

            <div class="form-part">
                <form action="login.php" method="POST">

                    <div class="form-in-part">
                        <label for="email"><i class="bi bi-envelope me-1"></i> Email</label>
                        <div class="input-with-icon">
                            <input type="email" name="email" id="email" required>
                        </div>
                    </div>

                    <div class="form-in-part">
                        <label for="password" class="form-label"><i class="bi bi-key me-1"></i> Password</label>
                        <div class="input-with-icon">
                            <input type="password" name="password" id="password" required>
                        </div>
                    </div>

                    <button type="submit" class="login-btn">
                        <span>Sign In</span> <i class="bi bi-arrow-right-short"></i>
                    </button>

                </form>
            </div>

            <div class="login-card-footer">
                <p>
                    Don't have an account? 
                    <a href="register.php">Create New Account</a>
                </p>
            </div>

        </div>

    </div>
</div>

<?php
include_once "includes/footer.php";
?>