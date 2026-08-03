<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<div class="col-md-2 sidebar d-flex flex-column">

    <!-- Logo -->
    <div class="brand py-4 px-3 border-bottom">
        <i class="fa-solid fa-briefcase text-primary me-2"></i>
        <span class="fw-bold">Job Portal</span>
    </div>

    <!-- Menu -->
    <div class="flex-grow-1 mt-3">

        <div class="sidebar-title px-3 mb-2">
            MAIN
        </div>

        <a href="dashboard.php" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-line"></i>
            Dashboard
        </a>

        <div class="sidebar-title px-3 mt-4 mb-2">
            MANAGEMENT
        </div>

        <a href="users.php" class="<?= $currentPage == 'users.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i>
            Job Seekers
        </a>

        <a href="companies.php" class="<?= $currentPage == 'companies.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-building"></i>
            Companies
        </a>

        <a href="jobs.php" class="<?= $currentPage == 'jobs.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-briefcase"></i>
            Jobs
        </a>

        <a href="categories.php" class="<?= $currentPage == 'categories.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-layer-group"></i>
            Categories
        </a>

        

        
        <div class="sidebar-title px-3 mt-4 mb-2">
            SETTINGS
        </div>

        <a href="profile.php" class="<?= $currentPage == 'profile.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-user"></i>
            Profile
        </a>

        <a href="settings.php" class="<?= $currentPage == 'settings.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-gear"></i>
            Settings
        </a>

    </div>

    <div class="border-top p-3">
        <a href="../logout.php" class="text-danger">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>
    </div>

</div>
<!-- End Sidebar -->