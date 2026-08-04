<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'job_seeker') {
    header("Location: ../login.php");
    exit();
}

include_once "../includes/db.php";

if (!isset($_GET['id'])) {
    die("Job not found");
}

$job_id = intval($_GET['id']);
$seeker_id = $_SESSION['user_id'];

// التأكد إن المستخدم مقدمش قبل كده
$check = mysqli_query($conn,
"SELECT * FROM applications
WHERE job_id='$job_id'
AND seeker_id='$seeker_id'");

if(mysqli_num_rows($check) > 0){

    echo "<script>
    alert('You already applied for this job');
    window.location='jobs.php';
    </script>";

    exit();
}

// إضافة الطلب

$sql = "INSERT INTO applications
(job_id,seeker_id,status)
VALUES
('$job_id','$seeker_id','Pending')";

if(mysqli_query($conn,$sql)){

    echo "<script>
    alert('Application Submitted Successfully');
    window.location='jobs.php';
    </script>";

}else{

    echo mysqli_error($conn);

}
?>