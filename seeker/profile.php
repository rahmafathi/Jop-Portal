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
$stmt = mysqli_prepare($conn,"SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);   
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user =mysqli_fetch_assoc($result) ?? [];

// 3. معالجة تحديث البيانات عند الحفظ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['full_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $address    = trim($_POST['address'] ?? '');
    $skills     = trim($_POST['skills'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $education  = trim($_POST['education'] ?? '');
    $new_pass   = $_POST['new_password'] ?? '';

    if (empty($name) || empty($email)) {
        $error_msg = "Full Name and Email are required fields.";
    } else {
        $profile_image = $user['profile_image'] ?? '';
        $cv_file       = $user['cv_file'] ?? '';

        // رفع/تغيير صورة البروفايل
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $img_tmp   = $_FILES['profile_image']['tmp_name'];
            $img_ext   = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $allowed   = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($img_ext, $allowed)) {
                $upload_img_dir = __DIR__ . '/../uploads/profiles/';
                if (!is_dir($upload_img_dir)) {
                    mkdir($upload_img_dir, 0777, true);
                }
                
                // حذف الصورة القديمة إذا وجدت لتوفير مساحة السيرفر
                if (!empty($user['profile_image']) && file_exists($upload_img_dir . $user['profile_image'])) {
                    unlink($upload_img_dir . $user['profile_image']);
                }

                // اسم فريد تماماً لمنع التضارب
                $img_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $img_ext;
                
                if (move_uploaded_file($img_tmp, $upload_img_dir . $img_name)) {
                    $profile_image = $img_name;
                }
            } else {
                $error_msg = "Allowed image formats: JPG, JPEG, PNG, WEBP only.";
            }
        }

        // رفع/تحديث الـ CV (PDF فقط)
        if (empty($error_msg) && isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
            $cv_tmp = $_FILES['cv_file']['tmp_name'];
            $cv_ext = strtolower(pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION));

            if ($cv_ext === 'pdf') {
                $upload_cv_dir = __DIR__ . '/../uploads/cvs/';
                if (!is_dir($upload_cv_dir)) {
                    mkdir($upload_cv_dir, 0777, true);
                }

                // حذف ملف الـ CV القديم إذا وجد لتوفير مساحة السيرفر
                if (!empty($user['cv_file']) && file_exists($upload_cv_dir . $user['cv_file'])) {
                    unlink($upload_cv_dir . $user['cv_file']);
                }

                // اسم فريد تماماً لمنع التضارب
                $cv_name = time() . '_' . bin2hex(random_bytes(4)) . '.pdf';

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

// مسار الصورة النسبي مع التحقق عبر __DIR__
if (!empty($display_image) && file_exists(__DIR__ . '/../uploads/profiles/' . $display_image)) {
    $display_img_src = '../uploads/profiles/' . $display_image . '?v=' . time();
} else {
    $display_img_src = 'https://via.placeholder.com/130';
}

include_once "../includes/header.php";
include_once "../includes/nav-seeker.php";
?>

<!-- FontAwesome & Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../assets/css/profilecompany.css">
<link rel="stylesheet" href="../assets/css/style.css">

<!-- =====================================================
     Ultra Pro Dark & Neon Web Theme (Zozo Style)
===================================================== -->
<style>
    :root {
        --bg-main: #0b0f19;
        --card-bg: #111827;
        --border-color: rgba(255, 255, 255, 0.07);
        --primary-neon: #38bdf8;
        --accent-glow: rgba(56, 189, 248, 0.25);
        --text-main: #f3f4f6;
        --text-muted: #9ca3af;
    }

    body {
        background-color: var(--bg-main) !important;
        color: var(--text-main) !important;
    }

    .profile-card-custom {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5) !important;
        overflow: hidden;
        position: relative;
    }

    .profile-card-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #0284c7, var(--primary-neon));
    }

    .profile-header-custom {
        background: linear-gradient(135deg, rgba(17, 24, 39, 0.95), rgba(30, 41, 59, 0.95));
        color: white;
        padding: 35px 20px;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        margin: -48px -48px 35px -48px;
    }

    .form-control, .form-select {
        background-color: #0b0f19 !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 12px !important;
        padding: 13px 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: var(--text-main) !important;
        font-size: 0.95rem;
    }

    .form-control::placeholder {
        color: #4b5563 !important;
    }

    .form-control:focus {
        border-color: var(--primary-neon) !important;
        background-color: #0b0f19 !important;
        box-shadow: 0 0 15px var(--accent-glow) !important;
        color: var(--text-main) !important;
    }

    .form-label {
        font-weight: 600;
        color: #e5e7eb !important;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-label i {
        color: var(--primary-neon) !important;
        margin-left: 6px;
    }

    .btn-save-custom {
        background: linear-gradient(135deg, #0284c7, var(--primary-neon));
        border: none;
        color: #0b0f19;
        padding: 13px 45px;
        border-radius: 12px;
        font-size: 1.05rem;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
    }

    .btn-save-custom:hover {
        background: linear-gradient(135deg, #0369a1, #0284c7);
        box-shadow: 0 0 30px rgba(56, 189, 248, 0.6);
        transform: translateY(-2px);
        color: #ffffff;
    }

    .profile-img-preview {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid var(--primary-neon);
        box-shadow: 0 0 25px var(--accent-glow);
        background: #0b0f19;
    }

    .alert {
        background-color: rgba(17, 24, 39, 0.9) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-main) !important;
        border-radius: 12px !important;
    }

    .alert-success {
        border-color: rgba(34, 197, 94, 0.4) !important;
        color: #4ade80 !important;
    }

    .alert-danger {
        border-color: rgba(239, 68, 68, 0.4) !important;
        color: #f87171 !important;
    }

    .form-text {
        color: var(--text-muted) !important;
    }

    @media (max-width: 768px) {
        .profile-header-custom {
            margin: -32px -32px 25px -32px;
        }
    }
</style>
<!-- ===================================================== -->

<div class="container py-5" dir="ltr">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="card profile-card-custom p-4 p-md-5">

                <!-- Header -->
                <div class="profile-header-custom">
                    <h2 class="fw-bold mb-1" style="color: #f3f4f6;">
                        <i class="fa-solid fa-user-pen me-2" style="color: var(--primary-neon);"></i> Edit Profile
                    </h2>
                    <p class="text-muted mb-0">Update your professional information and credentials</p>
                </div>

                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> <?= htmlspecialchars($success_msg) ?>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= htmlspecialchars($error_msg) ?>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="profile.php" method="POST" enctype="multipart/form-data">
                    
                    <!-- صورة البروفايل -->
                    <div class="text-center mb-4">
                        <img src="<?= $display_img_src ?>" alt="Profile Picture" class="profile-img-preview mb-3">
                        <div>
                            <label for="profile_image" class="form-label">
                                <i class="fa-solid fa-camera"></i> Upload / Change Profile Picture
                            </label>
                            <input type="file" class="form-control w-75 mx-auto" id="profile_image" name="profile_image" accept="image/*">
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">
                                <i class="fa-solid fa-user"></i> Full Name
                            </label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($display_name) ?>" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="fa-solid fa-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($display_email) ?>" required>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label">
                                <i class="fa-solid fa-phone"></i> Phone Number
                            </label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($display_phone) ?>">
                        </div>

                        <!-- Address -->
                        <div class="col-md-6">
                            <label for="address" class="form-label">
                                <i class="fa-solid fa-location-dot"></i> Address
                            </label>
                            <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($display_address) ?>">
                        </div>

                        <!-- Skills -->
                        <div class="col-12">
                            <label for="skills" class="form-label">
                                <i class="fa-solid fa-code"></i> Skills
                            </label>
                            <textarea class="form-control" id="skills" name="skills" rows="2" placeholder="e.g. HTML, CSS, JavaScript, PHP..."><?= htmlspecialchars($display_skills) ?></textarea>
                        </div>

                        <!-- Experience -->
                        <div class="col-12">
                            <label for="experience" class="form-label">
                                <i class="fa-solid fa-briefcase"></i> Experience
                            </label>
                            <textarea class="form-control" id="experience" name="experience" rows="3" placeholder="Describe your past work experience..."><?= htmlspecialchars($display_experience) ?></textarea>
                        </div>

                        <!-- Education -->
                        <div class="col-12">
                            <label for="education" class="form-label">
                                <i class="fa-solid fa-graduation-cap"></i> Education
                            </label>
                            <textarea class="form-control" id="education" name="education" rows="2" placeholder="e.g. Bachelor in Computer Science..."><?= htmlspecialchars($display_education) ?></textarea>
                        </div>

                        <!-- Change Password -->
                        <div class="col-12">
                            <label for="new_password" class="form-label">
                                <i class="fa-solid fa-lock"></i> Change Password (leave blank to keep current)
                            </label>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="******">
                        </div>

                        <!-- Upload CV / Resume File -->
<!-- Upload CV / Resume File -->
<div class="col-12">
    <label for="cv_file" class="form-label">
        <i class="fa-solid fa-file-pdf"></i> Upload Resume / CV (PDF only)
    </label>
    <input type="file" class="form-control" id="cv_file" name="cv_file" accept=".pdf">
    
    <?php if (!empty($display_cv)): ?>
        <?php 
            // التحقق من وجود الملف فعلياً على السيرفر لمنع مشكلة File Missing
            $cv_physical_path = __DIR__ . '/../uploads/cvs/' . $display_cv;
        ?>
        <div class="form-text mt-2">
            <?php if (file_exists($cv_physical_path)): ?>
                Current CV: <a href="../uploads/cvs/<?= htmlspecialchars($display_cv) ?>" target="_blank" class="text-decoration-none fw-semibold" style="color: var(--primary-neon);">View Current CV</a>
            <?php else: ?>
                <span class="text-danger fw-semibold">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> File Missing (Please re-upload your CV)
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

                    </div>

                    <!-- Save Changes Button -->
                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-save-custom">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Save Changes
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<?php
include_once "../includes/footer.php";
?>