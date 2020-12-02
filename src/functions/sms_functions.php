<?php
require __DIR__ . '/../../vendor/autoload.php';
use Twilio\Rest\Client;

function send_sms($userhp, $otp)
{
  require_once dirname(__FILE__)."/crypto.php";
  $creds = parse_ini_string(decryptIni(".twilio_creds.ini.enc"));
  $account_sid = "$creds[acctSID]";
  $auth_token = "$creds[authToken]";
  $twilio_number = "$creds[hpNum]";

  $client = new Client($account_sid, $auth_token);
  $client->messages->create(
    "$userhp",
    array(
      'from' => $twilio_number,
      'body' => "Your 2FA verification code for E-GaiGai is {$otp}. Please be noted that it will expire in 5mins"
    )
  );
}

// random_int() uses random_bytes(), which is in turn cryptographically secure, 
// we can guarantee that the strings it produce have a uniform distribution of possible values which do not follow a predictable pattern.

function generate_otp($otp_len = 8, $alphnum = '0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ')
{
  if ($otp_len < 1) {
    throw new InvalidArgumentException('Length must be a positive integer');
  }
  $otp = '';
  if (!is_string($alphnum)){
    throw new InvalidArgumentException('Invalid alphabet');
  }
  $alphamax = strlen($alphnum) - 1;
  if ($alphamax < 1) {
    throw new InvalidArgumentException('Invalid alphabet');
  }
  for ($i = 0; $i < $otp_len; ++$i) {
    $otp .= $alphnum[random_int(0, $alphamax)];
  }
    return $otp;
}

?>