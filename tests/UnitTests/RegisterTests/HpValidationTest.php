<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/auth_validation.php";

final class HpValidationTest extends TestCase
{   
    //======== TEST CASES =========

    //valid HP Number
    /**
     * @dataProvider valid_hp_provider
     **/ 
    function test_validate_hp_valid($hp)
    {
        $this->assertTrue(validate_hp($hp));
    }
    //Invalid HP Number
    /**
     * @dataProvider invalid_hp_provider
     **/ 
    function test_validate_hp_invalid($hp)
    {
        $this->assertEqualsIgnoringCase("Invalid Mobile Number.",validate_hp($hp));
    }


    // ========== PROVIDERS=========
    function valid_hp_provider()
    {
        return [[12345678]]; //valid hp
    }

    function invalid_hp_provider()
    {
        return [
            ["abcdefgh"], //invalid hp - string
            [123456789], //invalid hp - more than 8 digits
            ["%$@#^$&"] //invalid hp - special characters
        ];
    }
}
?>