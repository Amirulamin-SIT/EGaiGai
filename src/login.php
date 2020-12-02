<?php

if (isset($_SESSION["username"])) {
    header("Location: ./index.php");
} else {
    $email = "";
    $password = "";
    $err_msg = "";
    require_once dirname(__FILE__)."/crypto.php";
    $cred = parse_ini_string(decryptIni(".captcha.ini.enc"));
    $siteKey = $cred['sitekeyV2'];
}
?>
<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <title>Sign In</title>
</head>

<body>
    <?php
    include "./header.php";
    if (isset($_SESSION["username"])) {
        header("Location: ./index.php");
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['email']) && isset($_POST["password"]) && isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
            require_once './functions/captcha.php';
            if (!$captcha) {
                $err_msg = "Please check the captcha form";
            } elseif (captchaV2($captcha)) {
                $err_msg = "Bot detected, please resubmit registration.";
            } else {
                $email = $_POST['email'];
                $password = $_POST["password"];
                require_once "./functions/sql/auth.php";
                if (true !== $login_error = login($email, $password)) {
                    $err_msg = $login_error;
                } else {
                    $verification_results = get_user_verification($email);
                    $verification_status = mysqli_fetch_assoc($verification_results);

                    if ($verification_status['verified'] == 1) {
                        //Only verified accounts will be sent otp
                        require_once "./functions/sms_functions.php";
                        $otp = generate_otp(8);
                        require_once "./functions/sql/auth.php";
                        $hp = get_user_hp_by_email($email);
                        send_sms($hp, $otp);
                        require_once "./functions/sql/auth.php";
                        update_user_otp($email, $otp);
                        $_SESSION["num"] = 1;
                        $_SESSION["email"]=$email;
                        session_write_close();
                        //echo print_r($_SESSION);
                        header('Location: ./otp_verify.php');
                    } else {
                        $err_msg = "Account is not verified. Please verify first before logging in.";
                    }
                }
            }
        } else {
            $err_msg = "Please fill Email and Password!";
        }
    }
    ?>

    <div class="centralized container-fluid" style="padding-left: 5%; padding-top: 3%;">
        <div class="row">
            <div class="col-md-4">
                <h2>Login</h2>
                <p>Please fill in your credentials to login</p>
                <?php
                if ($err_msg != '') {
                    echo '<div class="alert alert-danger" role="error">' .
                        $err_msg
                        . '</div>';
                }
                ?>
                <form role="form" action="./login.php" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" autocomplete="off" id="password" name="password" value="<?php echo $password ?>" />
                    </div>
                    <div class="g-recaptcha" style="padding-bottom:10px" data-sitekey='<?php echo $siteKey; ?>'></div>
                    <button type="submit" class="col-md-3 btn btn-primary"> Login </button>
                    <p style="margin-top: 5px">Don't have an account? <a href="register.php">Sign up now</a>.</p>
                    <p style="margin-top: -15px;"><a href="forgotten.php">Forgotten Password?</a></p>
                </form>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>
<?php include "./footer.php" ?>

</html>