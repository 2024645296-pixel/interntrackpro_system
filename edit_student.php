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
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Student | InternTrack Pro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/sidebar.css">
<link rel="stylesheet" href="assets/css/dashboard.css">

</head>

<body>

<!-- SIDEBAR -->
<?php include "includes/sidebar.php"; ?>

<div id="overlay"></div>

<div class="main-content">

    <!-- TOPBAR -->
    <div class="topbar box">

        <div>
            <h4 class="fw-bold mb-1">Edit Student</h4>
            <small class="text-muted">Update student information</small>
        </div>

        <a href="students.php" class="btn btn-dark px-4 py-2">
            ← Back
        </a>

    </div>

    <!-- FORM -->
    <div class="box mt-4">

        <form method="POST">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control"
                           value="<?php echo htmlspecialchars($row['full_name']); ?>" required>
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
                    <label class="form-label">University</label>
                    <input type="text" name="university" class="form-control"
                           value="<?php echo htmlspecialchars($row['university']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-control"
                           value="<?php echo htmlspecialchars($row['course']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="internship_status" class="form-control">

                        <option value="Active" <?php if($row['internship_status']=="Active") echo "selected"; ?>>
                            Active
                        </option>

                        <option value="Completed" <?php if($row['internship_status']=="Completed") echo "selected"; ?>>
                            Completed
                        </option>

                        <option value="Pending" <?php if($row['internship_status']=="Pending") echo "selected"; ?>>
                            Pending
                        </option>

                    </select>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" name="update_student" class="btn btn-primary px-4 py-2">
                    Update Student
                </button>
            </div>

        </form>

    </div>

</div>

<?php include "includes/sidebar-script.php"; ?>

</body>
</html>