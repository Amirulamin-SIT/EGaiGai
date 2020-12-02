<?php
require_once "./functions/sql/auth.php";
require_once "./functions/verifying_time.php";

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

$verified_results = get_user_verification($user_email);
$user_verification_status = mysqli_fetch_assoc($verified_results);

if($user_verification_status['verified'] == 1){ //user is already verified
    header('Location: ./login.php'); //redirect them to login page
}
else{ //user account not verified yet
    $results = get_token_and_time($user_email);
    $userResult = mysqli_fetch_assoc($results);

    $user_assigned_token = $userResult['token'];
    $assigned_token_timing = $userResult['token_time'];

    $timing_results = checking_token_timing($assigned_token_timing);

    if($timing_results == true){
        echo $user_token;
        echo "<br>";
        echo $user_assigned_token;
        echo "<br>";
        if($user_token == $user_assigned_token){
            update_user_verification($user_email);
            $url_params['status'] = "yes";
            header('Location: ./user_account_verification.php?values='.urlencode(serialize($url_params)));
        }
        else{
            $url_params['status'] = "no";
            $url_params['email'] = base64_encode($user_email);
            header('Location: ./user_account_verification.php?values='.urlencode(serialize($url_params)));
        }
    }
    else{
        $url_params['status'] = "no";
        $url_params['email'] = base64_encode($user_email);
        header('Location: ./user_account_verification.php?values='.urlencode(serialize($url_params)));
    }
}


?>