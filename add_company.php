<?php
session_start();
include "config/db.php";

$message = "";

/* CHECK LOGIN */
if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

/* ADD COMPANY */
if(isset($_POST['add_company'])){

    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $slots = mysqli_real_escape_string($conn, $_POST['internship_slots']);

    /* CHECK EMAIL */
    $check = mysqli_query($conn,"
        SELECT * FROM companies
        WHERE email='$email'
    ");

    if(mysqli_num_rows($check) > 0){
        $message = "Company email already exists!";
    }else{

        $insert = mysqli_query($conn,"
            INSERT INTO companies
            (company_name,email,phone,location,industry,internship_slots)
            VALUES
            ('$company_name','$email','$phone','$location','$industry','$slots')
        ");

        if($insert){
            header("Location: companies.php");
            exit;
        }else{
            $message = "Failed to add company!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Company | InternTrack Pro</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- GLOBAL CSS -->
<link rel="stylesheet" href="assets/css/sidebar.css">
<link rel="stylesheet" href="assets/css/dashboard.css">

</head>

<body>

<!-- SIDEBAR -->
<?php include "includes/sidebar.php"; ?>

<!-- OVERLAY -->
<div id="overlay"></div>

<!-- MAIN CONTENT -->
<div class="main-content">

    <!-- TOPBAR -->
    <div class="topbar box">

        <div>
            <h4 class="fw-bold mb-1">Add New Company</h4>
            <small class="text-muted">Register internship company information</small>
        </div>

        <a href="companies.php" class="btn btn-dark px-4 py-2">
            ← Back
        </a>

    </div>

    <!-- ALERT -->
    <?php if($message != ""){ ?>
        <div class="alert alert-danger mt-3">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <!-- FORM -->
    <div class="box mt-4">

        <form method="POST">

            <div class="row g-4">

                <!-- COMPANY NAME -->
                <div class="col-md-6">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>

                <!-- EMAIL -->
                <div class="col-md-6">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <!-- PHONE -->
                <div class="col-md-6">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <!-- LOCATION -->
                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" required>
                </div>

                <!-- INDUSTRY -->
                <div class="col-md-6">
                    <label class="form-label">Industry</label>
                    <input type="text" name="industry" class="form-control" required>
                </div>

                <!-- SLOTS -->
                <div class="col-md-6">
                    <label class="form-label">Internship Slots</label>
                    <input type="number" name="internship_slots" class="form-control" required>
                </div>

            </div>

            <!-- BUTTON -->
            <div class="mt-4">

                <button type="submit" name="add_company" class="btn btn-primary px-4 py-2">
                    Save Company
                </button>

            </div>

        </form>

    </div>

</div>

<!-- SIDEBAR SCRIPT -->
<?php include "includes/sidebar-script.php"; ?>

</body>
</html>