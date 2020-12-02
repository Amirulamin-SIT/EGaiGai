<?php
include "./header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST['email']))
        {
            $email = $_POST['email'];

            require_once "./functions/auth_validation.php";
            //get result and validate email 
            if (true !== $email_result = validate_email($email))
            {
                $err_msg = $email_result;
            }
            else
            {
                require_once "./functions/sql/auth.php";
                $result = mysqli_num_rows(get_user_by_email($email));
                if($result == 1){
                    require_once "./functions/email.php";
                    send_reset_pwd_email($email);
                }
                header('Location: ./forget_pwd_handle.php');
            }
        }
    }
    ?>

<body>
    <div style="position: fixed; top: 50%; left:50%; transform: translate(-50%, -50%);">
        <h3>Forgot your Password?</h3>
        <form class="form-horizontal" role="form" method="POST" action="" autocomplete="off">
            <p>Please enter your email address to reset your password.</p>
            <div class="form-group">
                <input type="text" class="col-md-6 form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <button id="btn-reset" type="submit" name="submit" class="btn btn-info"><i class="icon-hand-right"></i>Reset Password</button>
            </div>

        </form>
    </div>
</body>

<?php include "./footer.php" ?>