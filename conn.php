<?php 
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gsm";

    $conn = mysqli_connect($server, $username, $password, $dbname);

    // check connection
    if (!$conn) {
        die("Connection Failed" . mysqli_connect_error());
    }
    date_default_timezone_set('Asia/Bangkok');
    include('function.php');