<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

$message = "";

if(isset($_POST['add_student'])){

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $university = mysqli_real_escape_string($conn, $_POST['university']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $status = mysqli_real_escape_string($conn, $_POST['internship_status']);

    // CHECK DUPLICATE EMAIL
    $check = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
    
    if(mysqli_num_rows($check) > 0){
        $message = "❌ Email already exists!";
    } else {

        $insert = mysqli_query($conn,"
            INSERT INTO students
            (full_name,email,phone,university,course,internship_status)
            VALUES
            ('$full_name','$email','$phone','$university','$course','$status')
        ");

        if($insert){
            header("Location: students.php");
            exit;
        } else {
            $message = "❌ Failed to add student!";
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="brand">Intern<span>Track</span></div>

    <a href="dashboard.php" class="nav-item">Dashboard</a>
    <a href="students.php" class="nav-item">Students</a>
    <a href="add_student.php" class="nav-item active">Add Student</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOP -->
    <div class="topbar">
        <div>
            <h4>Add Student</h4>
            <small>Register new internship student</small>
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
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
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
                    <label>University</label>
                    <input type="text" name="university" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Course</label>
                    <input type="text" name="course" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Status</label>
                    <select name="internship_status" class="form-control" required>
                        <option value="">Select Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Accepted">Accepted</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

            </div>

            <button type="submit" name="add_student" class="btn btn-primary mt-4">
                Save Student
            </button>

        </form>

    </div>

</div>

</body>
</html>