<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/file_upload_validation.php";

final class FileRenameValidationTest extends TestCase
{   
    //======== TEST CASES =========

    /**
     * @dataProvider valid_filename_provider
     **/ 
    function test_validate_filerename_valid($file)
    {
        $ext = strtolower(substr($file, strripos($file, '.')+1));
        $count = 64 + strlen($ext) + 1;
        $this->assertEquals($count,strlen(new_file_name($file)));
    }


    // ========== PROVIDERS=========
    function valid_filename_provider()
    {
        $testFile = array(
            'name'=>'test.jpg',
            'tmp_name'=>'tests/test_file\blue_sky.jpg',
            'type'=>'image/jpg',
            'size'=>1472190,
            'error'=>0
        );
        return [[$testFile['name']]]; //valid file name
    }
}
?>