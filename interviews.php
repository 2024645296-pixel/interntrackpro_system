<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

$query = "
SELECT interviews.*,
applications.id AS app_id,
students.full_name,
companies.company_name
FROM interviews
JOIN applications ON interviews.application_id = applications.id
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
ORDER BY interviews.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Interviews | InternTrack Pro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="main" id="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div>
            <h4>Interview Scheduler</h4>
            <small>Manage internship interviews</small>
        </div>

        <a href="add_interview.php" class="btn btn-primary">
            + Add Interview
        </a>
    </div>

    <!-- TABLE -->
    <div class="box mt-4">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Company</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if(mysqli_num_rows($result) == 0){ ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            No interviews scheduled
                        </td>
                    </tr>
                <?php } ?>

                <?php while($row = mysqli_fetch_assoc($result)){ ?>

                    <tr>

                        <td><?php echo $row['id']; ?></td>

                        <td><b><?php echo $row['full_name']; ?></b></td>

                        <td><?php echo $row['company_name']; ?></td>

                        <td><?php echo $row['interview_date']; ?></td>

                        <td><?php echo $row['interview_time']; ?></td>

                        <td><?php echo $row['location']; ?></td>

                        <td>

                            <?php if($row['status']=="Scheduled"){ ?>
                                <span class="badge bg-warning text-dark">Scheduled</span>

                            <?php } elseif($row['status']=="Done"){ ?>
                                <span class="badge bg-success">Done</span>

                            <?php } else { ?>
                                <span class="badge bg-danger">Missed</span>

                            <?php } ?>

                        </td>

                        <td class="d-flex gap-2">

                            <a href="edit_interview.php?id=<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-primary">
                                Edit
                            </a>

                            <a href="delete_interview.php?id=<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete interview?')">
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include "includes/sidebar-script.php"; ?>

</body>
</html>