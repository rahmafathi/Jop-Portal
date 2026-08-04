<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "includes/db.php";
include_once "includes/functions.php";
include_once "includes/header.php";
include_once "includes/nav.php";


// =====================================================
// Search
// =====================================================

$where = "WHERE 1";


// Search by Job Title
if (isset($_GET['title']) && $_GET['title'] != "") {

    $title = mysqli_real_escape_string(
        $conn,
        $_GET['title']
    );

    $where .= " AND jobs.title LIKE '%$title%'";
}


// Search by Location
if (isset($_GET['location']) && $_GET['location'] != "") {

    $location = mysqli_real_escape_string(
        $conn,
        $_GET['location']
    );

    $where .= " AND jobs.location LIKE '%$location%'";
}


// Search by Job Type
if (isset($_GET['type']) && $_GET['type'] != "") {

    $type = mysqli_real_escape_string(
        $conn,
        $_GET['type']
    );

    $where .= " AND jobs.job_type = '$type'";
}


// =====================================================
// Get Jobs
// =====================================================

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

        $where

        ORDER BY jobs.id DESC";


$result = mysqli_query($conn, $sql);


// Check Query
if (!$result) {

    die(
        "Database Error: " .
        mysqli_error($conn)
    );

}

?>


<link
    rel="stylesheet"
    href="assets/css/jobs.css"
>


<!-- =====================================================
     Hero Section
===================================================== -->

<section class="jobs-hero">

    <div class="container">

        <div class="text-center">

            <span class="hero-small">
                FIND YOUR CAREER
            </span>

            <h1>
                Available Jobs
            </h1>

            <p>
                Explore the latest opportunities from trusted companies
                and build your future with confidence.
            </p>

        </div>

    </div>

</section>



<!-- =====================================================
     Search Section
===================================================== -->

<section class="search-section">

    <div class="container">

        <div class="search-box">

            <form
                action=""
                method="GET"
            >

                <div class="row g-3 align-items-center">


                    <!-- Job Title -->

                    <div class="col-lg-4">

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="🔎 Job Title"
                            value="<?= isset($_GET['title'])
                                ? htmlspecialchars($_GET['title'])
                                : ''; ?>"
                        >

                    </div>


                    <!-- Location -->

                    <div class="col-lg-3">

                        <input
                            type="text"
                            name="location"
                            class="form-control"
                            placeholder="📍 Location"
                            value="<?= isset($_GET['location'])
                                ? htmlspecialchars($_GET['location'])
                                : ''; ?>"
                        >

                    </div>


                    <!-- Job Type -->

                    <div class="col-lg-3">

                        <select
                            name="type"
                            class="form-select"
                        >

                            <option value="">
                                Job Type
                            </option>


                            <option
                                value="full time"
                                <?= (
                                    isset($_GET['type']) &&
                                    $_GET['type'] == 'full time'
                                ) ? 'selected' : ''; ?>
                            >
                                Full-time
                            </option>


                            <option
                                value="part time"
                                <?= (
                                    isset($_GET['type']) &&
                                    $_GET['type'] == 'part time'
                                ) ? 'selected' : ''; ?>
                            >
                                Part-time
                            </option>


                            <option
                                value="remote"
                                <?= (
                                    isset($_GET['type']) &&
                                    $_GET['type'] == 'remote'
                                ) ? 'selected' : ''; ?>
                            >
                                Remote
                            </option>


                            <option
                                value="internship"
                                <?= (
                                    isset($_GET['type']) &&
                                    $_GET['type'] == 'internship'
                                ) ? 'selected' : ''; ?>
                            >
                                Internship
                            </option>

                        </select>

                    </div>


                    <!-- Search Button -->

                    <div class="col-lg-2">

                        <button
                            type="submit"
                            class="btn search-btn w-100"
                        >

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</section>



<!-- =====================================================
     Latest Jobs
===================================================== -->

<section class="jobs-section py-5">

    <div class="container">


        <!-- Section Header -->

        <div
            class="section-header d-flex
                   justify-content-between
                   align-items-center mb-5"
        >

            <div>

                <h2 class="fw-bold text-white">

                    Latest Job Opportunities

                </h2>


                <p class="text-light mb-0">

                    <?= mysqli_num_rows($result); ?>

                    Jobs Available

                </p>

            </div>

        </div>



        <!-- Jobs Row -->

        <div class="row">


            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($job = mysqli_fetch_assoc($result)) {

            ?>


                    <div class="col-lg-6 mb-4">

                        <div class="job-card">


                            <!-- =================================
                                 Job Top
                            ================================== -->

                            <div class="job-top">


                                <!-- Company Logo -->

                                <div class="company-logo">

                                    <?php

                                    $companyName =
                                        $job['company_name']
                                        ?? 'Company';

                                    echo strtoupper(
                                        substr(
                                            $companyName,
                                            0,
                                            1
                                        )
                                    );

                                    ?>

                                </div>


                                <!-- Job Title -->

                                <div class="job-title-area">

                                    <h4>

                                        <?= htmlspecialchars(
                                            $job['title']
                                        ); ?>

                                    </h4>


                                    <span>

                                        <?= htmlspecialchars(
                                            $companyName
                                        ); ?>

                                    </span>

                                </div>


                                <!-- Status -->

                                <span class="status">

                                    <?= htmlspecialchars(
                                        $job['status']
                                    ); ?>

                                </span>

                            </div>



                            <!-- =================================
                                 Job Information
                            ================================== -->

                            <div class="job-info">


                                <!-- Location -->

                                <span>

                                    📍

                                    <?= htmlspecialchars(
                                        $job['location']
                                    ); ?>

                                </span>


                                <!-- Job Type -->

                                <span>

                                    💼

                                    <?= htmlspecialchars(
                                        $job['job_type']
                                    ); ?>

                                </span>


                                <!-- Salary -->

                                <span>

                                    💰

                                    <?php

                                    if (
                                        isset($job['salary']) &&
                                        $job['salary'] !== ''
                                    ) {

                                        echo number_format(
                                            (float)$job['salary']
                                        );

                                        echo " EGP";

                                    } else {

                                        echo "Not specified";

                                    }

                                    ?>

                                </span>


                                <!-- Category -->

                                <span>

                                    📂

                                    <?= !empty(
                                        $job['category_name']
                                    )
                                        ? htmlspecialchars(
                                            $job['category_name']
                                        )
                                        : 'Not specified'; ?>

                                </span>

                            </div>



                            <!-- =================================
                                 Description
                            ================================== -->

                            <p class="job-desc">

                                <?= htmlspecialchars(
                                    substr(
                                        $job['description'],
                                        0,
                                        150
                                    )
                                ); ?>

                                <?php

                                if (
                                    strlen(
                                        $job['description']
                                    ) > 150
                                ) {

                                    echo "...";

                                }

                                ?>

                            </p>



                            <!-- =================================
                                 Job Footer
                            ================================== -->

                            <div class="job-footer">


                                <small>

                                    Job ID:

                                    <?= (int)$job['id']; ?>

                                </small>


                                <!-- View Details -->

                                <a
                                    href="job_details.php?id=<?= (int)$job['id']; ?>"
                                    class="btn btn-primary"
                                >

                                    <i class="bi bi-eye"></i>

                                    View Details

                                </a>

                            </div>


                        </div>

                    </div>


            <?php

                }

            } else {

            ?>


                <!-- No Jobs -->

                <div class="col-12">

                    <div class="empty-box text-center">

                        <h3>

                            No Jobs Found

                        </h3>


                        <p>

                            There are no available jobs at the moment.

                        </p>

                    </div>

                </div>


            <?php

            }

            ?>


        </div>

    </div>

</section>



<?php

include_once "includes/footer.php";

?>