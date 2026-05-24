<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

/* KPI */
$total_students = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM students"))['total'];
$total_companies = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM companies"))['total'];
$total_applications = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM applications"))['total'];
$pending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM applications WHERE status='Pending'"))['total'];

/* RECENT ACTIVITY */
$activities = mysqli_query($conn,"
SELECT 
applications.id AS app_id,
applications.status,
students.full_name,
companies.company_name
FROM applications
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
ORDER BY applications.id DESC
LIMIT 8
");

/* UPCOMING INTERVIEWS */
$upcoming = mysqli_query($conn,"
SELECT 
interviews.interview_date,
students.full_name,
companies.company_name
FROM interviews
JOIN applications ON interviews.application_id = applications.id
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
ORDER BY interviews.interview_date ASC
LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard | InternTrack Pro</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

<?php
$events = [];

$calData = mysqli_query($conn,"
SELECT 
interviews.interview_date,
students.full_name,
companies.company_name,
applications.status
FROM interviews
JOIN applications ON interviews.application_id = applications.id
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
");

while($row = mysqli_fetch_assoc($calData)){

    // COLOR ikut status application
    $color = "#6c757d"; // default grey

    if($row['status'] == "Pending"){
        $color = "#0d6efd"; // blue
    } elseif($row['status'] == "Accepted"){
        $color = "#198754"; // green
    } elseif($row['status'] == "Rejected"){
        $color = "#dc3545"; // red
    }

    $events[] = [
        'title' => $row['full_name'] . " @ " . $row['company_name'],
        'start' => $row['interview_date'],
        'color' => $color,

        // extra data untuk popup
        'extendedProps' => [
            'student' => $row['full_name'],
            'company' => $row['company_name'],
            'status' => $row['status']
        ]
    ];
}
?>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="main" id="main">

<!-- TOPBAR -->
<div class="topbar">
    <div>
        <h3>Dashboard</h3>
        <small>InternTrack Admin Panel</small>
    </div>

    <div class="user">
        Welcome, <b><?php echo $_SESSION['admin_name']; ?></b>
    </div>
</div>

<!-- KPI -->
<div class="row g-3">

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

<!-- CONTENT -->
<div class="row mt-4 g-3">

<!-- LEFT -->
<div class="col-lg-5">

    <!-- RECENT ACTIVITY -->
    <div class="box">
        <h5>Recent Activity</h5>

        <?php while($a = mysqli_fetch_assoc($activities)){ ?>

        <div class="activity" style="padding:12px 0;border-bottom:1px solid #eee;">

            <div style="display:flex; justify-content:space-between; align-items:center; gap:10px;">

                <div style="font-size:14px;">

                    <?php if($a['status']=="Pending"){ ?>
                        📄 <b><?php echo $a['full_name']; ?></b> applied to <b><?php echo $a['company_name']; ?></b>

                    <?php } elseif($a['status']=="Accepted"){ ?>
                        🎉 <b><?php echo $a['company_name']; ?></b> accepted <b><?php echo $a['full_name']; ?></b>

                    <?php } elseif($a['status']=="Rejected"){ ?>
                        ❌ <b><?php echo $a['company_name']; ?></b> rejected <b><?php echo $a['full_name']; ?></b>

                    <?php } else { ?>
                        📌 <b><?php echo $a['full_name']; ?></b> updated application
                    <?php } ?>

                </div>

                <?php if($a['status']=="Accepted"){ ?>
                    <span class="badge bg-success">Accepted</span>
                <?php } elseif($a['status']=="Rejected"){ ?>
                    <span class="badge bg-danger">Rejected</span>
                <?php } else { ?>
                    <span class="badge bg-primary">Pending</span>
                <?php } ?>

            </div>

            <small style="color:#999;">
                Application ID #<?php echo $a['app_id']; ?>
            </small>

        </div>

        <?php } ?>

    </div>

    <!-- UPCOMING -->
    <div class="box mt-3">
        <h5>Upcoming Interviews</h5>

        <?php while($u = mysqli_fetch_assoc($upcoming)){ ?>
            <div class="activity">
                📅 <?php echo $u['full_name']; ?> →
                <?php echo $u['company_name']; ?>
                (<?php echo $u['interview_date']; ?>)
            </div>
        <?php } ?>

    </div>

</div>

<!-- RIGHT -->
<div class="col-lg-7">

    <div class="box">
        <h5>Interview Calendar</h5>
        <div id="calendar"></div>
    </div>

</div>

</div>

</div>


<!-- CALENDAR SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',
        height: 450,
        selectable: true,

        // 🔥 INI PART IMPORTANT (DATA MASUK CALENDAR)
        events: <?php echo json_encode($events); ?>,

        // 🔥 CLICK EVENT (POPUP DETAIL)
        eventClick: function(info) {

            let e = info.event.extendedProps;

            alert(
                "Interview Details\n\n" +
                "Student: " + e.student + "\n" +
                "Company: " + e.company + "\n" +
                "Status: " + e.status + "\n" +
                "Date: " + info.event.startStr
            );
        }

    });

    calendar.render();
});
</script>

<?php include "includes/sidebar-script.php"; ?>

</body>
</html>