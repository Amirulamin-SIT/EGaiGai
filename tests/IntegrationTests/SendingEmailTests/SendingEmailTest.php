<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/email.php";

final class SendingEmailTest extends TestCase
{   
    //======== TEST CASES =========

    //valid otp parameters
    /**
     * @dataProvider valid_verification_email_sending_provider
     **/ 
    function test_validate_verification_email_sending_status_valid($email)
    {
        
        $this->assertTrue(send_email($email));
    }

    /**
     * @dataProvider valid_otp_email_sending_provider
     **/ 
    function test_validate_otp_email_sending_status_valid($email)
    {
        
        $this->assertTrue(send_email_otp($email));
    }

    /**
     * @dataProvider valid_reset_email_sending_provider
     **/ 
    function test_validate_reset_email_sending_status_valid($email)
    {
        
        $this->assertTrue(send_reset_pwd_email($email));
    }

    /**
     * @dataProvider valid_change_pw_email_confirmation_sending_provider
     **/ 
    function test_validate_change_pw_email_confirmation_sending_status_valid($email)
    {
        
        $this->assertTrue(send_change_password_email_confirmation($email));
    }



    // ========== PROVIDERS=========
    function valid_verification_email_sending_provider()
    {
        return [["aaaaa@gmail.com"]]; //email sent successfully
    }

    function valid_otp_email_sending_provider()
    {
        return [["aaaaa@gmail.com"]]; //email sent successfully
    }

    function valid_reset_email_sending_provider()
    {
        return [["aaaaa@gmail.com"]]; //email sent successfully
    }

    function valid_change_pw_email_confirmation_sending_provider()
    {
        return [["aaaaa@gmail.com"]]; //email sent successfully
    }
   
   
}
?>