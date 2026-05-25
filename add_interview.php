<?php
session_start();
include "config/db.php";

$message = "";

/* CHECK LOGIN */
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

/* ADD INTERVIEW */
if(isset($_POST['add_interview'])){

    $application_id = mysqli_real_escape_string($conn, $_POST['application_id']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $check = mysqli_query($conn,"
        SELECT * FROM interviews
        WHERE application_id='$application_id'
        AND interview_date='$date'
    ");

    if(mysqli_num_rows($check) > 0){
        $message = "Interview already scheduled for this date!";
    }else{

        $insert = mysqli_query($conn,"
            INSERT INTO interviews
            (application_id, interview_date, interview_time, location, status, notes)
            VALUES
            ('$application_id','$date','$time','$location','$status','$notes')
        ");

        if($insert){
            header("Location: interviews.php");
            exit;
        }else{
            $message = "Failed to save interview!";
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

<link rel="stylesheet" href="assets/css/sidebar.css">
<link rel="stylesheet" href="assets/css/dashboard.css">

</head>

<body>

<!-- SIDEBAR -->
<?php include "includes/sidebar.php"; ?>

<div id="overlay"></div>

<div class="main-content">

    <!-- TOP -->
    <div class="topbar box">

        <div>
            <h4 class="fw-bold mb-1">Schedule Interview</h4>
            <small class="text-muted">Create a new interview session</small>
        </div>

        <a href="interviews.php" class="btn btn-dark px-4 py-2">
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

                <div class="col-md-12">
                    <label class="form-label">Select Application</label>
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
                    <label class="form-label">Interview Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Interview Time</label>
                    <input type="time" name="time" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option>Scheduled</option>
                        <option>Done</option>
                        <option>Missed</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="4"></textarea>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" name="add_interview" class="btn btn-primary px-4 py-2">
                    Save Interview
                </button>
            </div>

        </form>

    </div>

</div>

<?php include "includes/sidebar-script.php"; ?>

</body>
</html>