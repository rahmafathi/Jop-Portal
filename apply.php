<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "includes/db.php";
include_once "includes/functions.php";
include_once "includes/header.php";
include_once "includes/nav.php";


// =====================================
// Check Login
// =====================================

if (!isset($_SESSION['user_id'])) {

    setMessage('danger', 'Please login first.');

    redirect('login.php');
}


// =====================================
// Check Job ID
// =====================================

if (!isset($_GET['job_id']) || !is_numeric($_GET['job_id'])) {

    redirect('jobs.php');
}

$job_id = (int) $_GET['job_id'];

$seeker_id = (int) $_SESSION['user_id'];


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
            companies.company_name

        FROM jobs

        LEFT JOIN companies
            ON jobs.company_id = companies.id

        WHERE jobs.id = $job_id

        LIMIT 1";

$result = mysqli_query($conn, $sql);


if (!$result) {

    die("Database Error: " . mysqli_error($conn));
}


if (mysqli_num_rows($result) == 0) {

    setMessage('danger', 'Job not found.');

    redirect('jobs.php');
}


$job = mysqli_fetch_assoc($result);


// =====================================
// Check Job Status
// =====================================

if (strtolower(trim($job['status'])) !== 'open') {

    setMessage('danger', 'This job is closed.');

    redirect("job_details.php?id=$job_id");
}


// =====================================
// Check Previous Application
// =====================================

$checkSql = "SELECT id
             FROM application
             WHERE job_id = $job_id
             AND seeker_id = $seeker_id
             LIMIT 1";

$checkResult = mysqli_query($conn, $checkSql);


if (!$checkResult) {

    die("Database Error: " . mysqli_error($conn));
}


if (mysqli_num_rows($checkResult) > 0) {

    setMessage(
        'warning',
        'You have already applied for this job.'
    );

    redirect("job_details.php?id=$job_id");
}


// =====================================
// Submit Application
// =====================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cover_letter = trim(
        $_POST['cover_letter'] ?? ''
    );


    if (checkEmpty($cover_letter)) {

        setMessage(
            'danger',
            'Please write your cover letter.'
        );

    } else {

        $cover_letter = mysqli_real_escape_string(
            $conn,
            $cover_letter
        );


        /*
         * CV is optional here.
         * The application table allows CV to be NULL.
         */

        $insertSql = "INSERT INTO application
                      (
                          job_id,
                          seeker_id,
                          cover_letter
                      )

                      VALUES
                      (
                          '$job_id',
                          '$seeker_id',
                          '$cover_letter'
                      )";


        if (mysqli_query($conn, $insertSql)) {

            setMessage(
                'success',
                'Your application has been submitted successfully!'
            );

            redirect(
                "job_details.php?id=$job_id"
            );

        } else {

            die(
                "Database Error: " .
                mysqli_error($conn)
            );
        }
    }
}

?>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-md-5">


                    <!-- =================================
                         Page Title
                    ================================== -->

                    <div class="mb-4">

                        <h2 class="fw-bold">

                            Apply for Job

                        </h2>

                        <p class="text-muted">

                            Submit your application for this position.

                        </p>

                    </div>



                    <!-- =================================
                         Job Information
                    ================================== -->

                    <div class="p-4 bg-light rounded-4 mb-4">

                        <h4 class="fw-bold mb-2">

                            <?= sanitize(
                                $job['title']
                            ); ?>

                        </h4>


                        <p class="text-muted mb-2">

                            <i class="bi bi-building"></i>

                            <?= !empty($job['company_name'])
                                ? sanitize(
                                    $job['company_name']
                                )
                                : 'Company'; ?>

                        </p>


                        <p class="text-muted mb-2">

                            <i class="bi bi-geo-alt"></i>

                            <?= sanitize(
                                $job['location']
                            ); ?>

                        </p>


                        <p class="text-muted mb-0">

                            <i class="bi bi-briefcase"></i>

                            <?= sanitize(
                                $job['job_type']
                            ); ?>

                        </p>

                    </div>



                    <!-- =================================
                         Application Form
                    ================================== -->

                    <form
                        method="POST"
                        action=""
                    >


                        <div class="mb-4">

                            <label
                                class="form-label fw-bold"
                            >

                                Cover Letter *

                            </label>


                            <textarea
                                name="cover_letter"
                                class="form-control"
                                rows="8"
                                placeholder="Write your cover letter here..."
                                required
                            ></textarea>


                            <small class="text-muted">

                                Tell the company why you are a good
                                fit for this job.

                            </small>

                        </div>



                        <!-- =================================
                             Buttons
                        ================================== -->

                        <div class="d-flex gap-2">


                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >

                                <i class="bi bi-send"></i>

                                Submit Application

                            </button>


                            <a
                                href="job_details.php?id=<?= $job_id; ?>"
                                class="btn btn-outline-secondary"
                            >

                                Cancel

                            </a>


                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<?php

include_once "includes/footer.php";

?>