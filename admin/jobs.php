<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../includes/db.php";

//====================== DELETE JOB ======================

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM jobs WHERE id='$id'");

    header("Location: jobs.php");
    exit();
}

//====================== STATISTICS ======================

$total_jobs = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM jobs"));

$open_jobs = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM jobs WHERE status='open'"));

$closed_jobs = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM jobs WHERE status='closed'"));

$total_applications = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM application"));


//====================== GET JOBS ======================

$query = "

SELECT jobs.*,

companies.company_name,

categories.category_name

FROM jobs

LEFT JOIN companies
ON jobs.company_id = companies.id

LEFT JOIN categories
ON jobs.category_id = categories.id

ORDER BY jobs.id DESC

";

$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Jobs Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet"
href="../assets/css/users.css?v=<?php echo time();?>">

</head>

<body>

<div class="container-fluid p-0">

<div class="row g-0">

<?php include 'sidebar.php'; ?>

<div class="col-md-10 p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

Jobs Management

</h2>

<p class="text-muted">

Manage all jobs posted by companies

</p>

</div>



</div>

<!-- ====================== STATISTICS ====================== -->

<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow-sm border-0">

<div class="card-body">

<h6 class="text-muted">

Total Jobs

</h6>

<h2>

<?= $total_jobs['total']; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm border-0">

<div class="card-body">

<h6 class="text-muted">

Open Jobs

</h6>

<h2 class="text-success">

<?= $open_jobs['total']; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm border-0">

<div class="card-body">

<h6 class="text-muted">

Closed Jobs

</h6>

<h2 class="text-danger">

<?= $closed_jobs['total']; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm border-0">

<div class="card-body">

<h6 class="text-muted">

Applications

</h6>

<h2 class="text-primary">

<?= $total_applications['total']; ?>

</h2>

</div>

</div>

</div>

</div>



<!-- ====================== JOB CARDS ====================== -->

<div class="row">

<?php

if(mysqli_num_rows($result)>0){

while($job=mysqli_fetch_assoc($result)){

?>

<div class="col-lg-6 mb-4">

<div class="card shadow-sm border-0 h-100">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h5 class="fw-bold">

<?= htmlspecialchars($job['title']); ?>

</h5>

<p class="text-muted mb-1">

<i class="fas fa-building"></i>

<?= htmlspecialchars($job['company_name']); ?>

</p>

<p class="text-muted">

<i class="fas fa-layer-group"></i>

<?= htmlspecialchars($job['category_name']); ?>

</p>

</div>

<div>

<?php

if($job['status']=="open"){

?>

<span class="badge bg-success">

Open

</span>

<?php

}else{

?>

<span class="badge bg-danger">

Closed

</span>

<?php } ?>

</div>

</div>

<hr>

<div class="row">

<div class="col-6">

<p>

<i class="fas fa-map-marker-alt text-danger"></i>

<?= htmlspecialchars($job['location']); ?>

</p>

</div>

<div class="col-6">

<p>

<i class="fas fa-money-bill-wave text-success"></i>

<?= htmlspecialchars($job['salary']); ?>

</p>

</div>

<div class="col-6">

<p>

<i class="fas fa-briefcase text-primary"></i>

<?= htmlspecialchars($job['job_type']); ?>

</p>

</div>

<div class="col-6">

<p>

<i class="fas fa-calendar"></i>

<?= date("d M Y",strtotime($job['created_at'])); ?>

</p>

</div>

</div>

<hr>

<div class="d-flex justify-content-end gap-2">

<a href="#"
class="btn btn-info btn-sm">

<i class="fas fa-eye"></i>

View

</a>



<a href="jobs.php?delete=<?= $job['id'];?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this Job?')">

<i class="fas fa-trash"></i>

Delete

</a>

</div>

</div>

</div>

</div>

<?php

}

}

?>
<?php

if(mysqli_num_rows($result) == 0){

?>

<div class="col-12">

    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <i class="fas fa-briefcase fa-4x text-secondary mb-3"></i>

            <h4>No Jobs Found</h4>

            <p class="text-muted">

                There are no jobs available yet.

            </p>

        </div>

    </div>

</div>

<?php

}

?>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<style>

.card{
    border-radius:18px;
}

.card:hover{
    transform:translateY(-5px);
    transition:.3s;
    box-shadow:0 10px 25px rgba(0,0,0,.12);
}

.badge{
    font-size:13px;
    padding:8px 14px;
}

.btn{
    border-radius:10px;
}

h2{
    font-weight:700;
}

.text-muted{
    font-size:14px;
}

.card-body p{
    margin-bottom:8px;
}

.fa-building{
    color:#2563eb;
}

.fa-layer-group{
    color:#9333ea;
}

.fa-map-marker-alt{
    color:#dc2626;
}

.fa-money-bill-wave{
    color:#16a34a;
}

.fa-briefcase{
    color:#f59e0b;
}

.fa-calendar{
    color:#64748b;
}

.card-body{
    padding:25px;
}

input.form-control{
    height:50px;
    border-radius:12px;
}

.btn-primary{

    background:#2563eb;

    border:none;

}

.btn-primary:hover{

    background:#1d4ed8;

}

.btn-warning{

    color:#fff;

}

.table-card{

    background:#fff;

    border-radius:18px;

}

.stat-card{

    border:none;

    border-radius:18px;

    box-shadow:0 10px 20px rgba(0,0,0,.05);

}

</style>