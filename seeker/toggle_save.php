<?php
session_start();
include_once "../includes/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'job_seeker' || !isset($_GET['job_id'])) {
    if (isset($_GET['redirect']) && $_GET['redirect'] === 'saved') {
        header("Location: save_job.php");
        exit();
    }
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error']);
    exit();
}

$userId = $_SESSION['user_id'];
$jobId = (int)$_GET['job_id'];

// هل الوظيفة محفوظة مسبقاً؟
$check = mysqli_query($conn, "SELECT * FROM saved_jobs WHERE seeker_id = $userId AND job_id = $jobId");

if (mysqli_num_rows($check) > 0) {
    // لو محفوظة، امسحها (Unsave)
    mysqli_query($conn, "DELETE FROM saved_jobs WHERE seeker_id = $userId AND job_id = $jobId");
    $status = 'removed';
} else {
    // لو مش محفوظة، احفظها فوراً (Save)
    mysqli_query($conn, "INSERT INTO saved_jobs (seeker_id, job_id) VALUES ($userId, $jobId)");
    $status = 'saved';
}

// إذا تم القدوم من صفحة الـ Saved Jobs، يتم إعادة التوجيه إليها فوراً
if (isset($_GET['redirect']) && $_GET['redirect'] === 'saved') {
    header("Location: save_job.php");
    exit();
}

// وإلا فسيتم الاستجابة عبر الـ AJAX بشكل طبيعي
header('Content-Type: application/json');
echo json_encode(['status' => $status]);
exit();