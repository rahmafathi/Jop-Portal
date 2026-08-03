<?php

include_once "includes/header.php";
include_once "includes/nav.php";
include_once "includes/functions.php";
include_once "includes/db.php";

/* Search */

$where = "WHERE 1";

if(isset($_GET['title']) && $_GET['title'] != ""){

    $title = mysqli_real_escape_string($conn,$_GET['title']);
    $where .= " AND title LIKE '%$title%'";

}

if(isset($_GET['location']) && $_GET['location'] != ""){

    $location = mysqli_real_escape_string($conn,$_GET['location']);
    $where .= " AND location LIKE '%$location%'";

}

if(isset($_GET['type']) && $_GET['type'] != ""){

    $type = mysqli_real_escape_string($conn,$_GET['type']);
    $where .= " AND job_type='$type'";

}

$sql = "SELECT jobs.*, companies.company_name
FROM jobs
LEFT JOIN companies
ON jobs.company_id = companies.id
$where
ORDER BY jobs.id DESC";

$result = mysqli_query($conn,$sql);

?>

<link rel="stylesheet" href="assets/css/jobs.css">
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
                Explore the latest opportunities from trusted companies and
                build your future with confidence.
            </p>

        </div>

    </div>

</section>
<!-- Search -->

<section class="search-section">

    <div class="container">

        <div class="search-box">

            <form action="" method="GET">

                <div class="row g-3 align-items-center">

                    <div class="col-lg-4">

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="🔎 Job Title"
                            value="<?= isset($_GET['title']) ? $_GET['title'] : ''; ?>">

                    </div>

                    <div class="col-lg-3">

                        <input
                            type="text"
                            name="location"
                            class="form-control"
                            placeholder="📍 Location"
                            value="<?= isset($_GET['location']) ? $_GET['location'] : ''; ?>">

                    </div>

                    <div class="col-lg-3">

                        <select
                            name="type"
                            class="form-select">

                            <option value="">Job Type</option>

                            <option value="Full Time">Full Time</option>

                            <option value="Part Time">Part Time</option>

                            <option value="Remote">Remote</option>

                            <option value="Internship">Internship</option>

                        </select>

                    </div>

                    <div class="col-lg-2">

                        <button class="btn search-btn w-100">

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</section>
<!-- Latest Jobs -->

<section class="jobs-section py-5">

    <div class="container">

        <div class="section-header d-flex justify-content-between align-items-center mb-5">

            <div>

                <h2 class="fw-bold text-white">

                    Latest Job Opportunities

                </h2>

                <p class="text-light mb-0">

                    <?= mysqli_num_rows($result); ?> Jobs Available

                </p>

            </div>

        </div>

        <div class="row">

            <?php

            if(mysqli_num_rows($result)>0){

            while($job=mysqli_fetch_assoc($result)){

            ?>

            <div class="col-lg-6 mb-4">

                <div class="job-card">

                    <div class="job-top">

                        <div class="company-logo">

                            <?= strtoupper(substr($job['company_name'],0,1)); ?>

                        </div>

                        <div class="job-title-area">

                            <h4>

                                <?= $job['title']; ?>

                            </h4>

                            <span>

                                Company #<?= $job['company_name']; ?>

                            </span>

                        </div>

                        <span class="status">

                            <?= $job['status']; ?>

                        </span>

                    </div>

                    <div class="job-info">

                        <span>

                            📍 <?= $job['location']; ?>

                        </span>

                        <span>

                            💼 <?= $job['job_type']; ?>

                        </span>

                        <span>

                            💰 <?= number_format($job['salary']); ?> EGP

                        </span>

                        <span>

                            ⭐ <?= $job['experience']; ?>

                        </span>

                    </div>

                    <p class="job-desc">

                        <?= substr($job['description'],0,150); ?>...

                    </p>

                    <div class="job-footer">

                        <small>

                            Deadline :
                            <?= $job['deadline']; ?>

                        </small>

                        <a href="job_details.php?id=<?= $job['id']; ?>"

                            class="btn btn-primary">

                            View Details

                        </a>

                    </div>

                </div>

            </div>

            <?php

            }

            }else{

            ?>

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

            <?php } ?>

        </div>

    </div>

</section>

<?php

include_once "includes/footer.php";

?>