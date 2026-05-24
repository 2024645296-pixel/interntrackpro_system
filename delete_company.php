<?php

include "config/db.php";

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM companies WHERE id='$id'");

header("Location: companies.php");

?>