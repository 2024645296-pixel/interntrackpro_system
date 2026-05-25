<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: companies.php");
    exit;
}

$id = intval($_GET['id']);

$get = mysqli_query($conn, "SELECT * FROM companies WHERE id='$id'");
$row = mysqli_fetch_assoc($get);

if(!$row){
    die("Company not found");
}

/* UPDATE */
if(isset($_POST['update_company'])){

    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $slots = intval($_POST['internship_slots']);

    mysqli_query($conn, "
        UPDATE companies SET
        company_name='$company_name',
        email='$email',
        phone='$phone',
        location='$location',
        industry='$industry',
        internship_slots='$slots'
        WHERE id='$id'
    ");

    header("Location: companies.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Company | InternTrack Pro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- SAME STYLE AS STUDENTS PAGE -->
<link rel="stylesheet" href="assets/css/sidebar.css">
<link rel="stylesheet" href="assets/css/dashboard.css">

</head>

<body>

<!-- SIDEBAR -->
<?php include "includes/sidebar.php"; ?>

<div id="overlay"></div>

<div class="main-content">

    <!-- TOPBAR (same style as edit_students.php) -->
    <div class="topbar box">

        <div>
            <h4 class="fw-bold mb-1">Edit Company</h4>
            <small class="text-muted">Update company information</small>
        </div>

        <a href="companies.php" class="btn btn-dark px-4 py-2">
            ← Back
        </a>

    </div>

    <!-- FORM -->
    <div class="box mt-4">

        <form method="POST">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control"
                           value="<?php echo htmlspecialchars($row['company_name']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?php echo htmlspecialchars($row['email']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control"
                           value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control"
                           value="<?php echo htmlspecialchars($row['location']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Industry</label>
                    <input type="text" name="industry" class="form-control"
                           value="<?php echo htmlspecialchars($row['industry']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Internship Slots</label>
                    <input type="number" name="internship_slots" class="form-control"
                           value="<?php echo htmlspecialchars($row['internship_slots']); ?>" required>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" name="update_company" class="btn btn-primary px-4 py-2">
                    Update Company
                </button>
            </div>

        </form>

    </div>

</div>

<?php include "includes/sidebar-script.php"; ?>

</body>
</html>