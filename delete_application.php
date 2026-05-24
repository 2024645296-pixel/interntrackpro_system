<?php

include "config/db.php";

$id = $_GET['id'];

mysqli_query($conn,
"DELETE FROM applications WHERE id='$id'");

header("Location: applications.php");

?>