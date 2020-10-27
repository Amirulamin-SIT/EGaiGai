<?php
    $email = "";
    $password = "";
    $err_msg = "";
?>
<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In</title>
</head>

<body>
    <?php 
    include "./header.php";
    //login
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST['email']) && isset($_POST["password"]))
        {
            $email = $_POST['email'];
            $password = $_POST["password"];
            require_once "./functions/sql/auth.php";
            if(!login($email,$password))
            {
                $err_msg = 'Invalid Username or Password';
            }
            else {
                header('Location: ./index');
            }
        }
    }
?>
    <div class="container-fluid" style="padding-left: 5%; padding-top: 3%;">
        <div class="row">
            <div class="col-md-4">
                <h2>Login</h2>
                <p>Please fill in your credentials to login</p>
                <?php 
                if ($err_msg != '')
                {
                    echo '<div class="alert alert-danger" role="error">'.
                    $err_msg
                .'</div>';
                }
                
                ?>
                <form role="form" action="./login.php" method="post">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="<?php echo $password ?>"/>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>
                    <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>