<?php

require_once '../includes/functions.php';
require_once '../includes/db.php';


// =====================================================
// Check Login
// =====================================================

checkLogin();


// =====================================================
// Check Job Seeker
// =====================================================

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'job_seeker') {
    redirect('../login.php');
}

$userId = $_SESSION['user_id'];


// =====================================================
// Get User Data
// =====================================================

$userQuery = mysqli_query(
    $conn,
    "SELECT id, name, email, phone
     FROM users
     WHERE id = $userId"
);

$user = mysqli_fetch_assoc($userQuery);

if (!$user) {
    session_destroy();
    redirect('../login.php');
}


// =====================================================
// Total Applications
// =====================================================

$applicationsQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM application
     WHERE seeker_id = $userId"
);

$applicationsData = mysqli_fetch_assoc($applicationsQuery);

$totalApplications = $applicationsData['total'];


// =====================================================
// Saved Jobs
// =====================================================

$savedJobsQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM saved_jobs
     WHERE seeker_id = $userId"
);

$savedJobsData = mysqli_fetch_assoc($savedJobsQuery);

$savedJobs = $savedJobsData['total'];


// =====================================================
// Profile Completion
// =====================================================

$profileFields = [
    $user['name'],
    $user['email'],
    $user['phone']
];

$completedFields = 0;

foreach ($profileFields as $field) {

    if (!empty($field)) {
        $completedFields++;
    }

}

$profileCompletion = round(
    ($completedFields / count($profileFields)) * 100
);


// =====================================================
// Recent Applications
// =====================================================

$recentApplicationsQuery = mysqli_query(
    $conn,
    "SELECT
        j.id,
        j.title,
        c.company_name,
        a.status,
        a.applied_at

     FROM application a

     INNER JOIN jobs j
        ON a.job_id = j.id

     INNER JOIN companies c
        ON j.company_id = c.id

     WHERE a.seeker_id = $userId

     ORDER BY a.applied_at DESC

     LIMIT 5"
);

$recentApplications = [];

if ($recentApplicationsQuery) {

    while ($row = mysqli_fetch_assoc($recentApplicationsQuery)) {
        $recentApplications[] = $row;
    }

}


// =====================================================
// Latest Jobs
// =====================================================

$latestJobsQuery = mysqli_query(
    $conn,
    "SELECT
        j.id,
        j.title,
        j.description,
        j.salary,
        j.location,
        j.job_type,
        j.experience,
        j.deadline,

        c.company_name,

        cat.category_name

     FROM jobs j

     INNER JOIN companies c
        ON j.company_id = c.id

     INNER JOIN categories cat
        ON j.category_id = cat.id

     WHERE j.status = 'open'

     ORDER BY j.created_at DESC

     LIMIT 5"
);

$latestJobs = [];

if ($latestJobsQuery) {

    while ($row = mysqli_fetch_assoc($latestJobsQuery)) {
        $latestJobs[] = $row;
    }

}


// =====================================================
// Include Header
// =====================================================

require_once '../includes/header.php';


// =====================================================
// Include Navbar
// =====================================================

require_once '../includes/nav.php';

?>

<!-- =====================================================
     Dashboard
===================================================== -->

<div class="container py-5">

    <!-- Welcome -->

    <div class="mb-4">

        <h2 class="fw-bold">

            Welcome,
            <?= sanitize($user['name']); ?>
            👋

        </h2>

        <p class="text-muted">

            Here is your job seeker dashboard.

        </p>

    </div>


    <!-- Messages -->

    <?php displayMessage(); ?>


    <!-- =================================================
         Statistics Cards
    ================================================== -->

    <div class="row g-4 mb-5">


        <!-- Total Applications -->

        <div class="col-md-4">

            <div class="card shadow-sm border-0 rounded-4 h-100">

                <div class="card-body d-flex align-items-center">

                    <div class="bg-primary text-white rounded-3
                                d-flex align-items-center justify-content-center
                                me-3"
                         style="width:50px;height:50px;">

                        <i class="bi bi-send-fill fs-4"></i>

                    </div>

                    <div>

                        <h6 class="text-muted mb-1">
                            Total Applications
                        </h6>

                        <h3 class="fw-bold mb-0">
                            <?= $totalApplications; ?>
                        </h3>

                    </div>

                </div>

            </div>

        </div>


        <!-- Saved Jobs -->

        <div class="col-md-4">

            <div class="card shadow-sm border-0 rounded-4 h-100">

                <div class="card-body d-flex align-items-center">

                    <div class="bg-warning text-dark rounded-3
                                d-flex align-items-center justify-content-center
                                me-3"
                         style="width:50px;height:50px;">

                        <i class="bi bi-bookmark-fill fs-4"></i>

                    </div>

                    <div>

                        <h6 class="text-muted mb-1">
                            Saved Jobs
                        </h6>

                        <h3 class="fw-bold mb-0">
                            <?= $savedJobs; ?>
                        </h3>

                    </div>

                </div>

            </div>

        </div>


        <!-- Profile Completion -->

        <div class="col-md-4">

            <div class="card shadow-sm border-0 rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center mb-3">

                        <div class="bg-success text-white rounded-3
                                    d-flex align-items-center justify-content-center
                                    me-3"
                             style="width:50px;height:50px;">

                            <i class="bi bi-person-check-fill fs-4"></i>

                        </div>

                        <div>

                            <h6 class="text-muted mb-1">
                                Profile Completion
                            </h6>

                            <h3 class="fw-bold mb-0">
                                <?= $profileCompletion; ?>%
                            </h3>

                        </div>

                    </div>

                    <div class="progress" style="height:8px;">

                        <div
                            class="progress-bar bg-success"
                            role="progressbar"
                            style="width: <?= $profileCompletion; ?>%;"
                            aria-valuenow="<?= $profileCompletion; ?>"
                            aria-valuemin="0"
                            aria-valuemax="100">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         Buttons
    ================================================== -->

    <div class="d-flex gap-2 mb-5">

        <a
            href="../jobs.php"
            class="btn btn-primary">

            <i class="bi bi-search"></i>

            Browse Jobs

        </a>


        <a
            href="profile.php"
            class="btn btn-outline-primary">

            <i class="bi bi-person-gear"></i>

            Edit Profile

        </a>

    </div>


    <!-- =================================================
         Recent Applications
    ================================================== -->

    <div class="card shadow-sm border-0 rounded-4 mb-5">

        <div class="card-body">

            <h4 class="fw-bold mb-4">

                <i class="bi bi-send"></i>

                Recent Applications

            </h4>


            <?php if (empty($recentApplications)): ?>

                <div class="text-center py-4 text-muted">

                    <i class="bi bi-inbox fs-1"></i>

                    <p class="mt-2 mb-0">

                        You haven't applied for any jobs yet.

                    </p>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>Job</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Applied At</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($recentApplications as $application): ?>

                                <tr>

                                    <td>

                                        <strong>
                                            <?= sanitize(
                                                $application['title']
                                            ); ?>
                                        </strong>

                                    </td>


                                    <td>

                                        <?= sanitize(
                                            $application['company_name']
                                        ); ?>

                                    </td>


                                    <td>

                                        <?php

                                        $status =
                                            $application['status'];

                                        if ($status === 'accepted') {

                                            $badgeClass = 'bg-success';

                                        } elseif ($status === 'rejected') {

                                            $badgeClass = 'bg-danger';

                                        } else {

                                            $badgeClass = 'bg-warning text-dark';

                                        }

                                        ?>

                                        <span class="badge <?= $badgeClass; ?>">

                                            <?= sanitize(
                                                ucfirst($status)
                                            ); ?>

                                        </span>

                                    </td>


                                    <td>

                                        <?= date(
                                            'd M Y',
                                            strtotime(
                                                $application['applied_at']
                                            )
                                        ); ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>


    <!-- =================================================
         Latest Jobs
    ================================================== -->

    <div class="mb-4">

        <h4 class="fw-bold">

            <i class="bi bi-briefcase"></i>

            Latest Jobs For You

        </h4>

        <p class="text-muted">

            Latest available job opportunities.

        </p>

    </div>


    <div class="row g-4">

        <?php if (empty($latestJobs)): ?>

            <div class="col-12">

                <div class="alert alert-info">

                    No available jobs at the moment.

                </div>

            </div>

        <?php else: ?>


            <?php foreach ($latestJobs as $job): ?>

                <div class="col-md-6 col-lg-4">

                    <div class="card shadow-sm border-0 rounded-4 h-100">

                        <div class="card-body">


                            <!-- Title -->

                            <div class="d-flex justify-content-between
                                        align-items-start mb-3">

                                <div>

                                    <h5 class="fw-bold mb-1">

                                        <?= sanitize(
                                            $job['title']
                                        ); ?>

                                    </h5>

                                    <small class="text-muted">

                                        <i class="bi bi-building"></i>

                                        <?= sanitize(
                                            $job['company_name']
                                        ); ?>

                                    </small>

                                </div>


                                <span class="badge bg-light text-dark">

                                    <?= sanitize(
                                        $job['category_name']
                                    ); ?>

                                </span>

                            </div>


                            <!-- Description -->

                            <p class="text-muted small">

                                <?= sanitize(
                                    $job['description']
                                ); ?>

                            </p>


                            <!-- Location -->

                            <div class="small mb-2">

                                <i class="bi bi-geo-alt"></i>

                                <?= sanitize(
                                    $job['location']
                                ); ?>

                            </div>


                            <!-- Job Type -->

                            <div class="small mb-2">

                                <i class="bi bi-clock"></i>

                                <?= sanitize(
                                    $job['job_type']
                                ); ?>

                            </div>


                            <!-- Salary -->

                            <?php if (!empty($job['salary'])): ?>

                                <div class="small mb-2">

                                    <i class="bi bi-cash"></i>

                                    <?= number_format(
                                        $job['salary'],
                                        2
                                    ); ?>

                                </div>

                            <?php endif; ?>


                            <!-- Experience -->

                            <?php if (!empty($job['experience'])): ?>

                                <div class="small mb-2">

                                    <i class="bi bi-person-workspace"></i>

                                    <?= sanitize(
                                        $job['experience']
                                    ); ?>

                                </div>

                            <?php endif; ?>


                            <!-- View Job -->

                            <a
                                href="../job_details.php?id=<?= $job['id']; ?>"
                                class="btn btn-primary w-100 mt-3">

                                View Job

                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>


        <?php endif; ?>

    </div>

</div>


<?php

// =====================================================
// Include Footer
// =====================================================

require_once '../includes/footer.php';

?>