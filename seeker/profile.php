<?php
session_start();

// 1. Session Check (الحماية والتأكد من نوع المستخدم)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'job_seeker') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

require_once '../includes/db.php';
include_once '../includes/functions.php';

$success_msg = '';
$error_msg = '';

// 2. جلب بيانات المستخدم الحالية
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc() ?? [];

// 3. معالجة تحديث البيانات عند الحفظ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['full_name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $skills      = trim($_POST['skills'] ?? '');
    $experience  = trim($_POST['experience'] ?? '');
    $education   = trim($_POST['education'] ?? '');
    $new_pass    = $_POST['new_password'] ?? '';

    if (empty($name) || empty($email)) {
        $error_msg = "Full Name and Email are required fields.";
    } else {
        $profile_image = $user['profile_image'] ?? '';
        $cv_file       = $user['cv_file'] ?? '';

        // رفع/تغيير صورة البروفايل
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $img_tmp           = $_FILES['profile_image']['tmp_name'];
            $img_original_name = basename($_FILES['profile_image']['name']);
            $img_ext           = strtolower(pathinfo($img_original_name, PATHINFO_EXTENSION));
            $allowed_img_exts  = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($img_ext, $allowed_img_exts)) {
                $img_name       = time() . '_' . $img_original_name;
                $upload_img_dir = '../uploads/profiles/';
                if (!is_dir($upload_img_dir)) {
                    mkdir($upload_img_dir, 0777, true);
                }
                if (move_uploaded_file($img_tmp, $upload_img_dir . $img_name)) {
                    $profile_image = $img_name;
                }
            } else {
                $error_msg = "Allowed image formats: JPG, JPEG, PNG, WEBP only.";
            }
        }

        // رفع/تحديث الـ CV (PDF فقط)
        if (empty($error_msg) && isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
            $cv_tmp           = $_FILES['cv_file']['tmp_name'];
            $cv_original_name = basename($_FILES['cv_file']['name']);
            $cv_ext           = strtolower(pathinfo($cv_original_name, PATHINFO_EXTENSION));

            if ($cv_ext === 'pdf') {
                $cv_name       = time() . '_' . $cv_original_name;
                $upload_cv_dir = '../uploads/cvs/';
                if (!is_dir($upload_cv_dir)) {
                    mkdir($upload_cv_dir, 0777, true);
                }
                if (move_uploaded_file($cv_tmp, $upload_cv_dir . $cv_name)) {
                    $cv_file = $cv_name;
                }
            } else {
                $error_msg = "CV file must be in PDF format only.";
            }
        }

        // حفظ التعديلات وتحديث كلمة المرور إن وجِدت
        if (empty($error_msg)) {
            if (!empty($new_pass)) {
                $hashed_pass = password_hash($new_pass, PASSWORD_BCRYPT);
                $sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ?, skills = ?, experience = ?, education = ?, profile_image = ?, cv_file = ?, password = ? WHERE id = ?";
                $stmt_update = $conn->prepare($sql);
                $stmt_update->bind_param("ssssssssssi", $name, $email, $phone, $address, $skills, $experience, $education, $profile_image, $cv_file, $hashed_pass, $user_id);
            } else {
                $sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ?, skills = ?, experience = ?, education = ?, profile_image = ?, cv_file = ? WHERE id = ?";
                $stmt_update = $conn->prepare($sql);
                $stmt_update->bind_param("sssssssssi", $name, $email, $phone, $address, $skills, $experience, $education, $profile_image, $cv_file, $user_id);
            }

            if ($stmt_update->execute()) {
                $success_msg = "Profile updated successfully!";
                // إعادة جلب البيانات فوراً
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc() ?? [];
            } else {
                $error_msg = "An error occurred while saving: " . $conn->error;
            }
        }
    }
}

// تجهيز القيم للعرض
$display_name       = $user['name'] ?? '';
$display_email      = $user['email'] ?? '';
$display_phone      = $user['phone'] ?? '';
$display_address    = $user['address'] ?? '';
$display_skills     = $user['skills'] ?? '';
$display_experience = $user['experience'] ?? '';
$display_education  = $user['education'] ?? '';
$display_image      = $user['profile_image'] ?? '';
$display_cv         = $user['cv_file'] ?? '';

// مسار الصورة النسبي بـ ../ للرجوع خطوة من فولدر seeker إلى الجذر
$img_relative_path = '../uploads/profiles/' . $display_image;

if (!empty($display_image) && file_exists(__DIR__ . '/../uploads/profiles/' . $display_image)) {
    $display_img_src = $img_relative_path . '?v=' . time();
} else {
    $display_img_src = 'https://via.placeholder.com/130';
}

include_once "../includes/header.php";
include_once "../includes/nav.php";
?>

<div class="container my-5">
    <div class="card profile-card p-4 bg-white shadow-sm mx-auto" style="max-width: 800px; border-radius: 12px;">
        <h3 class="text-center mb-4 text-primary">Edit Profile</h3>

        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($success_msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error_msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="profile.php" method="POST" enctype="multipart/form-data">
            
            <!-- الدائرة والصورة فوق -->
            <div class="text-center mb-4">
                <img src="<?= $display_img_src ?>" 
                     alt="Profile Picture" class="rounded-circle border border-primary mb-3 shadow-sm" style="width: 130px; height: 130px; object-fit: cover;">
                <div>
                    <label for="profile_image" class="form-label fw-bold">Upload / Change Profile Picture</label>
                    <input type="file" class="form-control w-75 mx-auto" id="profile_image" name="profile_image" accept="image/*">
                </div>
            </div>

            <div class="row g-3">
                <!-- Full Name -->
                <div class="col-md-6">
                    <label for="full_name" class="form-label fw-bold">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($display_name) ?>" required>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label for="email" class="form-label fw-bold">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($display_email) ?>" required>
                </div>

                <!-- Phone -->
                <div class="col-md-6">
                    <label for="phone" class="form-label fw-bold">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($display_phone) ?>">
                </div>

                <!-- Address -->
                <div class="col-md-6">
                    <label for="address" class="form-label fw-bold">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($display_address) ?>">
                </div>

                <!-- Skills -->
                <div class="col-12">
                    <label for="skills" class="form-label fw-bold">Skills</label>
                    <textarea class="form-control" id="skills" name="skills" rows="2" placeholder="e.g. HTML, CSS, JavaScript, PHP..."><?= htmlspecialchars($display_skills) ?></textarea>
                </div>

                <!-- Experience -->
                <div class="col-12">
                    <label for="experience" class="form-label fw-bold">Experience</label>
                    <textarea class="form-control" id="experience" name="experience" rows="3" placeholder="Describe your past work experience..."><?= htmlspecialchars($display_experience) ?></textarea>
                </div>

                <!-- Education -->
                <div class="col-12">
                    <label for="education" class="form-label fw-bold">Education</label>
                    <textarea class="form-control" id="education" name="education" rows="2" placeholder="e.g. Bachelor in Computer Science..."><?= htmlspecialchars($display_education) ?></textarea>
                </div>

                <!-- Change Password -->
                <div class="col-12">
                    <label for="new_password" class="form-label fw-bold">Change Password (leave blank to keep current)</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="******">
                </div>

                <!-- Upload CV / Resume File -->
                <div class="col-12">
                    <label for="cv_file" class="form-label fw-bold">Upload Resume / CV (PDF only)</label>
                    <input type="file" class="form-control" id="cv_file" name="cv_file" accept=".pdf">
                    <?php if (!empty($display_cv)): ?>
                        <div class="form-text mt-1">
                            Current CV: <a href="../uploads/cvs/<?= htmlspecialchars($display_cv) ?>" target="_blank" class="text-decoration-none">View CV</a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Save Changes Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php
include_once "../includes/footer.php";
?>