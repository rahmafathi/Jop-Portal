<?php

include_once "../includes/header.php";
include_once "../includes/functions.php";
include_once "../includes/db.php";


?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon">
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="sidebar-brand-text mx-2">
            Job Portal
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Management -->
    <div class="sidebar-heading">
        Management
    </div>

    <li class="nav-item">
        <a class="nav-link" href="users.php">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="companies.php">
            <i class="fas fa-building"></i>
            <span>Companies</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="jobs.php">
            <i class="fas fa-briefcase"></i>
            <span>Jobs</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="categories.php">
            <i class="fas fa-list"></i>
            <span>Categories</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="applications.php">
            <i class="fas fa-file-alt"></i>
            <span>Applications</span>
        </a>
    </li>

   

    

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
 <!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <h4 class="text-dark font-weight-bold ml-3">
        Admin Dashboard
    </h4>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown no-arrow">

            <a class="nav-link dropdown-toggle" href="#" id="userDropdown"
                role="button" data-toggle="dropdown">

                <span class="mr-2 d-none d-lg-inline text-gray-600 small">

                    <?php
                    echo $_SESSION['name'] ?? 'Admin';
                    ?>

                </span>

                <img class="img-profile rounded-circle"
                    src="../img/undraw_profile.svg">

            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">

                <a class="dropdown-item" href="profile.php">

                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>

                    Profile

                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="../logout.php">

                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>

                    Logout

                </a>

            </div>

        </li>

    </ul>

</nav>
<!-- End Topbar -->

<?php

include_once "../includes/footer.php";

?>