<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "includes/db.php";
include_once "includes/functions.php";
include_once "includes/header.php";
include_once "includes/nav.php";


// =====================================
// Check Job ID
// =====================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    die("Job ID not found");

}

$job_id = (int) $_GET['id'];


// =====================================
// Get Job Details
// =====================================

$sql = "SELECT 
            jobs.id,
            jobs.title,
            jobs.description,
            jobs.salary,
            jobs.location,
            jobs.job_type,
            jobs.status,
            jobs.category_id,
            jobs.company_id,

            companies.company_name,

            categories.category_name

        FROM jobs

        LEFT JOIN companies
        ON jobs.company_id = companies.id

        LEFT JOIN categories
        ON jobs.category_id = categories.id

        WHERE jobs.id = $job_id

        LIMIT 1";


$result = mysqli_query($conn, $sql);


// =====================================
// Check Query
// =====================================

if (!$result) {

    die("Database Error: " . mysqli_error($conn));

}


// =====================================
// Check Job Exists
// =====================================

if (mysqli_num_rows($result) == 0) {

    die("Job not found");

}


$job = mysqli_fetch_assoc($result);

?>



<div class="container py-5">

    <!-- ============================= -->
    <!-- Back Button -->
    <!-- ============================= -->

    <div class="mb-4">

        <a href="jobs.php" class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>

            Back to Jobs

        </a>

    </div>



    <div class="row g-4">


        <!-- ============================= -->
        <!-- Job Details -->
        <!-- ============================= -->

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-md-5">


                    <!-- Job Title -->

                    <h2 class="fw-bold mb-2">

                        <?= htmlspecialchars($job['title']); ?>

                    </h2>



                    <!-- Company -->

                    <p class="text-muted mb-4">

                        <i class="bi bi-building"></i>

                        <?= !empty($job['company_name'])
                            ? htmlspecialchars($job['company_name'])
                            : 'Company'; ?>

                    </p>


                    <hr>



                    <!-- ============================= -->
                    <!-- Job Information -->
                    <!-- ============================= -->

                    <div class="row g-3 my-4">


                        <!-- Location -->

                        <div class="col-md-6">

                            <div class="p-3 bg-light rounded-3">

                                <i class="bi bi-geo-alt text-primary"></i>

                                <strong>Location</strong>

                                <div class="mt-1 text-muted">

                                    <?= htmlspecialchars($job['location']); ?>

                                </div>

                            </div>

                        </div>



                        <!-- Job Type -->

                        <div class="col-md-6">

                            <div class="p-3 bg-light rounded-3">

                                <i class="bi bi-briefcase text-primary"></i>

                                <strong>Job Type</strong>

                                <div class="mt-1 text-muted">

                                    <?= htmlspecialchars($job['job_type']); ?>

                                </div>

                            </div>

                        </div>



                        <!-- Category -->

                        <div class="col-md-6">

                            <div class="p-3 bg-light rounded-3">

                                <i class="bi bi-grid text-primary"></i>

                                <strong>Category</strong>

                                <div class="mt-1 text-muted">

                                    <?php

                                    if (!empty($job['category_name'])) {

                                        echo htmlspecialchars(
                                            $job['category_name']
                                        );

                                    } else {

                                        echo "Not specified";

                                    }

                                    ?>

                                </div>

                            </div>

                        </div>



                        <!-- Salary -->

                        <div class="col-md-6">

                            <div class="p-3 bg-light rounded-3">

                                <i class="bi bi-cash-coin text-primary"></i>

                                <strong>Salary</strong>

                                <div class="mt-1 text-muted">

                                    <?php

                                    if (
                                        isset($job['salary']) &&
                                        $job['salary'] !== ''
                                    ) {

                                        echo htmlspecialchars(
                                            $job['salary']
                                        ) . " EGP";

                                    } else {

                                        echo "Not specified";

                                    }

                                    ?>

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- ============================= -->
                    <!-- Description -->
                    <!-- ============================= -->

                    <h4 class="fw-bold mb-3">

                        Job Description

                    </h4>


                    <div class="text-muted">

                        <?= nl2br(
                            htmlspecialchars($job['description'])
                        ); ?>

                    </div>


                </div>

            </div>

        </div>



        <!-- ============================= -->
        <!-- Side Card -->
        <!-- ============================= -->

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">


                    <!-- Status -->

                    <div class="mb-4">

                        <small class="text-muted">

                            Job Status

                        </small>


                        <div class="mt-2">

                            <?php

                            if (
                                strtolower(
                                    trim($job['status'])
                                ) === 'open'
                            ) {

                            ?>

                                <span class="badge bg-success fs-6">

                                    Open

                                </span>

                            <?php

                            } else {

                            ?>

                                <span class="badge bg-danger fs-6">

                                    Closed

                                </span>

                            <?php

                            }

                            ?>

                        </div>

                    </div>


                    <hr>


                    <!-- Company -->

                    <div class="mb-4">

                        <small class="text-muted">

                            Company

                        </small>

                        <h5 class="fw-bold mt-2">

                            <?= !empty($job['company_name'])
                                ? htmlspecialchars($job['company_name'])
                                : 'Company'; ?>

                        </h5>

                    </div>


                    <!-- Apply Button -->

                    <?php

                    if (
                        strtolower(
                            trim($job['status'])
                        ) === 'open'
                    ) {

                    ?>

                        <a
                            href="apply.php?job_id=<?= $job['id']; ?>"
                            class="btn btn-primary w-100">

                            <i class="bi bi-send"></i>

                            Apply Now

                        </a>

                    <?php

                    } else {

                    ?>

                        <button
                            class="btn btn-secondary w-100"
                            disabled>

                            Job Closed

                        </button>

                    <?php

                    }

                    ?>


                </div>

            </div>

        </div>

    </div>

</div>



<?php

include_once "includes/footer.php";

?>