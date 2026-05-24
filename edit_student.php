<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

if(!isset($_GET['id']) || empty($_GET['id'])){
    die("ID student tidak dijumpai");
}

$id = $_GET['id'];

$get = mysqli_query($conn, "SELECT * FROM students WHERE id='$id'");
$row = mysqli_fetch_assoc($get);

if(!$row){
    die("Student tidak wujud");
}

if(isset($_POST['update_student'])){

    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $university = $_POST['university'];
    $course = $_POST['course'];
    $status = $_POST['internship_status'];

    mysqli_query($conn, "
        UPDATE students SET
        full_name='$full_name',
        email='$email',
        phone='$phone',
        university='$university',
        course='$course',
        internship_status='$status'
        WHERE id='$id'
    ");

    header("Location: students.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container">
    <h3>Edit Student</h3>

    <form method="POST">

        <input type="text" name="full_name" class="form-control mb-2"
               value="<?php echo $row['full_name']; ?>">

        <input type="email" name="email" class="form-control mb-2"
               value="<?php echo $row['email']; ?>">

        <input type="text" name="phone" class="form-control mb-2"
               value="<?php echo $row['phone']; ?>">

        <input type="text" name="university" class="form-control mb-2"
               value="<?php echo $row['university']; ?>">

        <input type="text" name="course" class="form-control mb-2"
               value="<?php echo $row['course']; ?>">

        <select name="internship_status" class="form-control mb-3">
            <option value="Active" <?php if($row['internship_status']=="Active") echo "selected"; ?>>Active</option>
            <option value="Completed" <?php if($row['internship_status']=="Completed") echo "selected"; ?>>Completed</option>
            <option value="Pending" <?php if($row['internship_status']=="Pending") echo "selected"; ?>>Pending</option>
        </select>

        <button type="submit" name="update_student" class="btn btn-primary">
            Update Student
        </button>

    </form>
</div>

</body>
</html>