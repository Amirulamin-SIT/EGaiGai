<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/file_upload_validation.php";

final class FileUploadValidationTest extends TestCase
{   
    //======== TEST CASES =========

    /**
     * @dataProvider valid_file_provider
     **/ 
    function test_validate_fileupload_valid($file)
    {
        $otp_regex = '#.*/.*#';

        $this->assertRegExp($otp_regex,check_file_type($file));
    }


    // ========== PROVIDERS=========
    function valid_file_provider()
    {
        $testFile = array(
            'name'=>'test.jpg',
            'tmp_name'=>'tests/test_file/blue_sky.jpg',
            'type'=>'image/jpg',
            'size'=>1472190,
            'error'=>0
        );
        return [[$testFile['tmp_name']]]; //valid file type
    }
}
?>