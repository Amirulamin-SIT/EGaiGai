<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/auth_validation.php";

final class PasswordValidationTest extends TestCase
{   
    //======== TEST CASES =========

    //valid password
    /**
     * @dataProvider valid_password_provider
     **/ 
    function test_validate_password_valid($password)
    {
        $this->assertTrue(validate_password($password));
    }
    /**
     * @dataProvider invalid_password_length_provider
     **/ 
    function test_validate_password_invalid_length($password)
    {
        $this->assertEquals("Password needs to be more than 7 characters long", validate_password($password));
    }


    // ========== PROVIDERS=========
    function valid_password_provider()
    {
        return [["A12345678"]]; //valid password
    }

    function invalid_password_length_provider()
    {
        return [
        ["1234567"], //password < 8 chars long
        ];
    }
}
?>