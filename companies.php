<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

$search = mysqli_real_escape_string($conn, $_GET['search'] ?? "");

if(!empty($search)){

    $result = mysqli_query($conn,"
        SELECT * FROM companies
        WHERE company_name LIKE '%$search%'
        OR email LIKE '%$search%'
        OR location LIKE '%$search%'
        OR industry LIKE '%$search%'
        ORDER BY id DESC
    ");

}else{

    $result = mysqli_query($conn,"
        SELECT * FROM companies
        ORDER BY id DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Companies | InternTrack Pro</title>

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
            <h4 class="fw-bold mb-1">Companies Management</h4>
            <small class="text-muted">Manage internship companies</small>
        </div>

        <a href="add_company.php" class="btn btn-primary px-4 py-2">
            + Add Company
        </a>

    </div>

    <!-- SEARCH -->
    <div class="box mt-4">

        <form method="GET">

            <div class="row g-3 align-items-center">

                <div class="col-md-10">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search company..."
                        value="<?php echo htmlspecialchars($search); ?>"
                    >

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
                        <th>ID</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Industry</th>
                        <th>Slots</th>
                        <th class="text-center">Action</th>
                    </tr>

                </thead>

                <tbody>

                <?php if(mysqli_num_rows($result) == 0){ ?>

                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            No companies found
                        </td>
                    </tr>

                <?php } ?>

                <?php while($row = mysqli_fetch_assoc($result)){ ?>

                    <tr>

                        <td class="fw-semibold">
                            #<?php echo $row['id']; ?>
                        </td>

                        <td class="fw-semibold">
                            <?php echo $row['company_name']; ?>
                        </td>

                        <td>
                            <?php echo $row['email']; ?>
                        </td>

                        <td>
                            <?php echo $row['phone']; ?>
                        </td>

                        <td>
                            <?php echo $row['location']; ?>
                        </td>

                        <td>
                            <?php echo $row['industry']; ?>
                        </td>

                        <td>
                            <span class="badge bg-info text-dark">
                                <?php echo $row['internship_slots']; ?>
                            </span>
                        </td>

                        <td>
                            <div class="d-flex justify-content-center gap-2">

                                <a href="edit_company.php?id=<?php echo $row['id']; ?>"
                                   class="btn btn-sm btn-primary px-3">
                                    Edit
                                </a>

                                <a href="delete_company.php?id=<?php echo $row['id']; ?>"
                                   class="btn btn-sm btn-danger px-3"
                                   onclick="return confirm('Delete this company?')">
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