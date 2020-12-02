<?php
include "./header.php";

$password = "";
$password_confirm = "";
$err_msg = "";
$timing_results = "";
$user_email = "";

require_once "./functions/sql/auth.php";
require_once "./functions/verifying_time.php";
require_once "./functions/auth_validation.php";
require_once __DIR__ ."/functions/email.php";

//verify account landing page


require_once dirname(__FILE__)."/crypto.php";
$encrypting_stuff = parse_ini_string(decryptIni(".encrypting_creds.ini.enc"));
$chosen_cipher = "$encrypting_stuff[CIPHER]";
$decryption_key = hex2bin("$encrypting_stuff[KEY]"); 
$decryption_iv = hex2bin("$encrypting_stuff[IV]"); 


$to_decrypt = base64_decode($_GET['creds']);

$decryption=openssl_decrypt ($to_decrypt, $chosen_cipher,  $decryption_key, 0, $decryption_iv); 

$url_paramss= unserialize($decryption);

$user_token = $url_paramss['token']; 
$user_email = $url_paramss['email'];

$results = get_token_and_time($user_email);
$userResult = mysqli_fetch_assoc($results);

$user_assigned_token = $userResult['token'];
$assigned_token_timing = $userResult['token_time'];

$timing_results = checking_token_timing($assigned_token_timing);    


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST['password']) && isset($_POST["password_confirm"]))
    {
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        //get password validate password
        if (true !== $pass_result = validate_password($password)) 
        {
            $err_msg = $pass_result;
        }
        //check if password and confirmation are same
        elseif ($password != $password_confirm) 
        {
            $err_msg = "Password does not match";
        }
        else
        {
            require_once "./functions/sql/auth.php";
            $result = update_pwd($user_email, $password);
            if($result !== true)
            {
                $err_msg = $result;
            }
            else {
                send_change_password_email_confirmation($user_email);
                header('Location: ./reset_pwd_handle.php');
            }
        }
    }

}


?>
<?php if($timing_results == true) : ?>
    <?php 
    if ($err_msg != '')
    {
        echo '<div class="alert alert-danger" role="error">'.
        $err_msg
    .'</div>';
    }
    
    ?>
    <div style="position: fixed; top: 50%; left:50%; transform: translate(-50%, -50%);">
        <h3>Reset Password</h3>
        <div style="width:450px;">
            <form class=" form-horizontal" role="form" method="POST" action="" autocomplete="off">
                <p>Please enter your new password.</p>
                <div class="form-group">
                    <label for="passwrd" class="control-label">New Password</label>
                    <input type="password" class="col-md-12 form-control" name="password" autocomplete="off" placeholder="New password">
                </div>
                <div class="form-group">
                    <label for="curpass" class="control-label">Confirm Password</label>
                    <input type="password" class="col-md-12 form-control" name="password_confirm" autocomplete="off" placeholder="Confirm password">
                </div>
                <div class="form-group">
                    <button id="btn-reset" type="submit" name="submit" class="btn btn-info"><i class="icon-hand-right"></i>Reset Password</button>
                </div>

            </form>

        </div>
    </div>
<?php else : ?>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1>Invalid Password Reset Request</h1>     
            <p>This may be because your password reset link has expired!</p> 
            <p>Please reset password via forget password method again!</p>
        </div>
    </div>
<?php endif; ?>



<?php include "./footer.php" ?>