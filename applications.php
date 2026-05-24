<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

$query = "
SELECT applications.*,
students.full_name,
companies.company_name
FROM applications
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
ORDER BY applications.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Applications | InternTrack Pro</title>

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
            <h4>Applications Management</h4>
            <small>Manage internship applications</small>
        </div>

        <a href="add_application.php" class="btn btn-primary">
            + Add Application
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
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if(mysqli_num_rows($result) == 0){ ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            No applications found
                        </td>
                    </tr>
                <?php } ?>

                <?php while($row = mysqli_fetch_assoc($result)){ ?>

                    <tr>

                        <td><?php echo $row['id']; ?></td>

                        <td>
                            <b><?php echo htmlspecialchars($row['full_name']); ?></b>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['company_name']); ?>
                        </td>

                        <td>
                            <?php echo $row['application_date']; ?>
                        </td>

                        <td>
                            <?php
                            if($row['status'] == "Accepted"){
                                echo "<span class='badge bg-success'>Accepted</span>";
                            }
                            elseif($row['status'] == "Rejected"){
                                echo "<span class='badge bg-danger'>Rejected</span>";
                            }
                            else{
                                echo "<span class='badge bg-warning text-dark'>Pending</span>";
                            }
                            ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['remarks'] ?? '-'); ?>
                        </td>

                        <td class="d-flex gap-2">

                            <a href="edit_application.php?id=<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-primary">
                                Edit
                            </a>

                            <a href="delete_application.php?id=<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this application?')">
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