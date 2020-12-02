<?php

require_once dirname(__FILE__)."/sql.php";
require_once __DIR__ . "/../verifying_time.php";

// ========== USER AUTHENTICATION ==========
function get_user_by_email($email, $conn="")
{
    if ($conn == "")
    {
        $conn = get_conn();
        $internal_conn = true;
    }
    
    //get matching credentials if email exists
    $db_user = $conn->prepare("SELECT iduser, username, password_hash, verified FROM user WHERE email=?");
    $db_user->bind_param("s", $email);
    if (!$db_user->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_user->get_result();
    //close only if conn is internal
    $db_user->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }
    
    return $res;
}
function getUserByPhone($phone, $conn=""){
    if ($conn == "")
    {
        $conn = get_conn();
        $internal_conn = true;
    }
    
    //get matching credentials if email exists
    $db_user = $conn->prepare("SELECT iduser, username, password_hash, verified FROM user WHERE hp_num=?");
    $db_user->bind_param("s", $phone);
    if (!$db_user->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_user->get_result();
    //close only if conn is internal
    $db_user->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }
    
    return $res;
}

function get_user_otpstuff_by_email($email, $conn="")
{
    if ($conn == "")
    {
        $conn = get_conn();
        $internal_conn = true;
    }
    
    //get matching credentials if email exists
    $db_user = $conn->prepare("SELECT iduser, login_fail_amt, token, token_time, username FROM user WHERE email=?");
    $db_user->bind_param("s", $email);
    if (!$db_user->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_user->get_result();
    //close only if conn is internal
    $db_user->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }
    
    return $res;
}

function get_user_hp_by_email($email, $conn="")
{
    if ($conn == "")
    {
        $conn = get_conn();
        $internal_conn = true;
    }
    
    //get matching credentials if email exists
    $db_user = $conn->prepare("SELECT hp_num FROM user WHERE email=?");
    $db_user->bind_param("s", $email);
    if (!$db_user->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_user->get_result();
    //close only if conn is internal
    $db_user->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }
    
    $num_rows = mysqli_num_rows($res);

    // if number of rows is not exactly one return false
    if ($num_rows != 1)
    {
        return "Invalid username or password";
    }

    //retrieve values from row
    $rows = $res->fetch_all();
    $row = $rows[0];
    $hp = $row[0];

    return $hp;
}

function login($email, $password)
{
    $conn = get_conn();
    //get user by email
    $res  = get_user_by_email($email, $conn);
    $num_rows = mysqli_num_rows($res);

    // if number of rows is not exactly one return false
    if ($num_rows != 1)
    {
        return "Invalid username or password";
    }

    //retrieve values from row
    $rows = $res->fetch_all();
    $row = $rows[0];
    $uid = $row[0];
    $username = $row[1];
    $pw_hash = $row[2];
    $verified = $row[3];
    
    //Check Password
    if (!password_verify($password, $pw_hash))
    {
        //increase values of failed logins
        $db_inc_fail_count = $conn->prepare("UPDATE user SET login_fail_amt = login_fail_amt + 1 WHERE iduser = ?");
        $db_inc_fail_count->bind_param("s", $uid);
        if (!$db_inc_fail_count->execute()) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $db_inc_fail_count->close();
        $conn->close();
        return "Invalid username or password";
    }

    //get number of failures first
    $db_fail_count = $conn->prepare("SELECT login_fail_amt FROM user WHERE iduser = ?");
    $db_fail_count->bind_param("s", $uid);
    if (!$db_fail_count->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $db_fail_count_res = $db_fail_count->get_result();
    $row = $db_fail_count_res->fetch_row();
    $num_failures = $row[0];
    $db_fail_count->close();

    // TO DECIDE IF WANT TO INCLUDE LOCKOUT -> IF NOT THEN REMOVE ALL num_faliures Counts
    // if ($num_failures > 5)
    // {
    //     return "Too many failed login attempts";
    // }

    // //if password matches then start sesh
    // $_SESSION["username"] = $username;
    $_SESSION["email"] = $email;
    // $_SESSION["iduser"] = $uid;
        
    //reset fails to 0 and close conn
    $db_zero_fail_count = $conn->prepare("UPDATE user SET login_fail_amt = 0 WHERE iduser = ?");
    $db_zero_fail_count->bind_param("s", $uid);
    if (!$db_zero_fail_count->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $db_zero_fail_count->close();
    $conn->close();
    return true;
}


function signup($email, $username, $password, $phoneNumber)
{
    $conn = get_conn();

    //check if email already exists
    $res = get_user_by_email($email, $conn);
    $rows = mysqli_num_rows($res);
    if ($rows != 0)
    {
        return "Email already in use";
    }
    $res = getUserByPhone($phoneNumber,$conn);
    $rows = mysqli_num_rows($res);

    if ($rows != 0)
    {
        return "Phone Number already in use";
    }
    //if email is not used then insert intp user table
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $db_signup = $conn->prepare("INSERT INTO user SET email = ?, password_hash = ?, username =?, hp_num=?;");
    $db_signup->bind_param("ssss", $email, $password_hash, $username, $phoneNumber);
    
    if (!$db_signup->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $db_signup->close();
    $conn->close();
    return true;
}


//Update Email Token / OTP Token
function update_token($token, $token_datetime, $email_address){
    $conn = get_conn();
    
    $db_set_token = $conn->prepare("UPDATE user SET token = ?, token_time = ? WHERE email = ?");
    $db_set_token->bind_param("sss", $token, $token_datetime, $email_address);
    if (!$db_set_token->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $db_set_token->close();
    $conn->close();

}

//retrieve token
function get_token_and_time($email)
{
    $conn = get_conn();
    //get matching credentials if email exists
    $db_user = $conn->prepare("SELECT token, token_time FROM user WHERE email=?");
    $db_user->bind_param("s", $email);
    if (!$db_user->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_user->get_result();
    //close only if conn is internal
    $db_user->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }
    
    return $res;
}

function update_user_verification($email_address){
    $conn = get_conn();
    $verified = 1;

    $db_set_token = $conn->prepare("UPDATE user SET verified = ?, token = null, token_time = null WHERE email = ?");
    $db_set_token->bind_param("is", $verified, $email_address);
    if (!$db_set_token->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $db_set_token->close();
    $conn->close();
}
function update_user_otp($email, $otp)
{
    $conn = get_conn();

    date_default_timezone_set('Asia/Singapore');
    $otp_datetime = date("Y-m-d H:i:s");

    //check if email already exists
    // $res = get_user_by_email($email, $conn);
    // $rows = mysqli_num_rows($res);
    $db_update_user_token = $conn->prepare("UPDATE user SET token = ?, token_time = ? WHERE email = ?;");
    $db_update_user_token->bind_param("sss", $otp, $otp_datetime, $email);

    if (!$db_update_user_token->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $db_update_user_token->close();
    $conn->close();
    return true;    
}

function otp_verify($email, $otp)
{
    $conn = get_conn();
    //get user by email
    $res  = get_user_otpstuff_by_email($email, $conn);
    $num_rows = mysqli_num_rows($res);

    // if number of rows is not exactly one return false
    if ($num_rows != 1)
    {
        return "Invalid OTP";
    }

    //retrieve values from row 
    $rows = $res->fetch_all();
    $row = $rows[0];
    $uid = $row[0];
    $num_failures = $row[1];
    $token = $row[2];
    $token_time = $row[3];
    $username = $row[4];

    // TO DECIDE IF WANT TO INCLUDE LOCKOUT -> IF NOT THEN REMOVE ALL num_faliures Counts
    // if ($num_failures > 5)
    // {
    //     return 3;
    // }

    if(checking_token_timing($token_time) == true){ //otp expired
        if ($otp != $token){
            //increase values of failed logins
            $db_inc_fail_count = $conn->prepare("UPDATE user SET login_fail_amt = login_fail_amt + 1 WHERE iduser = ?");
            $db_inc_fail_count->bind_param("s", $uid);
            if (!$db_inc_fail_count->execute()) {
                echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            $db_inc_fail_count->close();
            $conn->close();
            return "Invalid OTP";
        }
    
        //if OTP matches then start sesh
        $_SESSION["username"] = $username;
        $_SESSION["iduser"] = $uid;
        unset($_SESSION['email']);
    
        //reset fails to 0 and close conn
        $db_zero_fail_count = $conn->prepare("UPDATE user SET login_fail_amt = 0, token = null, token_time = null WHERE iduser = ?");
        $db_zero_fail_count->bind_param("s", $uid);
        if (!$db_zero_fail_count->execute()) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $db_zero_fail_count->close();
        $conn->close();
        
        return true;
    }
    else{
        return "OTP Expired";
    }
}

//retrieve token
function get_user_verification($email)
{
    $conn = get_conn();
    //get matching credentials if email exists
    $db_user = $conn->prepare("SELECT verified FROM user WHERE email=?");
    $db_user->bind_param("s", $email);
    if (!$db_user->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_user->get_result();
    //close only if conn is internal
    $db_user->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }
    
    return $res;
}
function getUserDetails($user){
    $conn = get_conn();
    $db_user = $conn->prepare('SELECT * FROM user WHERE iduser=?');
    $db_user->bind_param("s",$user);
    if (!$db_user->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_user->get_result();
    $db_user->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }
    
    return $res;
}
function updateUserDetails($email,$username,$num){
    $conn = get_conn();
    $db_user = $conn->prepare('UPDATE user SET username=?, hp_num=? WHERE email=?');
    $db_user->bind_param("sss",$username,$num,$email);
    if (!$db_user->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_user->get_result();
    $db_user->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }
}
function getUserId($email){
    $conn = get_conn();
    $db_user = $conn->prepare('SELECT iduser FROM user WHERE email=?');
    $db_user->bind_param("s",$user);
    if (!$db_user->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_user->get_result();
    $db_user->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }
    
    return $res;
}

function update_pwd($email, $password)
{
    $conn = get_conn();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $db_update_pwd = $conn->prepare("UPDATE user SET password_hash = ?, token = null, token_time = null WHERE email = ?");
    $db_update_pwd->bind_param("ss", $password_hash, $email);
    
    if (!$db_update_pwd->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_update_pwd->get_result();
    echo $res;
    $db_update_pwd->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }

    return true;
}

function getRole($userid){
    $conn = get_conn();
    $db_user = $conn->prepare('SELECT type FROM user WHERE iduser=?');
    $db_user->bind_param("s",$userid);
    if (!$db_user->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $res = $db_user->get_result();
    $db_user->close();
    if(isset($internal_conn))
    {
        $conn->close();
    }
    
    return $res;
}


?>