<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../includes/header.php";
include_once '../includes/nav-company.php';
include_once "../includes/db.php";
include_once "../includes/functions.php";

/* Ensure user is logged in */
if (!isset($_SESSION['user_id'])) {
    redirect("../login.php");
}

$user_id = intval($_SESSION['user_id']);

/* Fetch company data for the logged-in user */
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

<!-- FontAwesome & Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../assets/css/profilecompany.css">
<link rel="stylesheet" href="../assets/css/style.css">

<div class="container py-5" dir="ltr">

    <?php displayMessage(); ?>

    <?php if ($company) { ?>

    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="card card-custom-profile shadow-lg">

                <!-- Header -->
                <div class="profile-header">

                    <?php if (!empty($company['logo'])) { ?>
                        <img src="../uploads/<?php echo htmlspecialchars($company['logo']); ?>" class="company-logo-img">
                    <?php } else { ?>
                        <div class="company-logo">
                            <?php echo strtoupper(substr($company['company_name'], 0, 1)); ?>
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
                <div class="card-body p-4 p-md-5">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-title">
                                    <i class="fa-solid fa-envelope"></i> Email
                                </div>
                                <p class="mb-0">
                                    <?php echo htmlspecialchars($company['email']); ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-title">
                                    <i class="fa-solid fa-phone"></i> Phone
                                </div>
                                <p class="mb-0">
                                    <?php echo htmlspecialchars($company['phone']); ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-title">
                                    <i class="fa-solid fa-location-dot"></i> Address
                                </div>
                                <p class="mb-0">
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
                                    <i class="fa-solid fa-globe"></i> Website
                                </div>
                                <p class="mb-0">
                                    <?php if(!empty($company['website'])){ ?>
                                        <a href="<?php echo htmlspecialchars($company['website']); ?>" target="_blank" class="text-info text-decoration-none">
                                            <?php echo htmlspecialchars($company['website']); ?>
                                        </a>
                                    <?php }else{ ?>
                                        -
                                    <?php } ?>
                                </p>
                            </div>
                        </div>

                    </div>

                    <!-- Edit Profile Button (Uncomment when needed) -->
                    
                    <div class="text-center mt-4">
                        <a href="edit_profile.php" class="btn btn-neon-primary edit-btn">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                        </a>
                    </div> 
                

                </div>

            </div>

        </div>
    </div>

    <?php } else { ?>
        <div class="alert custom-alert-danger text-center">
            No data available for this company.
        </div>
    <?php } ?>

</div>

<?php
include_once "../includes/footer.php";
?>