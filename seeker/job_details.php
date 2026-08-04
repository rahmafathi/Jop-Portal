<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'job_seeker') {
    header("Location: ../login.php");
    exit();
}

include_once "../includes/db.php";
include_once "../includes/header.php";

if (!isset($_GET['id'])) {
    die("Job not found");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT jobs.*, companies.company_name, categories.category_name
FROM jobs
JOIN companies ON jobs.company_id = companies.id
JOIN categories ON jobs.category_id = categories.id
WHERE jobs.id='$id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Job not found");
}

$job = mysqli_fetch_assoc($result);
?>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-body">

            <h2><?= $job['title']; ?></h2>

            <h5 class="text-primary">
                <?= $job['company_name']; ?>
            </h5>

            <hr>

            <p><strong>Category:</strong> <?= $job['category_name']; ?></p>

            <p><strong>Location:</strong> <?= $job['location']; ?></p>

            <p><strong>Salary:</strong> <?= $job['salary']; ?></p>

            <p><strong>Job Type:</strong> <?= $job['job_type']; ?></p>

            <p><strong>Experience:</strong> <?= $job['experience']; ?></p>

            <p><strong>Description:</strong></p>

            <p><?= $job['description']; ?></p>

            <p><strong>Requirements:</strong></p>

            <p><?= $job['requirements']; ?></p>

            <a href="jobs.php" class="btn btn-secondary">
                Back
            </a>

            <a href="#" class="btn btn-success">
                Apply Now
            </a>

        </div>

    </div>

</div>

<?php
include_once "../includes/footer.php";
?>