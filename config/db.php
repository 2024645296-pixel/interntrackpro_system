<?php

$host = "mysql.railway.internal";
$user = "root";
$pass = "dsqXfGVlwFDCXctQTagzYjKaCfSTECvN";
$db   = "railway";
$port = 3306;

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

?>