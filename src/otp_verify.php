<?php
    include "./header.php";
    if(!isset($_SESSION["email"])){
        //header('Location: ./index.php');
    }
    else{
        $otp = "";
        $err_msg = "";
        require_once dirname(__FILE__)."/crypto.php";
        $cred = parse_ini_string(decryptIni(".captcha.ini.enc"));
        $siteKey = $cred['sitekeyV2'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST['send_sms_otp_button']))
        {
            require_once "./functions/sms_functions.php";
            $otp_token = generate_otp(8);
            require_once "./functions/sql/auth.php";
            $hp = get_user_hp_by_email($_SESSION["email"]);
            //send_sms($hp, $otp_token);
            update_user_otp($_SESSION["email"],$otp_token);
        }
        else if(isset($_POST['send_email_otp_button']))
        {
            require_once "./functions/email.php";
            send_email_otp($_SESSION["email"]);
        }
        else if(isset($_POST['sms_button']))
        {
            if (isset($_POST['otp']) && isset($_POST['g-recaptcha-response']))
            {
                $captcha=$_POST['g-recaptcha-response'];
                require_once './functions/captcha.php';
                if(!$captcha){
                    $err_msg = "Please check the captcha form";
                }elseif(captchaV2($captcha)){
                    $err_msg = "Bot detected, please resubmit registration.";
                }else{
                    $otp = $_POST['otp'];
                    require_once "./functions/sql/auth.php";
                    require_once "./functions/auth_validation.php";
                    if (true !== $otp_result = validate_otp($otp))
                    {
                        $err_msg = $otp_result;
                    }
                    elseif(true !== $otp_result = otp_verify($_SESSION["email"],$otp))
                    {
                        if ($otp_result === 3)
                        {
                            $err_msg = "Too many failed attempts. Please try again later!";
                            header('Location: ./index.php');
                        }
                        else
                        {
                            $err_msg = $otp_result;
                        }
                    
                    }
                    else {
                        if($_SESSION["num"] == 1){
                            unset($_SESSION['num']);
                            unset($_SESSION['email']);
                            header('Location: ./index.php');
                        }
                        else if ($_SESSION["num"] == 2){
                            unset($_SESSION['num']);
                            unset($_SESSION['email']);
                            header('Location: ./change_password.php');
                        }
                    }
                }
            }
        }
        else if (isset($_POST['email_button']))
        {
            if (isset($_POST['otp1']) && isset($_POST['g-recaptcha-response']))
            {
                $captcha=$_POST['g-recaptcha-response'];
                require_once './functions/captcha.php';
                if(!$captcha){
                    $err_msg = "Please check the captcha form";
                }elseif(captchaV2($captcha)){
                    $err_msg = "Bot detected, please resubmit registration.";
                }else{
                    $otp = $_POST['otp1'];
                    require_once "./functions/sql/auth.php";
                    require_once "./functions/auth_validation.php";
                    if (true !== $otp_result = validate_otp($otp))
                    {
                        $err_msg = $otp_result;
                    }
                    elseif(true !== $otp_result = otp_verify($_SESSION["email"],$otp))
                    {
                        if ($otp_result === 3)
                        {
                            $err_msg = "Too many failed attempts. Please try again later!";
                            header('Location: ./index.php');
                        }
                        else
                        {
                            $err_msg = $otp_result;
                        }
                    
                    }
                    else {
                        
                       if($_SESSION["num"] == 1){
                            unset($_SESSION['num']); 
                            unset($_SESSION['email']);  
                            header('Location: ./index.php');
                        }
                        else if ($_SESSION["num"] == 2){
                            unset($_SESSION['num']); 
                            unset($_SESSION['email']);
                            header('Location: ./change_password.php');
                        }
                    }
                }
            }
        }
    }
    
    
?>

<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <title>Verify OTP</title>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" crossorigin="anonymous"></script>
    <style>
        nav > .nav.nav-tabs{

            border: none;
            color:#fff;
            background:#272e38;
            border-radius:0;

        }
        nav > div a.nav-item.nav-link,
        nav > div a.nav-item.nav-link.active
        {
            border: none;
            padding: 18px 25px;
            color:#fff;
            background:#272e38;
            border-radius:0;
        }

        nav > div a.nav-item.nav-link.active:after
        {
            content: "";
            position: relative;
            bottom: -60px;
            left: -10%;
            border: 15px solid transparent;
            border-top-color: #e74c3c ;
        }
        .tab-content{
            background: #fdfdfd;
            line-height: 25px;
            border: 1px solid #ddd;
            border-top:5px solid #e74c3c;
            border-bottom:5px solid #e74c3c;
            padding:30px 25px;
        }

        nav > div a.nav-item.nav-link:hover,
        nav > div a.nav-item.nav-link:focus
        {
            border: none;
            background: #e74c3c;
            color:#fff;
            border-radius:0;
            transition:background 0.20s linear;
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="padding-left: 5%; padding-top: 3%;">
        <div class="row">
                <div class="col-md-11 ">
                  <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                      <a class="nav-item nav-link active" id="nav-sms-tab" data-toggle="tab" href="#nav-sms" role="tab" aria-controls="nav-sms" aria-selected="true">SMS OTP</a>
                      <a class="nav-item nav-link" id="nav-email-tab" data-toggle="tab" href="#nav-email" role="tab" aria-controls="nav-email" aria-selected="false">Email OTP</a>
                    </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-sms" role="tabpanel" aria-labelledby="nav-sms-tab">
                        <h2>Verify OTP</h2>
                        <p>Please enter the OTP sent to your mobile to login</p>
                        <?php 
                            if ($err_msg != '')
                            {
                                echo '<div class="alert alert-danger" role="error">'.
                                $err_msg
                            .'</div>';
                            } 
                        ?>
                        <form role="form" action="./otp_verify.php" method="post" autocomplete="off">
                            <div class="form-group">
                                <label for="otp">OTP Token Value</label>
                                <input type="otp" class="form-control" id="otp" name="otp" value="<?php echo $otp ?>"/>
                                <button type="submit" name="send_sms_otp_button" value="send_sms_otp" class="btn btn-primary">Resend OTP via SMS</button>
                            </div>
                            <div class="g-recaptcha" data-sitekey='<?php echo $siteKey; ?>'></div>
                            <button type="submit" name="sms_button" value="SMS_OTP" class="btn btn-primary">
                                Verify
                            </button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="nav-email" role="tabpanel" aria-labelledby="nav-email-tab">
                        <h2>Verify OTP</h2>
                        <p>Please enter the OTP sent to your email address to login</p>
                        <?php 
                            if ($err_msg != '')
                            {
                                echo '<div class="alert alert-danger" role="error">'.
                                $err_msg
                            .'</div>';
                            } 
                        
                        ?>
                        <form role="form" action="./otp_verify.php" method="post" autocomplete="off">
                            <div class="form-group">
                                <label for="otp">OTP Token Value</label>
                                <input type="otp" class="form-control" id="otp1" name="otp1" value="<?php echo $otp ?>"/>
                                <button type="submit" name="send_email_otp_button" value="send_email_otp" class="btn btn-primary">Send / Resend OTP via Email</button>
                            </div>
                            <div class="g-recaptcha" data-sitekey='<?php echo $siteKey; ?>'></div>
                            <button type="submit" name="email_button" value="Email_OTP" class="btn btn-primary">
                                Verify
                            </button>
                        </form>
                    </div>
                  </div>
                
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