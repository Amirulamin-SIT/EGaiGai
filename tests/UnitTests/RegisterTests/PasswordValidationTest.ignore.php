<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/auth_validation.php";

final class PasswordValidationTest extends TestCase
{   
    //======== TEST CASES =========

    //valid password
    /**
     * @dataProvider valid_strong_password_provider
     **/ 
    /**
     * @dataProvider invalid_password_length_provider
     **/ 
    function test_validate_password_invalid_length($password)
    {
        $this->assertEquals("Password needs to be at least 8 characters long", validate_password($password));
    }

    // NOT USED IN JENKINS AS NO ACCESS TO API
    /**
     * @dataProvider invalid_weak_password_provider
     **/ 
    function test_validate_weak_password_invalid($password)
    {
        $this->assertStringContainsString("Compromised", validate_password($password));
    }


    // ========== PROVIDERS=========
    function valid_strong_password_provider()
    {
        return [["!@#456QweRty"]]; //valid strong password
    }

    function invalid_password_length_provider()
    {
        return [
        ["1234567"], //password < 8 chars long
        ];
    }

    function invalid_weak_password_provider()
    {
        return [
        ["P@ssw0rd"], //invalid weak password
        ];
    }
}
?>