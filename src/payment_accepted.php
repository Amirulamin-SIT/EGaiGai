<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment</title>
</head>

<body>
<?php 
    include "./header.php";
    if(!isset($_SESSION["iduser"]))
    {
        session_unset();
        session_destroy();
        header('Location: ./index.php');
    }
?>

<h2>Thank you for your order</h2>
</body>