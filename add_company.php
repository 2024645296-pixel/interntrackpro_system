<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

$message = "";

if(isset($_POST['add_company'])){

    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $slots = mysqli_real_escape_string($conn, $_POST['internship_slots']);

    // CHECK DUPLICATE EMAIL
    $check = mysqli_query($conn, "SELECT * FROM companies WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){
        $message = "❌ Company email already exists!";
    } else {

        $insert = mysqli_query($conn,"
            INSERT INTO companies
            (company_name,email,phone,location,industry,internship_slots)
            VALUES
            ('$company_name','$email','$phone','$location','$industry','$slots')
        ");

        if($insert){
            header("Location: companies.php");
            exit;
        } else {
            $message = "❌ Failed to add company!";
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="brand">Intern<span>Track</span></div>

    <a href="dashboard.php" class="nav-item">Dashboard</a>
    <a href="students.php" class="nav-item">Students</a>
    <a href="companies.php" class="nav-item">Companies</a>
    <a href="add_company.php" class="nav-item active">Add Company</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOP -->
    <div class="topbar">
        <div>
            <h4>Add Company</h4>
            <small>Register internship company</small>
        </div>
    </div>

    <!-- ALERT -->
    <?php if($message != "") { ?>
        <div class="alert alert-danger mt-3">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <!-- FORM -->
    <div class="box mt-3">

        <form method="POST">

            <div class="row g-3">

                <div class="col-md-6">
                    <label>Company Name</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Industry</label>
                    <input type="text" name="industry" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Internship Slots</label>
                    <input type="number" name="internship_slots" class="form-control" required>
                </div>

            </div>

            <button type="submit" name="add_company" class="btn btn-primary mt-4">
                Save Company
            </button>

        </form>

    </div>

</div>

</body>
</html>