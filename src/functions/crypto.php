<?php
// function genIV(){
//     $iv = random_bytes(16);
//     file_put_contents("../.credentials/iv.env",$iv);
// }
// function encrypt(){
//     $pass = "FAKE PASSWORD OBVIOUSLY";
//     $iv = file_get_contents("../.credentials/iv.env");
//     $text = file_get_contents("../.credentials/.sql_creds.ini");
//     $pbkdf = hash_pbkdf2("sha256",$pass,$iv,20000,16);
//     $fileIV = random_bytes(16);
//     $cipherText = openssl_encrypt($text,"AES-256-CBC",$pbkdf,0,$fileIV);
//     $cipherText = $fileIV.$cipherText;
//     file_put_contents("../.credentials/.sql_creds.ini.enc",$cipherText);
// }
function decryptIni($fileName){
    $encrypted = file_get_contents(__DIR__."/../../.credentials/".$fileName);
    $fileiv = substr($encrypted,0,16);
    $encrypted = substr($encrypted,16);
    $iv = file_get_contents(__DIR__."./../../.credentials/iv.env");
    $pass = getenv('key');
    $pbkdf = hash_pbkdf2("sha256",$pass,$iv,20000,16);
    $plainText = openssl_decrypt($encrypted,"AES-256-CBC",$pbkdf,0,$fileiv);
    return $plainText;
}
?>