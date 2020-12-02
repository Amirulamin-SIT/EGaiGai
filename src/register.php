<?php 
    //register
    $email = "";
    $username = "";
    $password = "";
    $password_confirm = "";
    $err_msg = "";
    $phoneNumber ="";
    require_once dirname(__FILE__)."/crypto.php";
    $cred = parse_ini_string(decryptIni(".captcha.ini.enc"));
    $siteKey = $cred['sitekeyV2'];
?>

<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <title>Register</title>
</head>

<body>
    <?php 
    include "./header.php";
    if (isset($_SESSION["username"])) {
        header("Location: index.php");
    }
    require_once "./functions/email.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST['email']) && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password_confirm"]) && isset($_POST['g-recaptcha-response']) && isset($_POST['phoneNumber']))
        {
            $captcha=$_POST['g-recaptcha-response'];
            $email = $_POST['email'];
            $username = $_POST['username'];

            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            $phoneNumber = $_POST['phoneNumber'];
          
            require_once "./functions/auth_validation.php";
            require_once './functions/captcha.php';
            require_once './functions/dataValidation.php';
            
            //get result and validate email 
            if (true !== $email_result = validate_email($email))
            {
                $err_msg = $email_result;
            }
            //ensure username is not blank
            elseif ($username == "") {
                $err_msg = "Username cannot be blank";
            }elseif(checkForTag($username)){
                $err_msg = "Username cannot contain < or > or /";
                
            }
            //ensure hp is not blank
            elseif (true !== $hp_result = validate_hp($phoneNumber)) {
                $err_msg = $hp_result;
            }
            //get password validate password
            elseif (true !== $pass_result = validate_password($password)) {
                $err_msg = $pass_result;
            }
            //check if password and confirmation are same
            elseif ($password != $password_confirm) {
                $err_msg = "Password does not match";
            }elseif (strlen($phoneNumber) != 8 ){
                $err_msg = "Invalid phone number or please enter without +65";
            }elseif (!$captcha){
                $err_msg = "Please check the captcha form";
            }elseif(captchaV2($captcha)){
                $err_msg = "Bot detected, please resubmit registration.";
            }
            else
            {
                require_once "./functions/sql/auth.php";
                $phoneNumber = "+65".$phoneNumber;
                $username = xssSanit($username);
                $result = signup($email, $username, $password,$phoneNumber);
                if($result !== true)
                {
                    $err_msg = $result;
                }
                else {
                    send_email($email);
                    header('Location: ./handle.php');
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
                    $email = "";
                    $username = "";
                    $password = "";
                    $password_confirm = "";
                    $phoneNumber ="";
                    echo '<div class="alert alert-danger" role="error">'.
                    $err_msg
                .'</div>';
                }
                
                ?>
                <form role="form" action="./register.php" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" />
                    </div>
                    <div class="form-group">
                        <label for="lbPhone">Phone Number (+65)</label>
                        <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber"
                            value="<?php echo $phoneNumber ?>" placeholder="912345678" pattern="^[0-9]{8}$" required/>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo $username ?>" />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="off" required data-validation-required-message="Please enter your phone number"
                            value="<?php echo $password ?>" />
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirm" autocomplete="off" name="password_confirm"
                            value="<?php echo $password_confirm ?>" />
                    </div>
                    </br>
                    <div class="g-recaptcha" data-sitekey='<?php echo $siteKey; ?>'></div>
                    </br>
                    <button type="submit" class="btn btn-primary">
                        Register
                    </button>
                    <p>Already have an account? <a href="./login.php">Login</a>.</p>
                </form>
            </div>
        </div>
    </div>
</form>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    
    
</body>

</html>