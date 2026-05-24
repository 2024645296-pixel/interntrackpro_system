<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

/* KPI */
$total_students = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM students"))['total'];
$total_companies = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM companies"))['total'];
$total_applications = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM applications"))['total'];

$pending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM applications WHERE status='Pending'"))['total'];
$accepted = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM applications WHERE status='Accepted'"))['total'];
$rejected = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM applications WHERE status='Rejected'"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reports Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="main" id="main">

    <div class="topbar">
        <div>
            <h4>Reports Dashboard</h4>
            <small>Analytics & system overview</small>
        </div>

        <a href="export_pdf.php" class="btn btn-danger">
            Export PDF
        </a>
    </div>

    <!-- KPI -->
    <div class="row g-4 mt-2">

        <div class="col-md-3">
            <div class="card-box blue">
                <h6>Students</h6>
                <h2><?php echo $total_students; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box purple">
                <h6>Companies</h6>
                <h2><?php echo $total_companies; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box cyan">
                <h6>Applications</h6>
                <h2><?php echo $total_applications; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box orange">
                <h6>Pending</h6>
                <h2><?php echo $pending; ?></h2>
            </div>
        </div>

    </div>

    <!-- CHART -->
    <div class="box mt-4">

        <h5>Application Status Overview</h5>

        <canvas id="statusChart" height="100"></canvas>

    </div>

</div>

<script>
const ctx = document.getElementById('statusChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Pending', 'Accepted', 'Rejected'],
        datasets: [{
            label: 'Applications',
            data: [
                <?php echo $pending; ?>,
                <?php echo $accepted; ?>,
                <?php echo $rejected; ?>
            ],
            backgroundColor: [
                '#facc15',
                '#22c55e',
                '#ef4444'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

<?php include "includes/sidebar-script.php"; ?>

</body>
</html>