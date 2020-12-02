<?php

function checking_token_timing($entered_timing, $token_valid_duration=5){
    date_default_timezone_set('Asia/Singapore');
    
    $current_timing = date("Y-m-d H:i:s");
    $minutes_elapsed = (time() - strtotime($entered_timing))/60;
    
    $token_validity = false;
    if($minutes_elapsed > $token_valid_duration){
        return $token_validity; // token invalid or token expired
    }
    else{
        $token_validity = true;
        return $token_validity; // token is still valid ( must go through otp validation)
    }

}

?>