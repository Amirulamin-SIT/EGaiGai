<?php
include "./header.php";
if (!isset($_SESSION["iduser"])) {
    header("Location: index.php");
} else {
    require_once "./functions/sql/auth.php";
    $user = $_SESSION["iduser"];
    $stmt = getUserDetails($user);
    $userDetail = mysqli_fetch_assoc($stmt);
    $userDetail['hp_num'] = trim($userDetail['hp_num'], '+65');
}
$err_msg = "";


if (isset($_POST['update'])) {
    $check = true;
    $username;
    if (isset($_POST['username'])) {
        require_once './functions/dataValidation.php';
        $username = $_POST['username'];

        if ($username == "") {
            $err_msg = "Username cannot be blank";
            $check = false;
        } elseif (checkForTag($_POST['username'])) {
            $err_msg = "Username cannot contain < or > or /";
            $check = false;
        }
    } else {
        $username = $_SESSION['username'];
    }
    if (isset($_POST['mobile'])) {
        require_once "./functions/auth_validation.php";
        if (true !== $hp_result = validate_hp($_POST['mobile'])) {
            $err_msg = $hp_result;
            $check = false;
        }

        $num = "+65" . $_POST['mobile'];
    } else {
        $num = $userDetail['hp_num'];
    }
    require_once "./functions/auth_validation.php";
    if ($check) {
        $email = mysqli_fetch_assoc(getUserDetails($_SESSION['iduser']))['email'];
        updateUserDetails($email, $username, $num);
        $_SESSION['message'] = "Profile has been updated!";
        $_SESSION['username'] = $username;
        header('location: ./user_profile.php');
    }
}
else if (isset($_POST['change_password'])){
    $email = mysqli_fetch_assoc(getUserDetails($_SESSION['iduser']))['email'];
    //Only verified accounts will be sent otp
    require_once "./functions/sms_functions.php";
    $otp = generate_otp(8);
    require_once "./functions/sql/auth.php";
    $hp = get_user_hp_by_email($email);
    send_sms($hp, $otp);
    require_once "./functions/sql/auth.php";
    update_user_otp($email, $otp);
    $_SESSION["num"] = 2;
    $_SESSION['email'] = $email;
    header('Location: ./otp_verify.php');
}
?>

<body>
    <div class="content-wrapper" style="margin-top:5%">
        <h1>Edit Profile</h1>
        <?php
        if ($err_msg != '') {
            echo '<div class="alert alert-danger" role="error">' .
                $err_msg
                . '</div>';
        }

        ?>
        <div class="panel-body col-md-12">
            <div class="col-md-6">
                <form class="form-horizontal" role="form" method="POST" action="" autocomplete="off">
                    <div class="form-group">
                        <label for="Username" class="col-md-3 control-label">Username</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="username" placeholder="username" value="<?php echo $userDetail['username']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-md-3 control-label">Mobile</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="mobile" placeholder="Mobile" value="<?php echo $userDetail['hp_num']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-9">
                            <button id="btn-signup" type="submit" name="update" value="update_account" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Save Changes</button>
                            <button id="btn-change-password" type="submit" name="change_password" value="change_password" class="btn btn-warning"><i class="icon-hand-right"></i> Change Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<?php include "./footer.php" ?>