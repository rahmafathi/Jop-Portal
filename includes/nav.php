<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#08152f;" dir="ltr">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold me-auto" href="/Jop-Portal/index.php">
            <i class="bi bi-briefcase-fill me-1"></i> Job Portal
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link" href="/Jop-Portal/index.php">
                        <i class="bi bi-house-door-fill me-1"></i>
                        Home
                    </a>
                </li>

                <!-- Jobs -->
                <li class="nav-item">
                    <a class="nav-link" href="/Jop-Portal/jobs.php">
                        <i class="bi bi-briefcase-fill me-1"></i>
                        Jobs
                    </a>
                </li>


                <?php if (isset($_SESSION['user_id'])): ?>

                    <!-- Profile -->
                    <li class="nav-item">
                        <?php

                        if (
                            isset($_SESSION['role']) &&
                            $_SESSION['role'] === 'company'
                        ) {

                            $profileLink = "/Jop-Portal/company/profile.php";

                        } elseif (
                            isset($_SESSION['role']) &&
                            $_SESSION['role'] === 'job_seeker'
                        ) {

                            $profileLink = "/Jop-Portal/seeker/profile.php";

                        } else {

                            $profileLink = "/Jop-Portal/index.php";

                        }

                        ?>

                        <a class="nav-link" href="<?= $profileLink; ?>">

                            <i class="bi bi-person-circle me-1"></i>

                            Profile

                        </a>
                    </li>


                    <!-- Logout -->
                    <li class="nav-item">
                        <a class="nav-link" href="/Jop-Portal/logout.php">

                            <i class="bi bi-box-arrow-right me-1"></i>

                            Logout

                        </a>
                    </li>


                <?php else: ?>


                    <!-- Login -->
                    <li class="nav-item">
                        <a class="nav-link" href="/Jop-Portal/login.php">

                            <i class="bi bi-box-arrow-in-right me-1"></i>

                            Login

                        </a>
                    </li>


                    <!-- Register -->
                    <li class="nav-item">
                        <a class="nav-link" href="/Jop-Portal/register.php">

                            <i class="bi bi-person-plus-fill me-1"></i>

                            Register

                        </a>
                    </li>


                <?php endif; ?>

            </ul>

        </div>

    </div>
</nav>