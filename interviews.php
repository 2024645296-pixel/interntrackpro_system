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

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- SIDEBAR CSS -->
<link rel="stylesheet" href="assets/css/sidebar.css">

<!-- DASHBOARD CSS -->
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
            <h4 class="fw-bold mb-1">Interview Scheduler</h4>
            <small class="text-muted">Manage internship interviews</small>
        </div>

        <a href="add_interview.php" class="btn btn-primary px-4 py-2">
            + Add Interview
        </a>

    </div>

    <!-- TABLE -->
    <div class="box mt-4 p-0 overflow-hidden">

        <div class="table-responsive">

            <table class="table align-middle mb-0 custom-table">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Company</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>

                </thead>

                <tbody>

                <?php if(mysqli_num_rows($result) == 0){ ?>

                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            No interviews scheduled
                        </td>
                    </tr>

                <?php } ?>

                <?php while($row = mysqli_fetch_assoc($result)){ ?>

                    <tr>

                        <td class="fw-semibold">
                            #<?php echo $row['id']; ?>
                        </td>

                        <td>
                            <b><?php echo htmlspecialchars($row['full_name']); ?></b>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['company_name']); ?>
                        </td>

                        <td>
                            <?php echo $row['interview_date']; ?>
                        </td>

                        <td>
                            <?php echo $row['interview_time']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['location']); ?>
                        </td>

                        <!-- STATUS -->
                        <td>

                            <?php 
                                $status = strtolower($row['status']);

                                if($status == "scheduled"){ 
                                    echo "<span class='badge bg-warning text-dark'>Scheduled</span>";
                                }
                                elseif($status == "done"){ 
                                    echo "<span class='badge bg-success'>Done</span>";
                                }
                                else{ 
                                    echo "<span class='badge bg-danger'>Missed</span>";
                                }
                            ?>

                        </td>

                        <!-- ACTION -->
                        <td>
                            <div class="d-flex justify-content-center gap-2">

                                <a href="edit_interview.php?id=<?php echo $row['id']; ?>"
                                   class="btn btn-sm btn-primary px-3">
                                    Edit
                                </a>

                                <a href="delete_interview.php?id=<?php echo $row['id']; ?>"
                                   class="btn btn-sm btn-danger px-3"
                                   onclick="return confirm('Delete interview?')">
                                    Delete
                                </a>

                            </div>
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- SIDEBAR SCRIPT (ONLY ONCE) -->
<?php include "includes/sidebar-script.php"; ?>

</body>
</html>