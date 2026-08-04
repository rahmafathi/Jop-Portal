<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';


$id = $_GET['id'] ?? 0;


if ($id) {

    $query = "DELETE FROM jobs WHERE id='$id'";

    if (mysqli_query($conn, $query)) {

        $_SESSION['success'] = "Job deleted successfully";

    } else {

        $_SESSION['error'] = "Something went wrong while deleting the job";

    }

} else {

    $_SESSION['error'] = "Invalid job id";

}


// الرجوع لصفحة الوظائف
header("Location: my_jobs.php");
exit;

?>