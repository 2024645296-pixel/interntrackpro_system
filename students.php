<?php
session_start();
include "config/db.php";

/* CHECK LOGIN */
if (!isset($_SESSION['admin_name'])) {
    header("Location: index.php");
    exit;
}

$search = mysqli_real_escape_string($conn, $_GET['search'] ?? "");

if (!empty($search)) {

    $result = mysqli_query($conn, "
        SELECT * FROM students
        WHERE full_name LIKE '%$search%'
        OR email LIKE '%$search%'
        OR university LIKE '%$search%'
        OR course LIKE '%$search%'
        ORDER BY id DESC
    ");

} else {

    $result = mysqli_query($conn, "
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
                <h4 class="fw-bold mb-1">Students Management</h4>
                <small class="text-muted">Manage internship students</small>
            </div>

            <a href="add_student.php" class="btn btn-primary px-4 py-2">
                + Add Student
            </a>

        </div>

        <!-- SEARCH BOX -->
        <div class="box mt-4">

            <form method="GET">

                <div class="row g-3 align-items-center">

                    <div class="col-md-10">

                        <input type="text" name="search" class="form-control"
                            placeholder="Search student by name, email, university..."
                            value="<?php echo htmlspecialchars($search); ?>">

                    </div>

                    <div class="col-md-2">

                        <button class="btn btn-primary w-100 py-3">
                            Search
                        </button>

                    </div>

                </div>

            </form>

        </div>

        <!-- TABLE -->
        <div class="box mt-4 p-0 overflow-hidden">

            <div class="table-responsive">

                <table class="table align-middle mb-0 custom-table">

                    <thead>

                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>University</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (mysqli_num_rows($result) == 0) { ?>

                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    No students found
                                </td>
                            </tr>

                        <?php } ?>

                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                            <tr>

                                <td class="ps-4 fw-semibold">
                                    #<?php echo $row['id']; ?>
                                </td>

                                <td class="fw-semibold">
                                    <?php echo $row['full_name']; ?>
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

                                    if ($status == "accepted") {
                                        $badge = "bg-success";
                                    } elseif ($status == "pending") {
                                        $badge = "bg-warning text-dark";
                                    } elseif ($status == "rejected") {
                                        $badge = "bg-danger";
                                    } elseif ($status == "active") {
                                        $badge = "bg-primary";
                                    }
                                    ?>

                                    <span class="badge <?php echo $badge; ?> px-3 py-2">
                                        <?php echo ucfirst($row['internship_status']); ?>
                                    </span>

                                </td>

                                <!-- ACTION -->
                                <td>
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="edit_student.php?id=<?php echo $row['id']; ?>"
                                            class="btn btn-sm btn-primary px-3">
                                            Edit
                                        </a>

                                        <a href="delete_student.php?id=<?php echo $row['id']; ?>"
                                            class="btn btn-sm btn-danger px-3"
                                            onclick="return confirm('Delete this student?')">
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

    <!-- SIDEBAR SCRIPT -->
    <?php include "includes/sidebar-script.php"; ?>

</body>

</html>