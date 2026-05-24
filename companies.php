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
    $result = mysqli_query($conn,"SELECT * FROM companies ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Companies | InternTrack Pro</title>

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
            <h4>Companies Management</h4>
            <small>Manage internship companies</small>
        </div>

        <a href="add_company.php" class="btn btn-primary">
            + Add Company
        </a>
    </div>

    <!-- SEARCH -->
    <div class="box mt-3">
        <form method="GET">
            <div class="row g-2">

                <div class="col-md-10">
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search company..."
                        value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-dark w-100">Search</button>
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
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Industry</th>
                        <th>Slots</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if(mysqli_num_rows($result) == 0){ ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            No companies found
                        </td>
                    </tr>
                <?php } ?>

                <?php while($row = mysqli_fetch_assoc($result)){ ?>

                    <tr>
                        <td><?php echo $row['id']; ?></td>

                        <td><b><?php echo $row['company_name']; ?></b></td>

                        <td><?php echo $row['email']; ?></td>

                        <td><?php echo $row['phone']; ?></td>

                        <td><?php echo $row['location']; ?></td>

                        <td><?php echo $row['industry']; ?></td>

                        <td>
                            <span class="badge bg-info">
                                <?php echo $row['internship_slots']; ?>
                            </span>
                        </td>

                        <td class="d-flex gap-2">
                            <a href="edit_company.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                Edit
                            </a>

                            <a href="delete_company.php?id=<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this company?')">
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