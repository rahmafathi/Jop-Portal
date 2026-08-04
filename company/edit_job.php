<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
include_once '../includes/header.php';
include_once '../includes/nav.php';

$id = $_GET['id'] ?? 0;

// جلب بيانات الوظيفة
$query = "SELECT * FROM jobs WHERE id='$id'";
$result = mysqli_query($conn, $query);

$job = mysqli_fetch_assoc($result);

if (!$job) {
    echo "<div class='alert alert-danger'>Job not found</div>";
    include_once '../includes/footer.php';
    exit;
}

// تحديث البيانات
if (isset($_POST['update'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update = "UPDATE jobs SET
                title='$title',
                job_type='$job_type',
                salary='$salary',
                status='$status'
                WHERE id='$id'";

    if (mysqli_query($conn, $update)) {

        $_SESSION['success'] = "Job updated successfully";
        header("Location: my_jobs.php");
        exit;

    } else {

        echo "<div class='alert alert-danger'>" . mysqli_error($conn) . "</div>";

    }
}
?>

<div class="container my-5">

    <div class="card shadow p-4">

        <h2 class="mb-4">Edit Job</h2>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Job Title</label>
                <input type="text"
                       name="title"
                       class="form-control"
                       value="<?php echo htmlspecialchars($job['title']); ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Job Type</label>

                <select name="job_type" class="form-control">

                    <option value="Full Time"
                        <?php if($job['job_type']=="Full Time") echo "selected"; ?>>
                        Full Time
                    </option>

                    <option value="Part Time"
                        <?php if($job['job_type']=="Part Time") echo "selected"; ?>>
                        Part Time
                    </option>

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Salary</label>

                <input type="number"
                       name="salary"
                       class="form-control"
                       value="<?php echo htmlspecialchars($job['salary']); ?>">
            </div>

            <div class="mb-3">

                <label class="form-label">Status</label>

                <select name="status" class="form-control">

                    <option value="Open"
                        <?php if($job['status']=="Open") echo "selected"; ?>>
                        Open
                    </option>

                    <option value="Closed"
                        <?php if($job['status']=="Closed") echo "selected"; ?>>
                        Closed
                    </option>

                </select>

            </div>

            <button type="submit"
                    name="update"
                    class="btn btn-primary">
                Update Job
            </button>

            <a href="my_jobs.php"
               class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>