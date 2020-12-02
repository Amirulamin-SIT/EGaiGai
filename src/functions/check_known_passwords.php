<?php

function check_known_password($password)
{  
    require_once dirname(__FILE__)."/crypto.php";
    $creds = parse_ini_string(decryptIni(".pwdchecker_creds.ini.enc"));
    $sha_pwd = strtoupper(sha1($password));
    $k_anon_sha_pwd = substr($sha_pwd, 0, 5);
    $xml = file_get_contents("$creds[pwd_api]$k_anon_sha_pwd");
    $result_array = explode("\n",$xml);
    if (array_search_partial($result_array, substr($sha_pwd, 5, strlen($sha_pwd)-5)) !=NULL)
    {
        $pwd_compromised = $result_array[array_search_partial($result_array, substr($sha_pwd, 5, strlen($sha_pwd)-5))];
        $compromise_count = substr($pwd_compromised, strpos($pwd_compromised, ':')+1);
        // echo "Your Password Has Been Compromised $compromise_count Times";

        return $compromise_count;
    }
    else
    {
        // echo "Your Password is SAFE";

        return False;
    }
}

function array_search_partial($arr, $keyword) {
    foreach($arr as $index => $string) {
        if (strpos($string, $keyword) !== FALSE)
            return $index;
    }
}

// PHP Single Quote would ignore variables usage
// Double quotes allow variables to be included inside of the string, while single quotes do not.
// check_known_password('P@ssw0rd');
?>