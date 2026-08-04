<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';

// التأكد من وجود id
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {

    $query = "DELETE FROM jobs WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {

        $_SESSION['success'] = "Job deleted successfully.";

    } else {

        $_SESSION['error'] = "Something went wrong while deleting the job.";

    }

} else {

    $_SESSION['error'] = "Invalid job ID.";

}

// الرجوع إلى صفحة الوظائف
header("Location: my_jobs.php");
exit();