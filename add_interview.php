<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

/* GET APPLICATIONS */
$applications = mysqli_query($conn, "
SELECT applications.id,
students.full_name,
companies.company_name
FROM applications
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
");

$message = "";

if(isset($_POST['add_interview'])){

    $application_id = mysqli_real_escape_string($conn, $_POST['application_id']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    /* CHECK DUPLICATE INTERVIEW */
    $check = mysqli_query($conn,"
        SELECT * FROM interviews
        WHERE application_id='$application_id'
        AND interview_date='$date'
    ");

    if(mysqli_num_rows($check) > 0){
        $message = "❌ Interview already scheduled for this application on this date!";
    } else {

        $insert = mysqli_query($conn,"
            INSERT INTO interviews
            (application_id, interview_date, interview_time, location, status, notes)
            VALUES
            ('$application_id','$date','$time','$location','$status','$notes')
        ");

        if($insert){
            header("Location: interviews.php");
            exit;
        } else {
            $message = "❌ Failed to save interview!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Interview | InternTrack Pro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

<!-- SIDEBAR (CONSISTENT SYSTEM) -->
<div class="sidebar">
    <div class="brand">Intern<span>Track</span></div>

    <a href="dashboard.php" class="nav-item">Dashboard</a>
    <a href="students.php" class="nav-item">Students</a>
    <a href="companies.php" class="nav-item">Companies</a>
    <a href="applications.php" class="nav-item">Applications</a>
    <a href="interviews.php" class="nav-item">Interviews</a>
    <a href="add_interview.php" class="nav-item active">Add Interview</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOP -->
    <div class="topbar">
        <div>
            <h4>Add Interview</h4>
            <small>Schedule new interview</small>
        </div>
    </div>

    <!-- ALERT -->
    <?php if($message != ""){ ?>
        <div class="alert alert-danger mt-3">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <!-- FORM -->
    <div class="box mt-3">

        <form method="POST">

            <div class="row g-3">

                <div class="col-md-12">
                    <label>Application</label>
                    <select name="application_id" class="form-control" required>
                        <option value="">Choose Application</option>

                        <?php while($app = mysqli_fetch_assoc($applications)){ ?>
                            <option value="<?php echo $app['id']; ?>">
                                <?php echo $app['full_name']; ?> - <?php echo $app['company_name']; ?>
                            </option>
                        <?php } ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Time</label>
                    <input type="time" name="time" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Done">Done</option>
                        <option value="Missed">Missed</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="4"></textarea>
                </div>

            </div>

            <button type="submit" name="add_interview" class="btn btn-primary mt-4">
                Save Interview
            </button>

        </form>

    </div>

</div>

</body>
</html>