<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'job_seeker') {
    header("Location: ../login.php");
    exit();
}

include_once "../includes/db.php";
include_once "../includes/functions.php";
include_once "../includes/header.php";
include_once "../includes/nav-seeker.php";

/* ===========================
   Search & Filters
=========================== */

$search = "";
$category = "";
$location = "";
$job_type = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if(isset($_GET['category'])){
    $category = mysqli_real_escape_string($conn, $_GET['category']);
}

if(isset($_GET['location'])){
    $location = mysqli_real_escape_string($conn, $_GET['location']);
}

if(isset($_GET['job_type'])){
    $job_type = mysqli_real_escape_string($conn, $_GET['job_type']);
}

/* ===========================
   SQL
=========================== */

$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT jobs.*, companies.company_name, categories.category_name
FROM jobs
JOIN companies ON jobs.company_id = companies.id
JOIN categories ON jobs.category_id = categories.id
WHERE 1";

if($search != ""){
    $sql .= " AND (jobs.title LIKE '%$search%' OR companies.company_name LIKE '%$search%')";
}

if($category != ""){
    $sql .= " AND jobs.category_id='$category'";
}

if($location != ""){
    $sql .= " AND jobs.location='$location'";
}

if($job_type != ""){
    $sql .= " AND jobs.job_type='$job_type'";
}

$sql .= " ORDER BY jobs.created_at DESC LIMIT $start, $limit";

$result = mysqli_query($conn, $sql);

// جلب الوظائف المحفوظة للمستخدم الحالي عشان نعرف إيه اللي متحفظ ومكتوب جنبه Saved
$userId = $_SESSION['user_id'];
$saved_query = mysqli_query($conn, "SELECT job_id FROM saved_jobs WHERE seeker_id = $userId");
$saved_jobs_arr = [];
while($row = mysqli_fetch_assoc($saved_query)){
    $saved_jobs_arr[] = $row['job_id'];
}
?>

<!-- Professional UI Styling -->
<style>
    :root {
        --bg-main: #0b0f19;
        --card-bg: rgba(17, 24, 39, 0.85);
        --border-color: rgba(255, 255, 255, 0.08);
        --primary-neon: #38bdf8;
        --accent-glow: rgba(56, 189, 248, 0.15);
        --text-main: #f3f4f6;
        --text-muted: #9ca3af;
    }

    body {
        background-color: var(--bg-main) !important;
        color: var(--text-main) !important;
    }

    .jobs-page-wrapper {
        max-width: 1200px;
        margin: 45px auto;
        padding: 0 20px;
    }

    .page-main-title {
        font-size: 30px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 25px;
        letter-spacing: 0.5px;
        text-shadow: 0 0 15px rgba(56, 189, 248, 0.25);
    }

    /* Filter Card */
    .pro-filter-card {
        background: var(--card-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 35px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.45), 0 0 20px var(--accent-glow);
    }

    .pro-filter-card .form-control, 
    .pro-filter-card .form-select {
        background-color: rgba(11, 15, 25, 0.9);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .pro-filter-card .form-control:focus, 
    .pro-filter-card .form-select:focus {
        background-color: rgba(11, 15, 25, 1);
        border-color: var(--primary-neon);
        color: var(--text-main);
        box-shadow: 0 0 12px rgba(56, 189, 248, 0.35);
    }

    .pro-filter-card .form-select option {
        background-color: #0b0f19;
        color: var(--text-main);
    }

    /* Job Card Design */
    .pro-job-card {
        background: var(--card-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }

    .pro-job-card:hover {
        border-color: rgba(56, 189, 248, 0.35);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.65), 0 0 25px rgba(56, 189, 248, 0.2);
        transform: translateY(-4px);
    }

    .pro-job-card .card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .job-title-top {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
    }

    .job-company-top {
        font-size: 14px;
        color: var(--primary-neon) !important;
        font-weight: 600;
        margin-bottom: 4px;
        letter-spacing: 0.3px;
    }

    .job-info-row {
        font-size: 13.5px;
        color: var(--text-muted);
        margin: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        padding-bottom: 6px;
    }

    .job-info-row strong {
        color: var(--text-main);
        font-weight: 500;
    }

    /* Action Buttons Area */
    .job-buttons-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-top: 10px;
    }

    .pro-btn {
        padding: 8px 6px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        cursor: pointer;
    }

    .pro-btn-view {
        background: rgba(56, 189, 248, 0.1);
        color: var(--primary-neon);
        border: 1px solid rgba(56, 189, 248, 0.3);
    }
    .pro-btn-view:hover {
        background: var(--primary-neon);
        color: #0b0f19;
        box-shadow: 0 0 12px rgba(56, 189, 248, 0.5);
    }

    .pro-btn-apply {
        background: rgba(52, 211, 153, 0.1);
        color: #34d399;
        border: 1px solid rgba(52, 211, 153, 0.3);
    }
    .pro-btn-apply:hover {
        background: #34d399;
        color: #0b0f19;
        box-shadow: 0 0 12px rgba(52, 211, 153, 0.5);
    }

    .pro-btn-save {
        background: rgba(251, 191, 36, 0.1);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }
    .pro-btn-save.saved {
        background: #fbbf24;
        color: #0b0f19;
        box-shadow: 0 0 12px rgba(251, 191, 36, 0.5);
    }
    .pro-btn-save:hover {
        background: #fbbf24;
        color: #0b0f19;
        box-shadow: 0 0 12px rgba(251, 191, 36, 0.5);
    }

    .pro-search-btn {
        background: linear-gradient(135deg, #0284c7, #38bdf8);
        color: #ffffff;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
        transition: all 0.3s ease;
    }
    .pro-search-btn:hover {
        background: linear-gradient(135deg, #0369a1, #0284c7);
        box-shadow: 0 0 22px rgba(56, 189, 248, 0.7);
        color: #fff;
    }

    /* Pagination */
    .pagination .page-item .page-link {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        padding: 9px 16px;
        margin: 0 4px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #0284c7, #38bdf8);
        border-color: var(--primary-neon);
        box-shadow: 0 0 12px rgba(56, 189, 248, 0.5);
        color: #fff;
    }

    .pagination .page-item .page-link:hover {
        background-color: rgba(56, 189, 248, 0.15);
        border-color: var(--primary-neon);
        color: var(--primary-neon);
    }

    .pro-alert-box {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--primary-neon);
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        font-size: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
</style>

<div class="jobs-page-wrapper">
    <h2 class="page-main-title">Available Jobs</h2>

    <!-- Search + Filter -->
    <div class="pro-filter-card">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search keywords..." value="<?= htmlspecialchars($search); ?>">
            </div>

            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php
                    $cat = mysqli_query($conn, "SELECT * FROM categories");
                    while($row = mysqli_fetch_assoc($cat)){
                    ?>
                    <option value="<?= $row['id']; ?>" <?= ($category == $row['id']) ? "selected" : ""; ?>>
                        <?= $row['category_name']; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-2">
                <input type="text" name="location" class="form-control" placeholder="Location" value="<?= htmlspecialchars($location); ?>">
            </div>

            <div class="col-md-2">
                <select name="job_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="full time" <?= ($job_type == "full time") ? "selected" : ""; ?>>Full Time</option>
                    <option value="part time" <?= ($job_type == "part time") ? "selected" : ""; ?>>Part Time</option>
                    <option value="remote" <?= ($job_type == "remote") ? "selected" : ""; ?>>Remote</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn pro-search-btn w-100 h-100">Search</button>
            </div>
        </form>
    </div>

    <!-- Jobs Grid -->
    <div class="row">
        <?php
        if(mysqli_num_rows($result) > 0){
            while($job = mysqli_fetch_assoc($result)){
                $isSaved = in_array($job['id'], $saved_jobs_arr);
        ?>
        <div class="col-md-4 mb-4">
            <div class="pro-job-card">
                <div class="card-body">
                    <div>
                        <h4 class="job-title-top"><?= htmlspecialchars($job['title']); ?></h4>
                        <h6 class="job-company-top"><?= htmlspecialchars($job['company_name']); ?></h6>
                    </div>
                    
                    <div class="job-info-row"><span>Category:</span> <strong><?= htmlspecialchars($job['category_name']); ?></strong></div>
                    <div class="job-info-row"><span>Location:</span> <strong><?= htmlspecialchars($job['location']); ?></strong></div>
                    <div class="job-info-row"><span>Salary:</span> <strong><?= htmlspecialchars($job['salary']); ?></strong></div>
                    <div class="job-info-row"><span>Job Type:</span> <strong><?= htmlspecialchars($job['job_type']); ?></strong></div>
                    <div class="job-info-row" style="border-bottom: none;"><span>Posted:</span> <strong><?= date("d M Y", strtotime($job['created_at'])); ?></strong></div>

                    <div class="job-buttons-group">
                        <a href="job_details.php?id=<?= $job['id']; ?>" class="pro-btn pro-btn-view">View</a>
                        <a href="apply_job.php?id=<?= $job['id']; ?>" class="pro-btn pro-btn-apply">Apply</a>
                        <button type="button" class="pro-btn pro-btn-save toggle-save-btn <?= $isSaved ? 'saved' : ''; ?>" data-job-id="<?= $job['id']; ?>">
                            <?= $isSaved ? 'Saved' : 'Save'; ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<div class='col-12'><div class='pro-alert-box'>No Jobs Available at the moment.</div></div>";
        }
        ?>
    </div>
</div>

<?php
$count = mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs");
$total = mysqli_fetch_assoc($count);
$pages = ceil($total['total'] / $limit);
?>

<nav class="mt-4 mb-5">
    <ul class="pagination justify-content-center">
        <?php for($i = 1; $i <= $pages; $i++){ ?>
        <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
            <a class="page-link" href="?page=<?= $i; ?><?= !empty($search) ? '&search='.urlencode($search) : ''; ?><?= !empty($category) ? '&category='.urlencode($category) : ''; ?><?= !empty($location) ? '&location='.urlencode($location) : ''; ?><?= !empty($job_type) ? '&job_type='.urlencode($job_type) : ''; ?>"><?= $i; ?></a>
        </li>
        <?php } ?>
    </ul>
</nav>

<!-- JavaScript for Instant AJAX Save/Unsave -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveButtons = document.querySelectorAll('.toggle-save-btn');

    saveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const jobId = this.getAttribute('data-job-id');
            const btn = this;

            // إرسال طلب في الخلفية بدون إعادة تحميل الصفحة
            fetch('toggle_save.php?job_id=' + jobId)
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'saved') {
                        btn.classList.add('saved');
                        btn.textContent = 'Saved';
                    } else if(data.status === 'removed') {
                        btn.classList.remove('saved');
                        btn.textContent = 'Save';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
});
</script>

<?php
include_once "../includes/footer.php";
?>