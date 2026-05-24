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
    header("Location: applications.php");
    exit;
}

$id = intval($_GET['id']);

/* LIST DATA */
$students = mysqli_query($conn, "SELECT * FROM students");
if(!$students){
    die("Students query error: " . mysqli_error($conn));
}

$companies = mysqli_query($conn, "SELECT * FROM companies");
if(!$companies){
    die("Companies query error: " . mysqli_error($conn));
}

/* MAIN DATA */
$get = mysqli_query($conn, "SELECT * FROM applications WHERE id='$id'");
if(!$get){
    die("Application query error: " . mysqli_error($conn));
}

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

    $update = mysqli_query($conn, "
        UPDATE applications SET
        student_id='$student_id',
        company_id='$company_id',
        status='$status',
        remarks='$remarks'
        WHERE id='$id'
    ");

    if(!$update){
        die("Update error: " . mysqli_error($conn));
    }

    header("Location: applications.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container">

    <h3>Edit Application</h3>

    <form method="POST">

        <!-- STUDENT -->
        <label>Student</label>
        <select name="student_id" class="form-control mb-2">
            <?php while($s = mysqli_fetch_assoc($students)){ ?>
                <option value="<?php echo $s['id']; ?>"
                    <?php if($s['id'] == $row['student_id']) echo "selected"; ?>>
                    <?php echo $s['full_name']; ?>
                </option>
            <?php } ?>
        </select>

        <!-- COMPANY -->
        <label>Company</label>
        <select name="company_id" class="form-control mb-2">
            <?php while($c = mysqli_fetch_assoc($companies)){ ?>
                <option value="<?php echo $c['id']; ?>"
                    <?php if($c['id'] == $row['company_id']) echo "selected"; ?>>
                    <?php echo $c['company_name']; ?>
                </option>
            <?php } ?>
        </select>

        <!-- STATUS -->
        <input type="text" name="status" class="form-control mb-2"
               value="<?php echo $row['status']; ?>">

        <!-- REMARKS -->
        <textarea name="remarks" class="form-control mb-3"><?php echo $row['remarks']; ?></textarea>

        <button type="submit" name="update_application" class="btn btn-primary">
            Update Application
        </button>

        <a href="applications.php" class="btn btn-secondary">Back</a>

    </form>

</div>

</body>
</html>