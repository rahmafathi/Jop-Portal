<?php

session_start();

?>
<?php
include_once "../includes/header.php";
include_once '../includes/nav.php';
?>
<link rel="stylesheet" href="../assets/css/profilecompany.css">
<link rel="stylesheet" href="../assets/css/style.css">
<?php
include_once "../includes/db.php";
include_once "../includes/functions.php";

/* التأكد من تسجيل الدخول */

if (!isset($_SESSION['user_id'])) {
    redirect("../login.php");
}

$user_id = $_SESSION['user_id'];


/* جلب بيانات الشركة الخاصة بالمستخدم */

$sql = "SELECT

users.id,
users.name,
users.email,
users.phone,

companies.company_name,
companies.description,
companies.website,
companies.location,
companies.logo

FROM companies

INNER JOIN users
ON companies.user_id = users.id

WHERE companies.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die(mysqli_error($conn));
}

$company = mysqli_fetch_assoc($result);

?>

<div class="container py-5">

    <?php displayMessage(); ?>

    <?php if ($company) { ?>

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card shadow-lg">

                <!-- Header -->

                <div class="profile-header">

                    <?php if (!empty($company['logo'])) { ?>

                        <img src="../uploads/<?php echo $company['logo']; ?>" class="company-logo-img">

                    <?php } else { ?>

                        <div class="company-logo">

                            <?php echo strtoupper(substr($company['company_name'],0,1)); ?>

                        </div>

                    <?php } ?>

                    <h2>

                        <?php echo htmlspecialchars($company['company_name']); ?>

                    </h2>

                    <p>

                        <?php
                        echo !empty($company['description'])
                        ? htmlspecialchars($company['description'])
                        : "No description available.";
                        ?>

                    </p>

                </div>

                <!-- Body -->

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="info-box">

                                <div class="info-title">

                                    <i class="fa-solid fa-envelope"></i>

                                    Email

                                </div>

                                <p>

                                    <?php echo htmlspecialchars($company['email']); ?>

                                </p>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-box">

                                <div class="info-title">

                                    <i class="fa-solid fa-phone"></i>

                                    Phone

                                </div>

                                <p>

                                    <?php echo htmlspecialchars($company['phone']); ?>

                                </p>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-box">

                                <div class="info-title">

                                    <i class="fa-solid fa-location-dot"></i>

                                    Address

                                </div>

                                <p>

                                    <?php

                                    echo !empty($company['location'])

                                    ? htmlspecialchars($company['location'])

                                    : "-";

                                    ?>

                                </p>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="info-box">

                                <div class="info-title">

                                    <i class="fa-solid fa-globe"></i>

                                    Website

                                </div>

                                <p>

                                    <?php if(!empty($company['website'])){ ?>

                                        <a href="<?php echo $company['website']; ?>" target="_blank">

                                            <?php echo $company['website']; ?>

                                        </a>

                                    <?php }else{ ?>

                                        -

                                    <?php } ?>

                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- <div class="text-center mt-4">

                        <a href="edit_profile.php" class="btn btn-primary edit-btn">

                            <i class="fa-solid fa-pen-to-square"></i>

                            Edit Profile

                        </a>

                    </div> -->

                </div>

            </div>

        </div>

    </div>

    <?php } else { ?>

        <div class="alert alert-warning text-center">

            لا توجد بيانات لهذه الشركة.

        </div>

    <?php } ?>

</div>

<?php

include_once "../includes/footer.php";

?>

