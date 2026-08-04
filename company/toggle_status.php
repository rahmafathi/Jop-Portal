<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // جلب الحالة الحالية للوظيفة من القاعدة
    $result = mysqli_query($conn, "SELECT status FROM jobs WHERE id = $id");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // لو هي open خليها closed، والعكس صحيح
        $new_status = ($row['status'] == 'open') ? 'closed' : 'open';
        
        // تحديث الحالة الجديدة
        mysqli_query($conn, "UPDATE jobs SET status = '$new_status' WHERE id = $id");
    }
}

header("Location: my_jobs.php");
exit();
?>