<?php

include '../includes/db.php';

// ====================== ADD CATEGORY ======================

if (isset($_POST['add'])) {

    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    if (!empty($category_name)) {

        mysqli_query($conn, "INSERT INTO categories(category_name)
                             VALUES('$category_name')");

        header("Location: categories.php");
        exit();
    }
}

// ====================== DELETE CATEGORY ======================

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM categories WHERE id=$id");

    header("Location: categories.php");
    exit();
}

// ====================== GET CATEGORY FOR EDIT ======================

$edit = false;
$category = "";

if (isset($_GET['edit'])) {

    $edit = true;

    $id = intval($_GET['edit']);

    $result = mysqli_query($conn, "SELECT * FROM categories WHERE id=$id");

    $category = mysqli_fetch_assoc($result);
}

// ====================== UPDATE CATEGORY ======================

if (isset($_POST['update'])) {

    $id = intval($_POST['id']);

    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    mysqli_query($conn, "UPDATE categories
                         SET category_name='$category_name'
                         WHERE id=$id");

    header("Location: categories.php");
    exit();
}

// ====================== SELECT ALL ======================

$result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Categories</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet"
href="../assets/css/users.css?v=<?php echo time(); ?>">

</head>

<body>

<div class="container-fluid p-0">

<div class="row g-0">

<?php include 'sidebar.php'; ?>

<div class="col-md-10 p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3 class="fw-bold mb-1">Categories</h3>

<p class="text-muted">
Manage all job categories
</p>

</div>

</div>

<div class="table-card p-4 mb-4">

<?php if($edit){ ?>

<form method="POST">

<input type="hidden" name="id"
value="<?= $category['id']; ?>">

<div class="row">

<div class="col-md-9">

<input
type="text"
name="category_name"
class="form-control"
value="<?= htmlspecialchars($category['category_name']); ?>"
required>

</div>

<div class="col-md-3">

<button
class="btn btn-warning w-100"
name="update">

<i class="fa fa-edit"></i>

Update

</button>

</div>

</div>

</form>

<?php } else { ?>

<form method="POST">

<div class="row">

<div class="col-md-9">

<input
type="text"
name="category_name"
class="form-control"
placeholder="Enter Category Name"
required>

</div>

<div class="col-md-3">

<button
class="btn btn-primary w-100"
name="add">

<i class="fa fa-plus"></i>

Add Category

</button>

</div>

</div>

</form>

<?php } ?>

</div>

<div class="table-card p-4">

<div class="table-responsive">

<table class="table align-middle">

<thead>

<tr>

<th>ID</th>

<th>Category Name</th>

<th>Created At</th>

<th width="180">Actions</th>

</tr>

</thead>

<tbody>
    <?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

    <td><?= $row['id']; ?></td>

    <td><?= htmlspecialchars($row['category_name']); ?></td>

    <td><?= $row['created_at']; ?></td>

    <td>

        <a href="categories.php?edit=<?= $row['id']; ?>"
           class="btn btn-warning btn-sm">

            <i class="fa fa-edit"></i>

        </a>

        <a href="categories.php?delete=<?= $row['id']; ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Are you sure you want to delete this category?')">

            <i class="fa fa-trash"></i>

        </a>

    </td>

</tr>

<?php } ?>

<?php if(mysqli_num_rows($result) == 0){ ?>

<tr>

    <td colspan="4" class="text-center py-4">

        No Categories Found

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