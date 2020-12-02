<?php
function get_conn()
{
    require_once __DIR__."/../crypto.php";
    $creds = parse_ini_string(decryptIni(".sql_creds.ini.enc"));

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