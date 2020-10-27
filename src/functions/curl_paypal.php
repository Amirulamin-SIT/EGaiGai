<?php

function getToken()
{
    $creds = parse_ini_file("../../.credentials/paypal_creds.ini");
    

    $ch = curl_init("https://api.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Accept-Language: en_US'
    ));

    $userpwd = "$creds[client_id]:$creds[secret]";
    curl_setopt($ch, CURLOPT_USERPWD,
    $userpwd);

    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

    // sets curl to return as var instead of echoing
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    //get result
    $curl_result = curl_exec($ch);
    if(curl_error($ch)) {
        fwrite($fp, curl_error($ch));
        return false;
    }
    curl_close($ch);

    //get token and return
    $json = json_decode($curl_result, true);
    $token = $json["access_token"];
    return $token;
}
echo getToken();
?>