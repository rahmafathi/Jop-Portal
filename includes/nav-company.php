<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>

<!-- تضمين Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Embedded Custom Styles for Navbar & Bottom LED Bar -->
<style>
/* ============================================================
   1. DARK GLOSSY NAVBAR COMPONENT STYLES
============================================================ */
.custom-navbar {
    background: rgba(5, 11, 20, 0.90) !important;
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    padding: 12px 0;
    transition: all 0.4s ease;
    border-bottom: none !important;
}

/* Brand Logo & Icon Fix */
.custom-navbar .navbar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 22px;
    font-weight: 800 !important;
    color: #ffffff !important;
    letter-spacing: -0.5px;
    transition: all 0.3s ease;
}

.custom-navbar .brand-icon {
    width: 38px;
    height: 38px;
    background: rgba(0, 210, 255, 0.08);
    border: 1px solid rgba(0, 210, 255, 0.2);
    color: #00d2ff !important;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: 0 0 10px rgba(0, 210, 255, 0.15);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.custom-navbar .navbar-brand:hover .brand-icon {
    transform: scale(1.08);
    background: rgba(0, 210, 255, 0.15);
    border-color: #00d2ff;
    box-shadow: 0 0 12px rgba(0, 210, 255, 0.4);
}

.custom-navbar .navbar-brand:hover {
    color: #00d2ff !important;
}

/* Toggler Button Styling */
.custom-navbar .navbar-toggler {
    background: rgba(0, 210, 255, 0.08);
    border: 1px solid rgba(0, 210, 255, 0.2);
    border-radius: 10px;
    padding: 8px 12px;
    outline: none;
    box-shadow: none;
}

.custom-navbar .navbar-toggler:focus {
    box-shadow: 0 0 10px rgba(0, 210, 255, 0.3);
}

.custom-navbar .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 210, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

/* Navigation Links & Icons Fix */
.custom-navbar .nav-link {
    color: #cbd5e1 !important;
    font-weight: 600;
    font-size: 15px;
    padding: 8px 16px !important;
    border-radius: 10px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    position: relative;
    background: transparent !important;
}

.custom-navbar .nav-link i {
    font-size: 16px;
    color: #00d2ff;
    transition: all 0.3s ease;
    display: inline-block;
    line-height: 1;
}

.custom-navbar .nav-link:hover {
    color: #ffffff !important;
    background: rgba(0, 210, 255, 0.08) !important;
}

.custom-navbar .nav-link:hover i {
    transform: translateY(-1px) scale(1.1);
    filter: drop-shadow(0 0 5px rgba(0, 210, 255, 0.6));
}

.custom-navbar .nav-link::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%) scaleX(0);
    width: 50%;
    height: 2px;
    background: linear-gradient(90deg, #00d2ff, #0d6efd);
    border-radius: 2px;
    transition: transform 0.3s ease;
}

.custom-navbar .nav-link:hover::after {
    transform: translateX(-50%) scaleX(1);
}

/* Action Buttons & Icons Fix */
.custom-navbar .btn-action-nav {
    font-weight: 700;
    font-size: 14px;
    padding: 8px 20px;
    border-radius: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.custom-navbar .btn-action-nav i {
    font-size: 16px;
    line-height: 1;
}

.custom-navbar .btn-action-nav:hover {
    transform: translateY(-2px);
}

/* Login Button */
.btn-nav-login {
    color: #00d2ff !important;
    border: 1.5px solid rgba(0, 210, 255, 0.3) !important;
    background: transparent !important;
}

.btn-nav-login:hover {
    background: rgba(0, 210, 255, 0.12) !important;
    border-color: #00d2ff !important;
    box-shadow: 0 0 12px rgba(0, 210, 255, 0.25);
}

/* Register Button */
.btn-nav-register {
    background: linear-gradient(135deg, #0d6efd, #00d2ff) !important;
    color: #ffffff !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(0, 210, 255, 0.25);
}

.btn-nav-register:hover {
    box-shadow: 0 6px 18px rgba(0, 210, 255, 0.4);
}

/* Logout Button */
.nav-btn-logout {
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

/* ============================================================
   2. SOFT NEON LED BAR EFFECT (BOTTOM OF NAV)
============================================================ */
.nav-neon-led-bar-bottom {
    width: 100% !important;
    height: 2.5px !important;
    background: linear-gradient(90deg, #00d2ff, #0d6efd, #00ffcc, #00d2ff) !important;
    background-size: 300% 100% !important;
    position: relative !important;
    z-index: 1035 !important;
    animation: navSoftNeonBlink 3.5s infinite alternate ease-in-out, navMoveGradient 6s linear infinite !important;
}

@keyframes navSoftNeonBlink {
    0% {
        opacity: 0.15;
        box-shadow: 0 0 2px rgba(0, 210, 255, 0.2);
    }
    50% {
        opacity: 0.85;
        box-shadow: 
            0 0 6px rgba(0, 210, 255, 0.6),
            0 0 14px rgba(0, 210, 255, 0.3);
    }
    100% {
        opacity: 0.15;
        box-shadow: 0 0 2px rgba(0, 210, 255, 0.2);
    }
}

@keyframes navMoveGradient {
    0% { background-position: 0% 50%; }
    100% { background-position: 100% 50%; }
}

/* Mobile & Tablet Responsiveness Fix */
@media (max-width: 991.98px) {
    .custom-navbar {
        padding: 10px 0;
    }

    .custom-navbar .navbar-collapse {
        background: rgba(15, 23, 42, 0.98);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        margin-top: 15px;
        border: 1px solid rgba(0, 210, 255, 0.15);
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .custom-navbar .nav-link {
        justify-content: flex-start;
        padding: 10px 14px !important;
        margin-bottom: 4px;
    }

    .custom-navbar .nav-link::after {
        display: none;
    }
    
    .custom-navbar .btn-action-nav {
        width: 100%;
        margin-top: 8px;
        margin-left: 0 !important;
    }

    .custom-navbar .navbar-nav {
        gap: 0 !important;
    }
}
</style>

<!-- Navbar Component -->
<div class="sticky-top">
    <nav class="navbar navbar-expand-lg custom-navbar" dir="ltr">
        <div class="container">

            <!-- Logo / Brand -->
            <a class="navbar-brand" href="/Jop-Portal/index.php">
                <div class="brand-icon">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <span class="brand-title">Job Portal</span>
            </a>

            <!-- Mobile Toggler -->
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav" 
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto align-items-lg-center gap-2">

                    <!-- Jobs -->
                    <li class="nav-item">
                        <a class="nav-link" href="/Jop-Portal/company/my_jobs.php">
                            <i class="bi bi-search"></i>
                            <span>Jobs</span>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>

                        <?php
                        if (isset($_SESSION['role']) && $_SESSION['role'] === 'company') {
                            $profileLink = "/Jop-Portal/company/profile.php";
                            $dashboardLink = "/Jop-Portal/company/dashboard.php";
                        } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'job_seeker') {
                            $profileLink = "/Jop-Portal/seeker/profile.php";
                            $dashboardLink = "/Jop-Portal/seeker/dashboard.php";
                        } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                            $profileLink = "/Jop-Portal/admin/profile.php";
                            $dashboardLink = "/Jop-Portal/admin/dashboard.php";
                        } else {
                            $profileLink = "/Jop-Portal/index.php";
                            $dashboardLink = "/Jop-Portal/index.php";
                        }
                        ?>

                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $dashboardLink; ?>">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <!-- Profile -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $profileLink; ?>">
                                <i class="bi bi-person-circle"></i>
                                <span>Profile</span>
                            </a>
                        </li>

                        <!-- Logout Button -->
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-danger btn-action-nav nav-btn-logout" href="/Jop-Portal/logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                        </li>

                    <?php else: ?>

                        <!-- Login -->
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-nav-login btn-action-nav" href="/Jop-Portal/login.php">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span>Login</span>
                            </a>
                        </li>

                        <!-- Register -->
                        <li class="nav-item">
                            <a class="btn btn-nav-register btn-action-nav" href="/Jop-Portal/register.php">
                                <i class="bi bi-person-plus-fill"></i>
                                <span>Register</span>
                            </a>
                        </li>

                    <?php endif; ?>

                </ul>

            </div>

        </div>
    </nav>

    <!-- LED Line Bar -->
    <div class="nav-neon-led-bar-bottom"></div>
</div>