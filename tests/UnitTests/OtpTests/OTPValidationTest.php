<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/auth_validation.php";

final class OTPValidationTest extends TestCase
{   
    //======== TEST CASES =========

    //valid OTP Token
    /**
     * @dataProvider valid_otp_provider
     **/ 
    function test_validate_hp_valid($otp)
    {
        $this->assertTrue(validate_otp($otp));
    }
    //Invalid OTP Token
    /**
     * @dataProvider invalid_otp_provider
     **/ 
    function test_validate_hp_invalid($otp)
    {
        $this->assertEqualsIgnoringCase("Invalid OTP",validate_otp($otp));
    }


    // ========== PROVIDERS=========
    function valid_otp_provider()
    {
        return [["AbCd1234"]]; //valid otp
    }

    function invalid_otp_provider()
    {
        return [
            ["%$@#^$&"] //invalid otp - special characters
        ];
    }
}
?>