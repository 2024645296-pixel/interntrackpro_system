<?php
session_start();
include "config/db.php";

$message = "";

/* CHECK LOGIN */
if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

/* ADD STUDENT */
if(isset($_POST['add_student'])){

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $university = mysqli_real_escape_string($conn, $_POST['university']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $status = mysqli_real_escape_string($conn, $_POST['internship_status']);

    /* CHECK EMAIL */
    $check = mysqli_query($conn, "
        SELECT * FROM students
        WHERE email='$email'
    ");

    if(mysqli_num_rows($check) > 0){

        $message = "Email already exists!";

    }else{

        $insert = mysqli_query($conn,"
            INSERT INTO students
            (full_name,email,phone,university,course,internship_status)

            VALUES

            ('$full_name','$email','$phone','$university','$course','$status')
        ");

        if($insert){
            header("Location: students.php");
            exit;
        }else{
            $message = "Failed to add student!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Student | InternTrack Pro</title>

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

    <!-- TOP BAR -->
    <div class="topbar box">

        <div>
            <h4 class="fw-bold mb-1">Add New Student</h4>
            <small class="text-muted">Fill in student internship information</small>
        </div>

        <a href="students.php" class="btn btn-dark px-4 py-2">
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

                <!-- FULL NAME -->
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
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

                <!-- UNIVERSITY -->
                <div class="col-md-6">
                    <label class="form-label">University</label>
                    <input type="text" name="university" class="form-control" required>
                </div>

                <!-- COURSE -->
                <div class="col-md-6">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-control" required>
                </div>

                <!-- STATUS -->
                <div class="col-md-6">
                    <label class="form-label">Internship Status</label>
                    <select name="internship_status" class="form-control" required>
                        <option value="">Select Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Accepted">Accepted</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

            </div>

            <!-- BUTTON -->
            <div class="mt-4">

                <button type="submit" name="add_student" class="btn btn-primary px-4 py-2">
                    Save Student
                </button>

            </div>

        </form>

    </div>

</div>

<!-- SIDEBAR SCRIPT -->
<?php include "includes/sidebar-script.php"; ?>

</body>
</html>