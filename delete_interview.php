<?php
session_start();
include "config/db.php";

/* CHECK LOGIN */
if(!isset($_SESSION['admin_name'])){
    header("Location: index.php");
    exit;
}

/* VALIDATE ID */
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: interviews.php");
    exit;
}

$id = intval($_GET['id']);

/* DELETE QUERY (SAFE) */
$stmt = $conn->prepare("DELETE FROM interviews WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

/* REDIRECT BACK */
header("Location: interviews.php");
exit;
?>