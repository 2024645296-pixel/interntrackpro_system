<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

$students = mysqli_query($conn, "SELECT * FROM students");
$companies = mysqli_query($conn, "SELECT * FROM companies");

$message = "";

if(isset($_POST['add_application'])){

    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $company_id = mysqli_real_escape_string($conn, $_POST['company_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    // CHECK DUPLICATE APPLICATION
    $check = mysqli_query($conn,"
        SELECT * FROM applications
        WHERE student_id='$student_id'
        AND company_id='$company_id'
    ");

    if(mysqli_num_rows($check) > 0){
        $message = "❌ Student already applied to this company!";
    } else {

        $insert = mysqli_query($conn,"
            INSERT INTO applications
            (student_id, company_id, status, remarks)
            VALUES
            ('$student_id','$company_id','$status','$remarks')
        ");

        if($insert){
            header("Location: applications.php");
            exit;
        } else {
            $message = "❌ Failed to create application!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Application | InternTrack Pro</title>

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
    <a href="applications.php" class="nav-item">Applications</a>
    <a href="add_application.php" class="nav-item active">Add Application</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div>
            <h4>Add Application</h4>
            <small>Create internship application</small>
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
                    <label>Select Student</label>
                    <select name="student_id" class="form-control" required>
                        <option value="">Choose Student</option>

                        <?php while($student = mysqli_fetch_assoc($students)){ ?>
                            <option value="<?php echo $student['id']; ?>">
                                <?php echo $student['full_name']; ?>
                            </option>
                        <?php } ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label>Select Company</label>
                    <select name="company_id" class="form-control" required>
                        <option value="">Choose Company</option>

                        <?php while($company = mysqli_fetch_assoc($companies)){ ?>
                            <option value="<?php echo $company['id']; ?>">
                                <?php echo $company['company_name']; ?>
                            </option>
                        <?php } ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Pending">Pending</option>
                        <option value="Accepted">Accepted</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" rows="4"></textarea>
                </div>

            </div>

            <button type="submit" name="add_application" class="btn btn-primary mt-4">
                Save Application
            </button>

        </form>

    </div>

</div>

</body>
</html>