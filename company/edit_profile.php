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

// Fetch current company and user data
$sql = "SELECT users.name, users.email, users.phone, 
               companies.company_name, companies.description, companies.website, companies.location, companies.logo 
        FROM companies 
        INNER JOIN users ON companies.user_id = users.id 
        WHERE companies.user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$company = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);

    // Handle logo upload if a new file is provided
    $logo_sql_part = "";
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $logo_name = time() . '_' . basename($_FILES['logo']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $logo_name;
        
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            $logo_sql_part = ", logo = '$logo_name'";
        }
    }

    // Update users table (phone)
    mysqli_query($conn, "UPDATE users SET phone = '$phone' WHERE id = '$user_id'");

    // Update companies table
    $update_company = "UPDATE companies SET 
                        company_name = '$company_name', 
                        description = '$description', 
                        location = '$location', 
                        website = '$website' 
                        $logo_sql_part 
                        WHERE user_id = '$user_id'";

    if (mysqli_query($conn, $update_company)) {
        $_SESSION['message'] = "Profile updated successfully!";
        $_SESSION['msg_type'] = "success";
        redirect("profile.php");
    } else {
        $error = "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!-- FontAwesome & Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body {
    background-color: #030712 !important;
    color: #ffffff !important;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}
.card-custom {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 20px !important;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
    backdrop-filter: blur(20px);
}
.form-control, .form-control:focus {
    background: rgba(30, 41, 59, 0.6) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
    border-radius: 10px;
    padding: 12px;
}
.form-control:focus {
    border-color: #00d2ff !important;
    box-shadow: 0 0 10px rgba(0, 210, 255, 0.2);
}
.form-label {
    color: #cbd5e1;
    font-weight: 600;
    margin-bottom: 6px;
}
.btn-neon-primary {
    background: linear-gradient(135deg, #0d6efd, #00d2ff) !important;
    color: #ffffff !important;
    font-weight: 700;
    border: none !important;
    border-radius: 30px !important;
    padding: 12px 30px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 210, 255, 0.3);
}
.btn-neon-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 210, 255, 0.5);
}
</style>

<div class="container py-5" dir="ltr">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom p-4 p-md-5">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold text-white mb-0"><i class="fas fa-user-edit text-info me-2"></i> Edit Company Profile</h3>
                    <a href="profile.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger bg-danger text-white border-0 rounded-3 mb-4"><?php echo $error; ?></div>
                <?php endif; ?>

               <div class="mb-3">
    <label class="form-label">Company Name</label>
    <input type="text" name="company_name" class="form-control"
        value="<?php echo htmlspecialchars($company['company_name']); ?>" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control"
            value="<?php echo htmlspecialchars($company['phone']); ?>" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Website</label>
        <input type="text" name="website" class="form-control"
            value="<?php echo htmlspecialchars($company['website']); ?>">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Address / Location</label>
    <input type="text" name="location" class="form-control"
        value="<?php echo htmlspecialchars($company['location']); ?>">
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="4"
        class="form-control"><?php echo htmlspecialchars($company['description']); ?></textarea>
</div>

<!-- Company Logo -->
<div class="mb-4">

    <label class="form-label">Company Logo</label>

    <div class="text-center mb-3">

        <?php if (!empty($company['logo'])) : ?>

            <img src="../uploads/profile/<?php echo $company['logo']; ?>"
                 class="rounded-circle border"
                 width="120"
                 height="120"
                 style="object-fit:cover;">

        <?php else : ?>

            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                 style="width:120px;height:120px;background:#0d6efd;color:#fff;font-size:48px;font-weight:bold;">

                <?php echo strtoupper(substr($company['company_name'], 0, 1)); ?>

            </div>

        <?php endif; ?>

    </div>

    <input type="file" name="logo" class="form-control">

    <small class="text-muted">
        Leave blank if you don't want to change the logo.
    </small>

</div>

<div class="text-center">
    <button type="submit" class="btn btn-neon-primary w-100 py-3">
        <i class="fas fa-save me-2"></i> Save Changes
    </button>
</div>

            </div>
        </div>
    </div>
</div>

<?php include_once "../includes/footer.php"; ?>