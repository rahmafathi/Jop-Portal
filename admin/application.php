<?php
include "../includes/db.php";

// Delete Application
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM application WHERE id='$id'");

    header("Location: applications.php");
    exit();
}

// Accept Application
if (isset($_GET['accept'])) {

    $id = intval($_GET['accept']);

    mysqli_query($conn, "UPDATE application SET status='accepted' WHERE id='$id'");

    header("Location: applications.php");
    exit();
}

// Reject Application
if (isset($_GET['reject'])) {

    $id = intval($_GET['reject']);

    mysqli_query($conn, "UPDATE application SET status='rejected' WHERE id='$id'");

    header("Location: applications.php");
    exit();
}

// Get Applications

$sql = "
SELECT
application.*,

users.name,

jobs.title

FROM application

JOIN users
ON application.seeker_id = users.id

JOIN jobs
ON application.job_id = jobs.id

ORDER BY application.applied_at DESC
";

$result = mysqli_query($conn,$sql);

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Applications</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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

<h2 class="fw-bold mb-4">
<i class="fa-solid fa-file-lines text-primary"></i>

Applications Management

</h2>
<!-- Statistics -->

<div class="row mb-4">

<?php

$total = mysqli_num_rows($result);

$pending = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM application WHERE status='pending'"));

$accepted = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM application WHERE status='accepted'"));

$rejected = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM application WHERE status='rejected'"));

?>

<div class="col-md-3">
    <div class="card stat-card p-3">
        <h6>Total Applications</h6>
        <h2><?= $total ?></h2>
    </div>
</div>

<div class="col-md-3">
    <div class="card stat-card p-3">
        <h6>Pending</h6>
        <h2 class="text-warning"><?= $pending ?></h2>
    </div>
</div>

<div class="col-md-3">
    <div class="card stat-card p-3">
        <h6>Accepted</h6>
        <h2 class="text-success"><?= $accepted ?></h2>
    </div>
</div>

<div class="col-md-3">
    <div class="card stat-card p-3">
        <h6>Rejected</h6>
        <h2 class="text-danger"><?= $rejected ?></h2>
    </div>
</div>

</div>

<!-- Table -->

<div class="table-card p-4">

<h4 class="fw-bold mb-3">

<i class="fa-solid fa-users"></i>

Applications List

</h4>

<div class="table-responsive">

<table class="table align-middle">

<thead>

<tr>

<th>ID</th>

<th>Applicant</th>

<th>Job</th>

<th>CV</th>

<th>Status</th>

<th>Applied At</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= $row['name'] ?></td>

<td><?= $row['title'] ?></td>

<td>

<?php if(!empty($row['cv'])){ ?>

<a href="../uploads/<?= $row['cv'] ?>" target="_blank"
class="btn btn-sm btn-info">

<i class="fa-solid fa-file"></i>

View CV

</a>

<?php }else{ ?>

<span class="text-muted">No CV</span>

<?php } ?>

</td>

<td>

<?php

if($row['status']=="pending"){

echo "<span class='badge bg-warning'>Pending</span>";

}elseif($row['status']=="accepted"){

echo "<span class='badge bg-success'>Accepted</span>";

}else{

echo "<span class='badge bg-danger'>Rejected</span>";

}

?>

</td>

<td><?= $row['applied_at'] ?></td>

<td>
    <!-- Actions -->

<a href="applications.php?accept=<?= $row['id'] ?>"
   class="btn btn-sm btn-success"
   onclick="return confirm('Accept this application?')">
    <i class="fa-solid fa-check"></i>
</a>

<a href="applications.php?reject=<?= $row['id'] ?>"
   class="btn btn-sm btn-warning"
   onclick="return confirm('Reject this application?')">
    <i class="fa-solid fa-xmark"></i>
</a>

<a href="applications.php?delete=<?= $row['id'] ?>"
   class="btn btn-sm btn-danger"
   onclick="return confirm('Delete this application?')">
    <i class="fa-solid fa-trash"></i>
</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>
<!-- End Main Content -->

</div>
<!-- End Row -->

</div>
<!-- End Container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>