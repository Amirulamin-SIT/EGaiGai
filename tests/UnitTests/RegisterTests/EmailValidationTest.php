<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/auth_validation.php";

final class EmailValidationTest extends TestCase
{   
    //======== TEST CASES =========

    //valid email
    /**
     * @dataProvider valid_email_provider
     **/ 
    function test_validate_email_valid($email)
    {
        $this->assertTrue(validate_email($email));
    }
    /**
     * @dataProvider invalid_email_provider
     **/ 
    function test_validate_email_invalid($email)
    {
        $this->assertEquals("Invalid Email", validate_email($email));
    }


    // ========== PROVIDERS=========
    function valid_email_provider()
    {
        return [["yes@yes.com"]]; //valid email
    }

    function invalid_email_provider()
    {
        return [
            ["test"], //string
            [""], //empty string
            ["example.com"], //no @ in string
            ["A@b@c@domain.com"], //only one @ allowed
        ];
    }
}
?>