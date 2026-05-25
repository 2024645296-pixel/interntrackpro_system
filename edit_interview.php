<?php
session_start();
include "config/db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: interviews.php");
    exit;
}

$id = intval($_GET['id']);

/* MAIN DATA */
$query = "
SELECT interviews.*,
students.full_name,
companies.company_name
FROM interviews
JOIN applications ON interviews.application_id = applications.id
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
WHERE interviews.id = $id
";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if(!$data){
    die("Interview not found");
}

/* APPLICATION LIST */
$applications = mysqli_query($conn, "
SELECT applications.id,
students.full_name,
companies.company_name
FROM applications
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
");

/* UPDATE */
if(isset($_POST['update_interview'])){

    $application_id = intval($_POST['application_id']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $status = $_POST['status'];
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $stmt = $conn->prepare("
        UPDATE interviews 
        SET application_id=?, interview_date=?, interview_time=?, location=?, status=?, notes=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "isssssi",
        $application_id,
        $date,
        $time,
        $location,
        $status,
        $notes,
        $id
    );

    $stmt->execute();

    header("Location: interviews.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Interview | InternTrack Pro</title>

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
            <h4 class="fw-bold mb-1">Edit Interview</h4>
            <small class="text-muted">Update interview information</small>
        </div>

        <a href="interviews.php" class="btn btn-dark px-4 py-2">
            ← Back
        </a>

    </div>

    <!-- FORM -->
    <div class="box mt-4">

        <form method="POST">

            <div class="row g-4">

                <!-- APPLICATION -->
                <div class="col-md-12">
                    <label class="form-label">Application</label>
                    <select name="application_id" class="form-control" required>

                        <?php while($app = mysqli_fetch_assoc($applications)){ ?>
                            <option value="<?php echo $app['id']; ?>"
                                <?php if($app['id'] == $data['application_id']) echo "selected"; ?>>

                                <?php echo $app['full_name']; ?> - <?php echo $app['company_name']; ?>

                            </option>
                        <?php } ?>

                    </select>
                </div>

                <!-- DATE -->
                <div class="col-md-6">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control"
                           value="<?php echo $data['interview_date']; ?>" required>
                </div>

                <!-- TIME -->
                <div class="col-md-6">
                    <label class="form-label">Time</label>
                    <input type="time" name="time" class="form-control"
                           value="<?php echo $data['interview_time']; ?>" required>
                </div>

                <!-- LOCATION -->
                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control"
                           value="<?php echo htmlspecialchars($data['location']); ?>">
                </div>

                <!-- STATUS -->
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">

                        <option value="Scheduled" <?php if($data['status']=="Scheduled") echo "selected"; ?>>
                            Scheduled
                        </option>

                        <option value="Done" <?php if($data['status']=="Done") echo "selected"; ?>>
                            Done
                        </option>

                        <option value="Missed" <?php if($data['status']=="Missed") echo "selected"; ?>>
                            Missed
                        </option>

                    </select>
                </div>

                <!-- NOTES -->
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="4"><?php echo htmlspecialchars($data['notes']); ?></textarea>
                </div>

            </div>

            <!-- BUTTON -->
            <div class="mt-4">
                <button type="submit" name="update_interview" class="btn btn-primary px-4 py-2">
                    Update Interview
                </button>
            </div>

        </form>

    </div>

</div>

<?php include "includes/sidebar-script.php"; ?>

</body>
</html>