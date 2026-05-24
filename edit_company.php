<?php
session_start();
include "config/db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

if(!$get){
    die("SQL ERROR: " . mysqli_error($conn));
}

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

    $update = mysqli_query($conn, "
        UPDATE companies SET
        company_name='$company_name',
        email='$email',
        phone='$phone',
        location='$location',
        industry='$industry',
        internship_slots='$slots'
        WHERE id='$id'
    ");

    if(!$update){
        die("UPDATE ERROR: " . mysqli_error($conn));
    }

    header("Location: companies.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Company</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container">

    <h3>Edit Company</h3>

    <form method="POST">

        <input type="text" name="company_name" class="form-control mb-2"
               value="<?php echo $row['company_name']; ?>">

        <input type="email" name="email" class="form-control mb-2"
               value="<?php echo $row['email']; ?>">

        <input type="text" name="phone" class="form-control mb-2"
               value="<?php echo $row['phone']; ?>">

        <input type="text" name="location" class="form-control mb-2"
               value="<?php echo $row['location']; ?>">

        <input type="text" name="industry" class="form-control mb-2"
               value="<?php echo $row['industry']; ?>">

        <input type="number" name="internship_slots" class="form-control mb-3"
               value="<?php echo $row['internship_slots']; ?>">

        <button type="submit" name="update_company" class="btn btn-primary">
            Update Company
        </button>

        <a href="companies.php" class="btn btn-secondary">Back</a>

    </form>

</div>

</body>
</html>