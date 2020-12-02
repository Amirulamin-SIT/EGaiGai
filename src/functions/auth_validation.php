<?php
function validate_email($email)
{  
    $email_regex =  '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

    //if email doesn't match then no
    if (!preg_match($email_regex,$email))
    {
        return "Invalid Email";
    }

    return true;
}

function validate_password($password)
{
    require_once dirname(__FILE__)."/check_known_passwords.php";
    //validate pasword length
    if (strlen($password) < 8)
    {
        return "Password needs to be at least 8 characters long";
    }
    
    $leakedPwdResult = check_known_password($password);
    if ($leakedPwdResult !== FALSE)
    {
        return "Password input has been Has Been Compromised $leakedPwdResult Times. Please use another password!";
    }

    return true;
}

function validate_hp($hp)
{
    $hp_regex = '/^[0-9]{8}$/';

    //if hp doesn't match then no
    if (!preg_match($hp_regex,$hp))
    {
        return "Invalid Mobile Number.";
    }

    return true;
}

function validate_otp($otp)
{
    $otp_regex = '/^[a-zA-Z0-9]{8}$/';

    //if otp doesn't match then no
    if (!preg_match($otp_regex,$otp))
    {
        return "Invalid OTP";
    }

    return true;
}

?>