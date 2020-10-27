<?php
// Start the session
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST['logout']))
        {
            session_unset();
            session_destroy();
        }
    }
?>
<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div class="row">
        <div class="col-md-12">
            <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-dark bg-dark">
                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                    <span class="navbar-toggler-icon"></span>
                </button> <a class="navbar-brand" href="#">E-GaiGai</a>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                    <form class="form-inline">
                        <input class="form-control mr-sm-2" type="text" />
                        <button class="btn btn-primary my-2 my-sm-0" type="submit">
                            Search
                        </button>
                    </form>
                    <ul class="navbar-nav ml-md-auto">
                        <?php 
                    // add login button if not logged in else show username
                    if (!isset($_SESSION["username"]))
                    {
                        echo '<li class="nav-item">
                        <a class="nav-link" href="./login">Login</a>
                        </li>';
                    }
                    else {
                        $username = $_SESSION["username"];
                        echo 
                        "<li class='nav-item'>
                            <a class='nav-link' href='./profile'>$username</a>
                        </li>
                        
                        <li class='nav-item'>
                            <form class='form-inline' action='./' method='post'>
                            <input type='hidden' id='logout' name='logout' value='true'>
                                <button type='submit' class='btn btn-primary'>Logout</button>
                            </form>
                        </li>
                        ";
                    }
                    ?>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>