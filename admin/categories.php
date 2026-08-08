<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

// ====================== ADD CATEGORY ======================

if (isset($_POST['add'])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    if (!empty($category_name) && $conn) {
        mysqli_query($conn, "INSERT INTO categories(category_name) VALUES('$category_name')");
        header("Location: categories.php");
        exit();
    }
}

// ====================== DELETE CATEGORY ======================

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($conn) {
        mysqli_query($conn, "DELETE FROM categories WHERE id=$id");
    }
    header("Location: categories.php");
    exit();
}

// ====================== GET CATEGORY FOR EDIT ======================

$edit = false;
$category = "";

if (isset($_GET['edit']) && $conn) {
    $edit = true;
    $id = intval($_GET['edit']);
    $result_edit = mysqli_query($conn, "SELECT * FROM categories WHERE id=$id");
    if ($result_edit) {
        $category = mysqli_fetch_assoc($result_edit);
    }
}

// ====================== UPDATE CATEGORY ======================

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    if ($conn) {
        mysqli_query($conn, "UPDATE categories SET category_name='$category_name' WHERE id=$id");
    }
    header("Location: categories.php");
    exit();
}

// ====================== SELECT ALL ======================

$result = null;
if ($conn) {
    $result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management | Admin Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/users.css?v=<?php echo time(); ?>">

    <!-- Pro Dashboard Styling & Color Harmony -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
        }

        .table-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(112, 144, 176, 0.08);
        }

        .form-control {
            height: 48px;
            border-radius: 12px;
            border: 1px solid #e0e5f2;
            padding-left: 16px;
            color: #2b3674;
            font-size: 0.95rem;
            background-color: #ffffff;
        }

        .form-control:focus {
            border-color: #4318ff;
            box-shadow: 0 0 0 3px rgba(67, 24, 255, 0.1);
            background-color: #ffffff;
        }

        .table thead th {
            background-color: transparent;
            color: #8f9bba;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.8px;
            border-bottom: 1px solid #edf2f7;
            padding: 16px 20px;
            text-transform: uppercase;
        }

        .table tbody td {
            padding: 18px 20px;
            color: #2b3674;
            border-bottom: 1px solid #f8f9fc;
            font-size: 0.95rem;
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fc;
        }

        .category-icon {
            width: 40px;
            height: 40px;
            background: rgba(67, 24, 255, 0.1);
            color: #4318ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        /* Action Column Control */
        .action-column {
            width: 200px;
            text-align: right;
        }

        /* Buttons Customization */
        .btn-add {
            background-color: #4318ff;
            border: none;
            color: #ffffff;
            font-weight: 600;
            border-radius: 12px;
            height: 48px;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            background-color: #3311db;
            color: #ffffff;
            box-shadow: 0px 5px 15px rgba(67, 24, 255, 0.3);
        }

        .btn-update {
            background-color: #ffb547;
            border: none;
            color: #ffffff;
            font-weight: 600;
            border-radius: 12px;
            height: 48px;
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            background-color: #f99b0c;
            color: #ffffff;
            box-shadow: 0px 5px 15px rgba(255, 181, 71, 0.3);
        }

        .btn-action-edit {
            background: rgba(255, 181, 71, 0.1);
            color: #f99b0c;
            border: none;
            border-radius: 10px;
            padding: 6px 14px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-action-edit:hover {
            background: #f99b0c;
            color: #ffffff;
        }

        .btn-action-delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: none;
            border-radius: 10px;
            padding: 6px 14px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-action-delete:hover {
            background: #ef4444;
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">

        <?php include 'sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="color: #2b3674; font-size: 1.75rem;">Categories Management</h3>
                <p class="text-muted small mb-0" style="font-size: 0.9rem;">Manage all job categories cleanly and efficiently</p>
            </div>

            <!-- Add / Edit Form Card -->
            <div class="table-card p-4 mb-4">
                <?php if($edit && $category){ ?>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $category['id']; ?>">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <input type="text" name="category_name" class="form-control" value="<?= htmlspecialchars($category['category_name']); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-update w-100 shadow-sm" name="update">
                                <i class="fa fa-edit me-1"></i> Update Category
                            </button>
                        </div>
                    </div>
                </form>
                <?php } else { ?>
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <input type="text" name="category_name" class="form-control" placeholder="Enter Category Name..." required>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-add w-100 shadow-sm" name="add">
                                <i class="fa fa-plus me-1"></i> Add Category
                            </button>
                        </div>
                    </div>
                </form>
                <?php } ?>
            </div>

            <!-- Categories Table Card -->
            <div class="table-card p-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="90">#ID</th>
                                <th>Category Name</th>
                                <th>Created At</th>
                                <th class="action-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($result && mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)){ 
                            ?>
                            <tr>
                                <td class="text-muted fw-bold">#<?= $row['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="category-icon"><i class="fa-solid fa-layer-group"></i></div>
                                        <span class="fw-bold" style="color: #2b3674; font-size: 1rem;"><?= htmlspecialchars($row['category_name']); ?></span>
                                    </div>
                                </td>
                                <td class="text-secondary fw-medium"><?= date("d M Y - h:i A", strtotime($row['created_at'])); ?></td>
                                <td class="action-column">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="categories.php?edit=<?= $row['id']; ?>" class="btn btn-action-edit">
                                            <i class="fa fa-edit me-1"></i> Edit
                                        </a>
                                        <a href="categories.php?delete=<?= $row['id']; ?>" class="btn btn-action-delete" onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="fa fa-trash-can me-1"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else { 
                            ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fas fa-layer-group fa-3x mb-3 opacity-50 text-primary"></i>
                                    <h5 class="fw-bold text-dark">No Categories Found</h5>
                                    <p class="text-muted mb-0">Start by adding a new job category using the form above.</p>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>