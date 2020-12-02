<?php
function get_conn()
{
    $servername = getenv("SQL_HOST");
    $username = getenv("SQL_USERNAME");
    $password = getenv("SQL_PASSWORD");
    $database = getenv("SQL_DATABASE");

    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>