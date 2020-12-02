<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/verifying_time.php";

final class OtpTimeValidationTest extends TestCase
{   
    //======== TEST CASES =========

    //valid OTP Token Time
    /**
     * @dataProvider valid_otp_time_provider
     **/ 
    function test_validate_otp_time_valid($token_time)
    {
        $this->assertTrue(checking_token_timing($token_time));
    }
    /**
     * @dataProvider invalid_otp_time_provider
     **/ 
    function test_validate_otp_time_invalid($token_time)
    {
        $this->assertFalse(checking_token_timing($token_time));
    }


    // ========== PROVIDERS=========
    function valid_otp_time_provider()
    {
        date_default_timezone_set('Asia/Singapore');
        return [[$current_timing = date("Y-m-d H:i:s")]]; //valid otp time
    }

    function invalid_otp_time_provider()
    {
        date_default_timezone_set('Asia/Singapore');
        $expired_time = strtotime("2020-11-11 11:11:11");
        $future_time = strtotime("2022-11-11 11:11:11");

        return [
            [$expired_time], //invalid otp time - expired time
            [$future_time] //invalid otp time - future time
        ];
    }
}
?>