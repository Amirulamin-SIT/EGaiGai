<?php 
    //register
    $email = "";
    $username = "";
    $password = "";
    $password_confirm = "";
    $err_msg = "";
?>

<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
</head>

<body>
    <?php 
    include "./header.php";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST['email']) && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password_confirm"]))
        {
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST["password"];
            $password_confirm = $_POST["password_confirm"];
            require_once "./functions/auth_validation.php";
            
            //get result and validate email 
            if (true !== $email_result = validate_email($email))
            {
                $err_msg = $email_result;
            }
            //ensure username is not blank
            elseif ($username == "") {
                $err_msg = "Username cannot be blank";
            }
            //get password validate password
            elseif (true !== $pass_result = validate_password($password)) {
                $err_msg = $pass_result;
            }
            //check if password and confirmation are same
            elseif ($password != $password_confirm) {
                $err_msg = "Password does not match";
            }
            // no errors then continue
            else
            {
                require_once "./functions/sql/auth.php";
                $result = signup($email, $username, $password);
                if($result !== true)
                {
                    $err_msg = $result;
                }
                else {
                    header('Location: ./login');
                }
            }
        }
    }
?>
    <div class="container-fluid" style="padding-left: 5%; padding-top: 3%;">
        <div class="row">
            <div class="col-md-4">
                <h2>Register</h2>
                <p>Register for an account with us!</p>
                <?php 
                if ($err_msg != '')
                {
                    echo '<div class="alert alert-danger" role="error">'.
                    $err_msg
                .'</div>';
                }
                
                ?>
                <form role="form" action="./register.php" method="post">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" />
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo $username ?>" />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            value="<?php echo $password ?>" />
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm"
                            value="<?php echo $password_confirm ?>" />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>
                    <p>Already have an account? <a href="./login.php">Login</a>.</p>
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