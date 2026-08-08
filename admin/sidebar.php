<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<div class="col-md-2 sidebar d-flex flex-column admin-sidebar-custom">

    <!-- Logo -->
    <div class="brand py-4 px-3 border-bottom d-flex align-items-center">
        <div class="brand-icon-box me-2">
            <i class="fa-solid fa-briefcase text-info"></i>
        </div>
        <span class="fw-bold text-white fs-5">Job Portal</span>
    </div>

    <!-- Menu -->
    <div class="flex-grow-1 mt-3 px-2">

        <div class="sidebar-title px-3 mb-2 text-uppercase text-muted fw-bold" style="letter-spacing: 1px; font-size: 0.7rem;">
            MAIN
        </div>

        <a href="dashboard.php" class="nav-link-custom <?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <div class="sidebar-title px-3 mt-4 mb-2 text-uppercase text-muted fw-bold" style="letter-spacing: 1px; font-size: 0.7rem;">
            MANAGEMENT
        </div>

        <!--<a href="users.php" class="nav-link-custom <?= $currentPage == 'users.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i>
            <span>Job Seekers</span>
        </a> -->

        <!--<a href="companies.php" class="nav-link-custom <?= $currentPage == 'companies.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-building"></i>
            <span>Companies</span>
        </a>-->

        <a href="jobs.php" class="nav-link-custom <?= $currentPage == 'jobs.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-briefcase"></i>
            <span>Jobs</span>
        </a>

        <a href="categories.php" class="nav-link-custom <?= $currentPage == 'categories.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-layer-group"></i>
            <span>Categories</span>
        </a>

        <a href="application.php" class="nav-link-custom <?= $currentPage == 'application.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-file-lines"></i>
            <span>Applications</span>
        </a>
        
        <div class="sidebar-title px-3 mt-4 mb-2 text-uppercase text-muted fw-bold" style="letter-spacing: 1px; font-size: 0.7rem;">
            SETTINGS
        </div>

        <a href="profile.php" class="nav-link-custom <?= $currentPage == 'profile.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-user"></i>
            <span>Profile</span>
        </a>

        <a href="settings.php" class="nav-link-custom <?= $currentPage == 'settings.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
        </a>

    </div>

    <!-- Logout -->
    <div class="p-3 border-top border-secondary border-opacity-25">
        <a href="../logout.php" class="nav-link-custom text-danger logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>

</div>
<!-- End Sidebar -->