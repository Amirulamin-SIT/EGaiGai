<?php
  function captchaV2($captcha){
    $cred = parse_ini_string(decryptIni(".captcha.ini.enc"));
    $secretKey = $cred['keyV2'];
    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response,true);
    if($responseKeys["success"]) {
      return 0;
    } else {
      return 1;
    }
  }
?>