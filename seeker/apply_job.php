<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'job_seeker') {
    header("Location: ../login.php");
    exit();
}

include_once "../includes/db.php";

if (isset($_GET['id'])) {
    $userId = $_SESSION['user_id'];
    $jobId = mysqli_real_escape_string($conn, $_GET['id']);
    
    $cv = 'cv_sample.pdf';
    $coverLetter = 'I am very interested in this position.';
    $status = 'pending';

    // التحقق هل قدم مسبقاً
    $check = mysqli_query($conn, "SELECT * FROM application WHERE job_id = '$jobId' AND seeker_id = '$userId'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('You have already applied for this job!'); window.location.href='jobs.php';</script>";
        exit();
    }

    // الإدخال
    $sql = "INSERT INTO application (job_id, seeker_id, cv, cover_letter, status, applied_at) 
            VALUES ('$jobId', '$userId', '$cv', '$coverLetter', '$status', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Application submitted successfully!'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>