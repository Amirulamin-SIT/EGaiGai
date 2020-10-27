<?php

require_once dirname(__FILE__)."/sql.php";

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

function login($email, $password)
{
    $conn = get_conn();
    //get user by email
    $res  = get_user_by_email($email, $conn);
    $num_rows = mysqli_num_rows($res);

    // if number of rows is not exactly one return false
    if ($num_rows != 1)
    {
        return false;
    }

    //retrieve values from row
    $rows = $res->fetch_all();
    $row = $rows[0];
    $uid = $row[0];
    $username = $row[1];
    $pw_hash = $row[2];
    $verified = $row[3];

    //password check
    if (!password_verify($password, $pw_hash))
    {
        //increase values of failed logins
        $db_fail_count = $conn->prepare("UPDATE user SET login_fail_amt = login_fail_amt + 1 WHERE iduser = ?");
        $db_fail_count->bind_param("s", $uid);
        if (!$db_fail_count->execute()) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $db_fail_count->close();
        $conn->close();
        return false;
    }
    //if password matches then start sesh
    $_SESSION["username"] = $username;

    //close conn
    $conn->close();
    return true;
}


function signup($email, $username, $password)
{
    $conn = get_conn();

    //check if email already exists
    $res = get_user_by_email($email, $conn);
    $rows = mysqli_num_rows($res);
    if ($rows != 0)
    {
        return "Email already in use";
    }

    //if email is not used then insert intp user table
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $db_signup = $conn->prepare("INSERT INTO user SET email = ?, password_hash = ?, username =?;");
    $db_signup->bind_param("sss", $email, $password_hash, $username);
    
    if (!$db_signup->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $db_signup->close();
    $conn->close();
    return true;
}
?>