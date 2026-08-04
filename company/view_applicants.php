<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
include_once '../includes/header.php';
include_once '../includes/nav.php';


$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;


// جلب عنوان الوظيفة
$job_query = "SELECT title FROM jobs WHERE id = $job_id";

$job_res = mysqli_query($conn, $job_query);

$job_title = ($job_res && mysqli_num_rows($job_res) > 0)
    ? mysqli_fetch_assoc($job_res)['title']
    : 'Job';




// جلب المتقدمين للوظيفة
$app_query = "
    SELECT 
        users.name,
        users.email,
        application.applied_at,
        application.id AS application_id

    FROM application

    INNER JOIN users
    ON application.seeker_id = users.id

    WHERE application.job_id = $job_id
";


$app_res = mysqli_query($conn, $app_query);

?>


<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="page-title mb-1">
                Applicants for:
                <span class="text-primary">
                    <?php echo htmlspecialchars($job_title); ?>
                </span>
            </h2>

            <p class="text-muted">
                Review candidates who applied for this position.
            </p>

        </div>


        <a href="my_jobs.php" class="btn btn-secondary">
            Back to My Jobs
        </a>

    </div>




    <div class="card shadow-sm border-0 p-4">

        <div class="table-responsive">

            <table class="table align-middle table-hover mb-0">


                <thead class="table-light">

                    <tr>

                        <th>Applicant Name</th>

                        <th>Email</th>

                        <th>Applied Date</th>

                        <th class="text-end">Actions</th>

                    </tr>

                </thead>



                <tbody>


                <?php if ($app_res && mysqli_num_rows($app_res) > 0): ?>


                    <?php while ($app = mysqli_fetch_assoc($app_res)): ?>


                        <tr>


                            <td class="fw-bold">

                                <?php
                                echo htmlspecialchars($app['name']);
                                ?>

                            </td>



                            <td>

                                <?php echo htmlspecialchars($app['email']); ?>

                            </td>



                            <td>

                                <?php echo htmlspecialchars($app['applied_at']); ?>

                            </td>



                            <td class="text-end">

                                <a href="view_application.php?id=<?php echo $app['application_id']; ?>"
                                   class="btn btn-sm btn-outline-primary">

                                    View

                                </a>

                            </td>


                        </tr>


                    <?php endwhile; ?>



                <?php else: ?>


                    <tr>

                        <td colspan="4" class="text-center py-5 text-muted">

                            No applicants have applied for this job yet.

                        </td>

                    </tr>



                <?php endif; ?>


                </tbody>


            </table>

        </div>

    </div>

</div>



<?php include_once '../includes/footer.php'; ?>