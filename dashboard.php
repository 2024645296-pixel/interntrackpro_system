<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

/* =========================
   KPI
========================= */

$result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM students");

if(!$result){
    die("SQL ERROR (students): " . mysqli_error($conn));
}

$total_students = mysqli_fetch_assoc($result)['total'];


$result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM companies");

if(!$result){
    die("SQL ERROR (companies): " . mysqli_error($conn));
}

$total_companies = mysqli_fetch_assoc($result)['total'];


$result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM applications");

if(!$result){
    die("SQL ERROR (applications): " . mysqli_error($conn));
}

$total_applications = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM applications WHERE status='Pending'");

if(!$result){
    die("SQL ERROR (pending): " . mysqli_error($conn));
}

$pending = mysqli_fetch_assoc($result)['total'];

/* =========================
   RECENT ACTIVITY
========================= */

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

/* =========================
   UPCOMING INTERVIEWS
========================= */

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

/* =========================
   CALENDAR EVENTS
========================= */

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

    $color = "#6c757d";

    if($row['status'] == "Pending"){
        $color = "#2563eb";
    }
    elseif($row['status'] == "Accepted"){
        $color = "#10b981";
    }
    elseif($row['status'] == "Rejected"){
        $color = "#ef4444";
    }

    $events[] = [
        'title' => $row['full_name'],
        'start' => $row['interview_date'],
        'backgroundColor' => $color,
        'borderColor' => $color,

        'extendedProps' => [

            'student' => $row['full_name'],
            'company' => $row['company_name'],
            'status' => $row['status']

        ]
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard | InternTrack Pro</title>

<!-- GOOGLE FONT -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- FULLCALENDAR -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="assets/css/sidebar.css">
<link rel="stylesheet" href="assets/css/dashboard.css">

</head>

<body class="dashboard">

<!-- SIDEBAR -->
<?php include "includes/sidebar.php"; ?>

<!-- OVERLAY -->
<div id="overlay"></div>

<!-- MAIN CONTENT -->
<div class="main-content" id="main">

    <div class="topbar">

    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>InternTrack Admin Panel</p>
    </div>

    <div class="user-box">
        Welcome,
        <span><?php echo $_SESSION['admin_name']; ?></span>
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

                <h5 class="mb-3">
                    Recent Activity
                </h5>

                <?php while($a = mysqli_fetch_assoc($activities)){ ?>

                <div class="activity">

                    <div class="d-flex justify-content-between align-items-center gap-2">

                        <div style="font-size:14px;">

                            <?php if($a['status']=="Pending"){ ?>

                                📄 
                                <b><?php echo $a['full_name']; ?></b> 
                                applied to 
                                <b><?php echo $a['company_name']; ?></b>

                            <?php } elseif($a['status']=="Accepted"){ ?>

                                🎉 
                                <b><?php echo $a['company_name']; ?></b> 
                                accepted 
                                <b><?php echo $a['full_name']; ?></b>

                            <?php } else { ?>

                                ❌ 
                                <b><?php echo $a['company_name']; ?></b> 
                                rejected 
                                <b><?php echo $a['full_name']; ?></b>

                            <?php } ?>

                        </div>

                        <span class="badge bg-<?php 
                            echo $a['status']=="Accepted" ? "success" : 
                            ($a['status']=="Rejected" ? "danger" : "primary"); 
                        ?>">

                            <?php echo $a['status']; ?>

                        </span>

                    </div>

                    <small style="color:#94a3b8;">

                        Application ID #<?php echo $a['app_id']; ?>

                    </small>

                </div>

                <?php } ?>

            </div>

            <!-- UPCOMING -->
            <div class="box mt-3">

                <h5 class="mb-3">
                    Upcoming Interviews
                </h5>

                <?php while($u = mysqli_fetch_assoc($upcoming)){ ?>

                <div class="activity">

                    📅 
                    <b><?php echo $u['full_name']; ?></b>

                    → 

                    <?php echo $u['company_name']; ?>

                    <br>

                    <small style="color:#94a3b8;">

                        <?php echo $u['interview_date']; ?>

                    </small>

                </div>

                <?php } ?>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-7">

            <div class="box">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h5 class="mb-1">
                            Interview Calendar
                        </h5>

                        <small style="color:#94a3b8;">
                            Monthly Schedule
                        </small>

                    </div>

                </div>

                <!-- CALENDAR -->
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

        height: 320,

        fixedWeekCount: false,

        dayMaxEvents: 2,

        headerToolbar: {

            left: 'prev,next',

            center: 'title',

            right: ''

        },

        events: <?php echo json_encode($events); ?>,

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

<!-- SIDEBAR SCRIPT -->
<?php include "includes/sidebar-script.php"; ?>

<?php include "includes/sidebar-script.php"; ?>

</body>
</html>