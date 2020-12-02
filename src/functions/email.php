<?php
require_once __DIR__ . '/../../vendor/autoload.php'; 
require_once __DIR__ ."/sms_functions.php";
require_once __DIR__. "/sql/auth.php";

function send_email($target_email){
    require_once dirname(__FILE__)."/crypto.php";
    $creds = parse_ini_string(decryptIni(".sendgrid_creds.ini.enc"));
    $encrypting_stuff = parse_ini_string(decryptIni(".encrypting_creds.ini.enc"));

    $token = generate_otp(9);

    $credentials['token'] = $token;
    $credentials['email'] = $target_email; 
    
    $to_encrypt = serialize($credentials);


    $chosen_cipher = "$encrypting_stuff[CIPHER]";
    $encryption_key = hex2bin("$encrypting_stuff[KEY]"); 
    $encryption_iv = hex2bin("$encrypting_stuff[IV]"); 
    $encryption = openssl_encrypt($to_encrypt,$chosen_cipher, $encryption_key, 0,$encryption_iv);

    $email_key = "$creds[SENDGRID_API_KEY]";
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("e.gaigai.sit@gmail.com", "E-GaiGai");
    $email->setSubject("E-GaiGai: Verify Your Account");
    $email->addTo($target_email, "Customer");
    $email->addContent(
        "text/html", 
        "<p>Dear Customer, </p> <br> <h1>Welcome to E-GaiGai!</h1> <br> 
        <p>Please click on the <a href=\"https://is32se.sitict.net/verify.php?creds=".base64_encode($encryption)."\">link</a> to verify your account. The link is valid for 5 minutes.</p> 
        <br>
        <p><i>Just in case you cannot click the link:<br> https://is32se.sitict.net/verify.php?creds=".base64_encode($encryption)."</i></p>
        <br>
        <p> Regards, </p>
        <p> E-GaiGai </p>
        "
    ); 
    $sendgrid = new \SendGrid($email_key);
    try {
        $response = $sendgrid->send($email);
        //print $response->statusCode() . "\n";
        //print_r($response->headers());
        //print $response->body() . "\n";
        date_default_timezone_set('Asia/Singapore');
        $token_datetime = date("Y-m-d H:i:s");
        update_token($token, $token_datetime, $target_email);
        return true;
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

function send_email_otp($target_email){
    require_once dirname(__FILE__)."/crypto.php";
    $creds = parse_ini_string(decryptIni(".sendgrid_creds.ini.enc"));

    $token = generate_otp(9);

    $email_key = "$creds[SENDGRID_API_KEY]";
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("e.gaigai.sit@gmail.com", "E-GaiGai");
    $email->setSubject("E-GaiGai: 2FA Verification Code");
    $email->addTo($target_email, "Customer");
    $email->addContent(
        "text/html", 
        "<p>Dear Customer, </p> <br> 
        <p>Your 2FA verification code for E-GaiGai is ". $token . ". The OTP is valid for 5 minutes.</p> 
        <br>
        <p> Regards, </p>
        <p> E-GaiGai </p>
        "
    ); 
    $sendgrid = new \SendGrid($email_key);
    try {
        $response = $sendgrid->send($email);
        //print $response->statusCode() . "\n";
        //print_r($response->headers());
        //print $response->body() . "\n";
        date_default_timezone_set('Asia/Singapore');
        $token_datetime = date("Y-m-d H:i:s");
        update_token($token, $token_datetime, $target_email);
        return true;
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

function send_reset_pwd_email($target_email){
    require_once dirname(__FILE__)."/crypto.php";
    $creds = parse_ini_string(decryptIni(".sendgrid_creds.ini.enc"));
    $encrypting_stuff = parse_ini_string(decryptIni(".encrypting_creds.ini.enc"));

    $token = generate_otp(9);

    $credentials['token'] = $token;
    $credentials['email'] = $target_email; 
    
    $to_encrypt = serialize($credentials);


    $chosen_cipher = "$encrypting_stuff[CIPHER]";
    $encryption_key = hex2bin("$encrypting_stuff[KEY]"); 
    $encryption_iv = hex2bin("$encrypting_stuff[IV]"); 
    $encryption = openssl_encrypt($to_encrypt,$chosen_cipher, $encryption_key, 0,$encryption_iv);

    $email_key = "$creds[SENDGRID_API_KEY]";
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("e.gaigai.sit@gmail.com", "E-GaiGai");
    $email->setSubject("E-GaiGai: Password Reset Request");
    $email->addTo($target_email, "Customer");
    $email->addContent(
        "text/html", 
        "<p>Dear Customer, </p> <br> <h1>Welcome to E-GaiGai!</h1> <br> 
        <p>A Reset Password has been Requested </p>
        <br>
        <p>Please click on the <a href=\"https://is32se.sitict.net/reset_password.php?creds=".base64_encode($encryption)."\">link</a> to reset the password for your account. The link is valid for 5 minutes.</p> 
        <br>
        <p><i>Just in case you cannot click the link:<br> https://is32se.sitict.net/reset_password.php?creds=".base64_encode($encryption)."</i></p>
        <br>
        <p> Regards, </p>
        <p> E-GaiGai </p>
        "
    ); 
    $sendgrid = new \SendGrid($email_key);
    try {
        $response = $sendgrid->send($email);
        //print $response->statusCode() . "\n";
        //print_r($response->headers());
        //print $response->body() . "\n";
        date_default_timezone_set('Asia/Singapore');
        $token_datetime = date("Y-m-d H:i:s");
        update_token($token, $token_datetime, $target_email);
        return true;
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

function send_change_password_email_confirmation($target_email){
    require_once dirname(__FILE__)."/crypto.php";
    $creds = parse_ini_string(decryptIni(".sendgrid_creds.ini.enc"));

    date_default_timezone_set('Asia/Singapore');
    $datetime = date("Y-m-d H:i:s");

    $email_key = "$creds[SENDGRID_API_KEY]";
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("e.gaigai.sit@gmail.com", "E-GaiGai");
    $email->setSubject("E-GaiGai: Successfully Changed Password");
    $email->addTo($target_email, "Customer");
    $email->addContent(
        "text/html", 
        "<p>Dear Customer, </p> <br> 
        <p>This Email is to inform you that you have successfully changed your password on ". $datetime . ".</p> 
        <br>
        <p> Regards, </p>
        <p> E-GaiGai </p>
        "
    ); 
    $sendgrid = new \SendGrid($email_key);
    try {
        $response = $sendgrid->send($email);
        //print $response->statusCode() . "\n";
        //print_r($response->headers());
        //print $response->body() . "\n";  
        return true;
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

?>
