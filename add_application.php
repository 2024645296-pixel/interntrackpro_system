<?php
session_start();
include "config/db.php";

$message = "";

/* CHECK LOGIN */
if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

/* GET DATA */
$students = mysqli_query($conn,"SELECT * FROM students");
$companies = mysqli_query($conn,"SELECT * FROM companies");

/* ADD APPLICATION */
if(isset($_POST['add_application'])){

    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $company_id = mysqli_real_escape_string($conn, $_POST['company_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    /* CHECK DUPLICATE */
    $check = mysqli_query($conn,"
        SELECT * FROM applications
        WHERE student_id='$student_id'
        AND company_id='$company_id'
    ");

    if(mysqli_num_rows($check) > 0){
        $message = "Student already applied to this company!";
    }else{

        $insert = mysqli_query($conn,"
            INSERT INTO applications
            (student_id, company_id, status, remarks)
            VALUES
            ('$student_id','$company_id','$status','$remarks')
        ");

        if($insert){
            header("Location: applications.php");
            exit;
        }else{
            $message = "Failed to create application!";
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
            <h4 class="fw-bold mb-1">Add New Application</h4>
            <small class="text-muted">Create internship application for students</small>
        </div>

        <a href="applications.php" class="btn btn-dark px-4 py-2">
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

                <!-- STUDENT -->
                <div class="col-md-6">
                    <label class="form-label">Select Student</label>
                    <select name="student_id" class="form-control" required>
                        <option value="">Choose Student</option>
                        <?php while($student = mysqli_fetch_assoc($students)){ ?>
                            <option value="<?php echo $student['id']; ?>">
                                <?php echo $student['full_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- COMPANY -->
                <div class="col-md-6">
                    <label class="form-label">Select Company</label>
                    <select name="company_id" class="form-control" required>
                        <option value="">Choose Company</option>
                        <?php while($company = mysqli_fetch_assoc($companies)){ ?>
                            <option value="<?php echo $company['id']; ?>">
                                <?php echo $company['company_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- STATUS -->
                <div class="col-md-6">
                    <label class="form-label">Application Status</label>
                    <select name="status" class="form-control">
                        <option value="Pending">Pending</option>
                        <option value="Accepted">Accepted</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

                <!-- REMARKS -->
                <div class="col-md-12">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="5"
                              placeholder="Enter remarks..."></textarea>
                </div>

            </div>

            <!-- BUTTON -->
            <div class="mt-4">
                <button type="submit" name="add_application" class="btn btn-primary px-4 py-2">
                    Save Application
                </button>
            </div>

        </form>

    </div>

</div>

<!-- SIDEBAR SCRIPT -->
<?php include "includes/sidebar-script.php"; ?>

</body>
</html>