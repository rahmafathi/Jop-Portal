<?php
session_start();

include("../includes/db.php");

if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}

$company_id = $_SESSION['company_id'];

$sql = "SELECT
            application.id,
            users.name,
            users.email,
            users.phone,
            jobs.title,
            application.status,
            application.applied_at
        FROM application
        INNER JOIN jobs ON application.job_id = jobs.id
        INNER JOIN users ON application.seeker_id = users.id
        WHERE jobs.company_id = '$company_id'
        ORDER BY application.applied_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f6fa;
        }
        .card{
            margin-top:40px;
            border:none;
            border-radius:15px;
            box-shadow:0 0 10px rgba(0,0,0,.1);
        }
    </style>
</head>
<body>

<div class="container">

    <div class="card">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">View Applicants</h3>

            <a href="dashboard.php" class="btn btn-light">
                Back
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Applicant Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Applied At</th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php

                    if(mysqli_num_rows($result)>0){

                        while($row=mysqli_fetch_assoc($result)){

                    ?>

                    <tr>

                        <td><?= $row['id']; ?></td>

                        <td><?= $row['name']; ?></td>

                        <td><?= $row['email']; ?></td>

                        <td><?= $row['phone']; ?></td>

                        <td><?= $row['title']; ?></td>

                        <td>

                            <?php

                            if($row['status']=="pending"){

                                echo "<span class='badge bg-warning text-dark'>Pending</span>";

                            }elseif($row['status']=="accepted"){

                                echo "<span class='badge bg-success'>Accepted</span>";

                            }else{

                                echo "<span class='badge bg-danger'>Rejected</span>";

                            }

                            ?>

                        </td>

                        <td><?= $row['applied_at']; ?></td>

                    </tr>

                    <?php

                        }

                    }else{

                    ?>

                    <tr>

                        <td colspan="7" class="text-center text-muted">
                            No Applicants Found
                        </td>

                    </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>