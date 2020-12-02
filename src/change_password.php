<?php 
include "./header.php";
if(!isset($_SESSION["iduser"]))
{
    session_unset();
    session_destroy();
    header('Location: ./index.php');
}

$err_msg = "";
require_once __DIR__ ."/functions/sql/auth.php";
require_once __DIR__ ."/functions/auth_validation.php";
require_once __DIR__ ."/functions/email.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST["password_confirm"]))
    {
        $uid = $_SESSION["iduser"];
        $stmt = getUserDetails($uid);
        $userDetails = mysqli_fetch_assoc($stmt);

        $current_password = $_POST['current_password'];
        $password_hash = $userDetails['password_hash'];
        $email = $userDetails['email'];
        if(!password_verify($current_password, $password_hash)){
            $err_msg = "Invalid Credentials";
        }
        else{
            $password = $_POST['new_password'];
            $password_confirm = $_POST['password_confirm'];
            if (true !== $pass_result = validate_password($password)) 
            {
                $err_msg = $pass_result;
            }
            elseif($current_password == $password){
                $err_msg = "You've entered an old password.";
            }
            //check if password and confirmation are same
            elseif ($password != $password_confirm) 
            {
                $err_msg = "New Passwords does not match";
            }
            else
            {
                $result = update_pwd($email, $password);
                if($result !== true)
                {
                    $err_msg = $result;
                }
                else {
                    send_change_password_email_confirmation($email);
                    header('Location: ./change_password_confirmation.php');
                }
            }
        }
    }

}
?>


<body>
<?php 
    if ($err_msg != '')
    {
        echo '<div class="alert alert-danger" role="error">'.
        $err_msg
    .'</div>';
    }
    
    ?>
<div style="position: fixed; top: 50%; left:50%; transform: translate(-50%, -50%);">
        <h3>Change Password</h3>
        <div style="width:450px;">
            <form class=" form-horizontal" role="form" method="POST" action="" autocomplete="off">
                <div class="form-group">
                    <label for="passwrd" class="control-label">Current Password</label>
                    <input type="password" class="col-md-12 form-control" autocomplete="off" name="current_password" placeholder="Current password">
                </div>
                <div class="form-group">
                    <label for="passwrd" class="control-label">New Password</label>
                    <input type="password" class="col-md-12 form-control" autocomplete="off" name="new_password" placeholder="New password">
                </div>
                <div class="form-group">
                    <label for="curpass" class="control-label">Confirm Password</label>
                    <input type="password" class="col-md-12 form-control" autocomplete="off" name="password_confirm" placeholder="Confirm password">
                </div>
                <div class="form-group">
                    <button id="btn-reset" type="submit" name="submit" class="btn btn-info"><i class="icon-hand-right"></i>Change Password</button>
                </div>

            </form>

        </div>
    </div>
</body>
<?php include "./footer.php" ?>