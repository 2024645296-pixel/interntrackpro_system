<?php
session_start();
include '../config/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){

    $admin = mysqli_fetch_assoc($result);

    $_SESSION['admin_name'] = $admin['name'];

    header("Location: ../dashboard.php");
    exit;

} else {

    echo "Login Failed";
}
?>