<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/sms_functions.php";

final class SmsValidationTest extends TestCase
{   
    //======== TEST CASES =========

    //valid otp parameters
    /**
     * @dataProvider valid_otp_gen_provider
     **/ 
    function test_validate_otp_gen_valid($otplen, $alphnum)
    {
        $otp_regex = '/^[a-zA-Z0-9]{8,12}$/';
        $this->assertRegExp($otp_regex,generate_otp($otplen, $alphnum));
    }
    /**
     * @dataProvider invalid_otp_gen_provider
     **/ 
    function test_validate_otp_gen_invalid($otplen, $alphnum)
    {
        $this->expectException(InvalidArgumentException::class);
        generate_otp($otplen, $alphnum);
    }


    // ========== PROVIDERS=========
    function valid_otp_gen_provider()
    {
        return [[12,"0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ"]]; //valid otp generation
    }

    function invalid_otp_gen_provider()
    {
        return [
            [0,"0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ"], //invalid otplen parameter - boundary
            [-1,"0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ"], //invalid otplen parameter - -ve num
            ["abc","0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ"], //invalid otplen parameter - string
            [8,""], //invalid alphnum parameter - empty string
            [8,123456789] //invalid alphnum parameter - integers
        ];
    }
}
?>