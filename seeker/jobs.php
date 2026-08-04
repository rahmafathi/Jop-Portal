<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'job_seeker') {
    header("Location: ../login.php");
    exit();
}

include_once "../includes/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";
include_once "../includes/nav.php";

/* ===========================
   Search & Filters
=========================== */

$search = "";
$category = "";
$location = "";
$job_type = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn,$_GET['search']);
}

if(isset($_GET['category'])){
    $category = mysqli_real_escape_string($conn,$_GET['category']);
}

if(isset($_GET['location'])){
    $location = mysqli_real_escape_string($conn,$_GET['location']);
}

if(isset($_GET['job_type'])){
    $job_type = mysqli_real_escape_string($conn,$_GET['job_type']);
}

/* ===========================
   SQL
=========================== */


$limit = 6;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$start = ($page - 1) * $limit;


$sql = "SELECT jobs.*, companies.company_name, categories.category_name
FROM jobs
JOIN companies ON jobs.company_id = companies.id
JOIN categories ON jobs.category_id = categories.id
WHERE 1";

if($search != ""){
    $sql .= " AND (jobs.title LIKE '%$search%'
              OR companies.company_name LIKE '%$search%')";
}

if($category != ""){
    $sql .= " AND jobs.category_id='$category'";
}

if($location != ""){
    $sql .= " AND jobs.location='$location'";
}

if($job_type != ""){
    $sql .= " AND jobs.job_type='$job_type'";
}

$sql .= " ORDER BY jobs.created_at DESC
LIMIT $start,$limit";

$result = mysqli_query($conn,$sql);

?>

<div class="container mt-5">

    <h2 class="mb-4">Available Jobs</h2>

    <!-- Search + Filter -->

    <form method="GET" class="row g-3 mb-4">

        <div class="col-md-3">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search..."
                value="<?= $search; ?>">
        </div>

        <div class="col-md-3">

            <select name="category" class="form-select">

                <option value="">All Categories</option>

                <?php

                $cat = mysqli_query($conn,"SELECT * FROM categories");

                while($row = mysqli_fetch_assoc($cat)){

                ?>

                <option
                    value="<?= $row['id']; ?>"
                    <?= ($category == $row['id']) ? "selected" : ""; ?>>

                    <?= $row['category_name']; ?>

                </option>

                <?php } ?>

            </select>

        </div>

        <div class="col-md-2">

            <input
                type="text"
                name="location"
                class="form-control"
                placeholder="Location"
                value="<?= $location; ?>">

        </div>

        <div class="col-md-2">

            <select name="job_type" class="form-select">

                <option value="">All Types</option>

                <option value="full time"
                    <?= ($job_type=="full time")?"selected":""; ?>>

                    Full Time

                </option>

                <option value="part time"
                    <?= ($job_type=="part time")?"selected":""; ?>>

                    Part Time

                </option>

                <option value="remote"
                    <?= ($job_type=="remote")?"selected":""; ?>>

                    Remote

                </option>

            </select>

        </div>

        <div class="col-md-2">

            <button class="btn btn-primary w-100">

                Search

            </button>

        </div>

    </form>

    <!-- Jobs -->

    <div class="row">

        <?php

        if(mysqli_num_rows($result) > 0){

            while($job = mysqli_fetch_assoc($result)){

        ?>

        <div class="col-md-4 mb-4">

            <div class="card shadow h-100">

                <div class="card-body">

                    <h4><?= $job['title']; ?></h4>

                    <h6 class="text-primary">

                        <?= $job['company_name']; ?>

                    </h6>

                    <p>

                        <strong>Category:</strong>

                        <?= $job['category_name']; ?>

                    </p>

                    <p>

                        <strong>Location:</strong>

                        <?= $job['location']; ?>

                    </p>

                    <p>

                        <strong>Salary:</strong>

                        <?= $job['salary']; ?>

                    </p>

                    <p>

                        <strong>Job Type:</strong>

                        <?= $job['job_type']; ?>

                    </p>

                    <p>

                        <strong>Posted:</strong>

                        <?= date("d M Y",strtotime($job['created_at'])); ?>

                    </p>

                   <a href="job_details.php?id=<?= $job['id']; ?>" class="btn btn-primary btn-sm">
    View Details
</a>

                    <a href="apply_job.php?id=<?= $job['id']; ?>" class="btn btn-success btn-sm">
    Apply Now
</a>

                    <a href="save_job.php?id=<?= $job['id']; ?>" class="btn btn-warning btn-sm">
    Save Job
</a>

                </div>

            </div>

        </div>

        <?php

            }

        }else{

            echo "<div class='alert alert-info'>No Jobs Available.</div>";

        }

        ?>

    </div>

</div>


<?php

$count = mysqli_query($conn,"SELECT COUNT(*) as total FROM jobs");

$total = mysqli_fetch_assoc($count);

$pages = ceil($total['total']/$limit);

?>

<nav class="mt-4">

<ul class="pagination justify-content-center">

<?php for($i=1;$i<=$pages;$i++){ ?>

<li class="page-item <?= ($page==$i)?'active':''; ?>">

<a class="page-link" href="?page=<?= $i; ?>">

<?= $i; ?>

</a>

</li>

<?php } ?>

</ul>

</nav>


<?php
include_once "../includes/footer.php";
?>