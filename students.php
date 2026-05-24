<?php
session_start();
include "config/db.php";

/* CHECK LOGIN */
if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

$search = mysqli_real_escape_string($conn, $_GET['search'] ?? "");

if(!empty($search)){

    $result = mysqli_query($conn,"
        SELECT * FROM students
        WHERE full_name LIKE '%$search%'
        OR email LIKE '%$search%'
        OR university LIKE '%$search%'
        OR course LIKE '%$search%'
        ORDER BY id DESC
    ");

}else{

    $result = mysqli_query($conn,"
        SELECT * FROM students
        ORDER BY id DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Students | InternTrack Pro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/dashboard.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<!-- MAIN -->
<div class="main" id="main">

    <!-- TOPBAR -->
    <div class="topbar">

        <div>
            <h4>Students Management</h4>
            <small>Manage internship students</small>
        </div>

        <a href="add_student.php" class="btn btn-primary">
            + Add Student
        </a>

    </div>

    <!-- SEARCH -->
    <div class="box mt-3">

        <form method="GET">

            <div class="row g-2">

                <div class="col-md-10">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search student..."
                        value="<?php echo htmlspecialchars($search); ?>"
                    >

                </div>

                <div class="col-md-2">

                    <button class="btn btn-dark w-100">
                        Search
                    </button>

                </div>

            </div>

        </form>

    </div>

    <!-- TABLE -->
    <div class="box mt-4">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>University</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                <?php if(mysqli_num_rows($result) == 0){ ?>

                    <tr>

                        <td colspan="8" class="text-center py-4">
                            No students found
                        </td>

                    </tr>

                <?php } ?>

                <?php while($row = mysqli_fetch_assoc($result)){ ?>

                    <tr>

                        <td>
                            <?php echo $row['id']; ?>
                        </td>

                        <td>
                            <b><?php echo $row['full_name']; ?></b>
                        </td>

                        <td>
                            <?php echo $row['email']; ?>
                        </td>

                        <td>
                            <?php echo $row['phone']; ?>
                        </td>

                        <td>
                            <?php echo $row['university']; ?>
                        </td>

                        <td>
                            <?php echo $row['course']; ?>
                        </td>

                        <!-- STATUS -->
                        <td>

                            <?php
                                $status = strtolower($row['internship_status']);

                                $badge = "bg-secondary";

                                if($status == "accepted"){
                                    $badge = "bg-success";
                                }
                                elseif($status == "pending"){
                                    $badge = "bg-warning text-dark";
                                }
                                elseif($status == "rejected"){
                                    $badge = "bg-danger";
                                }
                                elseif($status == "active"){
                                    $badge = "bg-primary";
                                }
                            ?>

                            <span class="badge <?php echo $badge; ?>">

                                <?php echo $row['internship_status']; ?>

                            </span>

                        </td>

                        <!-- ACTION -->
                        <td>

                            <div class="action-buttons">

                                <a
                                    href="edit_student.php?id=<?php echo $row['id']; ?>"
                                    class="btn btn-sm btn-primary"
                                >
                                    Edit
                                </a>

                                <a
                                    href="delete_student.php?id=<?php echo $row['id']; ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this student?')"
                                >
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

<?php include "includes/sidebar-script.php"; ?>

</body>
</html>