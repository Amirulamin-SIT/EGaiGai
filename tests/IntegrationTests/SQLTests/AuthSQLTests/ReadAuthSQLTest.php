<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/sql/sql.php";
require_once "./src/functions/sql/auth.php";


final class ReadAuthSQLTest extends TestCase
{   
    //======== TEST CASES =========

    // ----- function get_user_by_email($email, $conn="")

    /**
     * @dataProvider valid_email_provider
     **/ 
    function test_get_user_by_email_valid($email)
    {
        //test function
        $res = get_user_by_email($email);

        //get result
        $rows = mysqli_num_rows($res);
        $this->assertEquals($rows, 1); //there should only be one row
    }

    /**
     * @dataProvider invalid_email_provider
     **/ 
    function test_get_user_by_email_invalid($email)
    {
        //test function
        $res = get_user_by_email($email);

        //get result
        $rows = mysqli_num_rows($res);
        $this->assertEquals($rows, 0); //there should only be one row
    }


    // ----- function getUserByPhone($phone, $conn="")
    /**
     * @dataProvider valid_phone_provider
     **/
    function test_getUserByPhone_valid($phone)
    {
        //test function
        $res = getUserByPhone($phone);

        //get result
        $rows = mysqli_num_rows($res);
        $this->assertEquals($rows, 1); //there should only be one row
    }

    /**
     * @dataProvider invalid_phone_provider
     **/
    function test_getUserByPhone_invalid($phone)
    {
        //test function
        $res = getUserByPhone($phone);

        //get result
        $rows = mysqli_num_rows($res);
        $this->assertEquals($rows, 0); //there should only be one row
    }
    
    // ----- function get_user_otpstuff_by_email($email, $conn="")
    /**
     * @dataProvider valid_email_provider
     **/ 
    function test_get_user_otpstuff_by_email_valid($email)
    {
        //test function
        $res = get_user_otpstuff_by_email($email);

        //get result
        $rows = mysqli_num_rows($res);
        $this->assertEquals($rows, 1); //there should only be one row
    }

    /**
     * @dataProvider invalid_email_provider
     **/ 
    function test_get_user_otpstuff_by_email_invalid($email)
    {
        //test function
        $res = get_user_otpstuff_by_email($email);

        //get result
        $rows = mysqli_num_rows($res);
        $this->assertEquals($rows, 0); //there should only be one row
    }

    // ----- function get_user_hp_by_email($email, $conn="")
    /**
     * @dataProvider valid_email_to_hp_provider
     **/ 
    function test_get_user_hp_by_email_valid($email, $hp)
    {
        //test function
        $res = get_user_hp_by_email($email);

        //get result
        $this->assertEquals($res, $hp); //there should only be one row
    }

    /**
     * @dataProvider invalid_email_provider
     **/ 
    function test_get_user_hp_by_email($email)
    {
        //test function
        $res = get_user_hp_by_email($email);

        //get result
        $this->assertEquals($res, "Invalid username or password"); //it returns error message
    }

    //======== PROVIDERS =========

    // ----- email providers
    function valid_email_provider()
    {
        return [["lesliechiew@gmail.com"]]; //valid email
    }

    function invalid_email_provider()
    {
        return [["yes@yes.com"]]; //invalid email
    }


    // ----- phone providers
    function valid_phone_provider()
    {
        return [["+6590114334"]]; //valid phoneno
    }

    function invalid_phone_provider()
    {
        return [["123456"]]; //invalid phoneno
    }


    // test_get_user_hp_by_email_valid
    function valid_email_to_hp_provider()
    {
        return [["lesliechiew@gmail.com", "+6590114334"]]; //valid email -> hp
    }

}
?>