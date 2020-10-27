<?php
function get_conn()
{
    $creds = parse_ini_file(dirname(__FILE__, 3)."/.credentials/.sql_creds.ini");

    $servername = $creds["host"];
    $username = $creds["username"];
    $password = $creds["password"];
    $database = $creds["database"];

    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>