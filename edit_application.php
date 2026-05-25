<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: applications.php");
    exit;
}

$id = intval($_GET['id']);

/* LIST DATA */
$students = mysqli_query($conn, "SELECT * FROM students");
$companies = mysqli_query($conn, "SELECT * FROM companies");

/* MAIN DATA */
$get = mysqli_query($conn, "SELECT * FROM applications WHERE id='$id'");
$row = mysqli_fetch_assoc($get);

if(!$row){
    die("Application not found");
}

/* UPDATE */
if(isset($_POST['update_application'])){

    $student_id = intval($_POST['student_id']);
    $company_id = intval($_POST['company_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    mysqli_query($conn, "
        UPDATE applications SET
        student_id='$student_id',
        company_id='$company_id',
        status='$status',
        remarks='$remarks'
        WHERE id='$id'
    ");

    header("Location: applications.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Application | InternTrack Pro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CONSISTENT UI -->
<link rel="stylesheet" href="assets/css/sidebar.css">
<link rel="stylesheet" href="assets/css/dashboard.css">

</head>

<body>

<!-- SIDEBAR -->
<?php include "includes/sidebar.php"; ?>

<div id="overlay"></div>

<div class="main-content">

    <!-- TOPBAR (same style as others) -->
    <div class="topbar box">

        <div>
            <h4 class="fw-bold mb-1">Edit Application</h4>
            <small class="text-muted">Update internship application</small>
        </div>

        <a href="applications.php" class="btn btn-dark px-4 py-2">
            ← Back
        </a>

    </div>

    <!-- FORM -->
    <div class="box mt-4">

        <form method="POST">

            <!-- STUDENT -->
            <div class="mb-3">
                <label class="form-label">Student</label>
                <select name="student_id" class="form-control" required>
                    <?php while($s = mysqli_fetch_assoc($students)){ ?>
                        <option value="<?php echo $s['id']; ?>"
                            <?php if($s['id'] == $row['student_id']) echo "selected"; ?>>
                            <?php echo htmlspecialchars($s['full_name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- COMPANY -->
            <div class="mb-3">
                <label class="form-label">Company</label>
                <select name="company_id" class="form-control" required>
                    <?php while($c = mysqli_fetch_assoc($companies)){ ?>
                        <option value="<?php echo $c['id']; ?>"
                            <?php if($c['id'] == $row['company_id']) echo "selected"; ?>>
                            <?php echo htmlspecialchars($c['company_name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- STATUS -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" name="status" class="form-control"
                       value="<?php echo htmlspecialchars($row['status']); ?>" required>
            </div>

            <!-- REMARKS -->
            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-control" rows="4"><?php echo htmlspecialchars($row['remarks']); ?></textarea>
            </div>

            <button type="submit" name="update_application" class="btn btn-primary px-4 py-2">
                Update Application
            </button>

        </form>

    </div>

</div>

<?php include "includes/sidebar-script.php"; ?>

</body>
</html>