<?php
session_start();
include "config/db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: interviews.php");
    exit;
}

$id = intval($_GET['id']);

/* MAIN QUERY */
$query = "
SELECT interviews.*,
applications.id AS app_id,
students.full_name,
companies.company_name
FROM interviews
JOIN applications ON interviews.application_id = applications.id
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
WHERE interviews.id = $id
";

$result = mysqli_query($conn, $query);

if(!$result){
    die("SQL ERROR (main query): " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);

if(!$data){
    die("Interview not found");
}

/* APPLICATION LIST */
$applications = mysqli_query($conn, "
SELECT applications.id,
students.full_name,
companies.company_name
FROM applications
JOIN students ON applications.student_id = students.id
JOIN companies ON applications.company_id = companies.id
");

if(!$applications){
    die("SQL ERROR (applications): " . mysqli_error($conn));
}

/* UPDATE */
if(isset($_POST['update_interview'])){

    $application_id = intval($_POST['application_id']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $status = $_POST['status'];
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $stmt = $conn->prepare("
        UPDATE interviews 
        SET application_id=?, interview_date=?, interview_time=?, location=?, status=?, notes=?
        WHERE id=?
    ");

    if(!$stmt){
        die("PREPARE ERROR: " . $conn->error);
    }

    $stmt->bind_param(
        "isssssi",
        $application_id,
        $date,
        $time,
        $location,
        $status,
        $notes,
        $id
    );

    if(!$stmt->execute()){
        die("EXECUTE ERROR: " . $stmt->error);
    }

    header("Location: interviews.php");
    exit;
}
?>