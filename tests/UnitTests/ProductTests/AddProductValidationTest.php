<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/add_product_validation.php";

final class AddProductValidationTest extends TestCase
{   
    //======== TEST CASES =========

    // Valid Item Name
    /**
     * @dataProvider valid_itemName_provider
     **/ 
    function test_validate_item_name_valid($item_name)
    {
        $this->assertTrue(validate_item_name($item_name));
    }

    // Invalid Item Name
    /**
     * @dataProvider invalid_itemName_empty_provider
     **/ 
    function test_validate_item_name_empty_invalid($item_name)
    {
        $this->assertEquals("Item Name Must Not Be Empty", validate_item_name($item_name));
    }

    /**
     * @dataProvider invalid_itemName_specChar_provider
     **/ 
    function test_validate_item_name_sc_invalid($item_name)
    {
        $this->assertEquals("Invalid Characters Detected", validate_item_name($item_name));
    }    

    // Valid Item Description
    /**
     * @dataProvider valid_itemDesc_provider
     **/ 
    function test_validate_item_desc_valid($item_desc)
    {
        $this->assertTrue(validate_item_desc($item_desc));
    }

    // Invalid Item Description
    /**
     * @dataProvider invalid_itemDesc_empty_provider
     **/ 
    function test_validate_item_desc_empty_invalid($item_desc)
    {
        $this->assertEquals("Item Description must not be empty", validate_item_desc($item_desc));
    }

    /**
     * @dataProvider invalid_itemDesc_specChar_provider
     **/ 
    function test_validate_item_desc_sc_invalid($item_desc)
    {
        $this->assertEquals("Item Description cannot contain < or > or /", validate_item_desc($item_desc));
    }

    // Valid Item Quantity
    /**
     * @dataProvider valid_itemQty_provider
     **/ 
    function test_validate_item_qty_valid($item_quantity)
    {
        $this->assertTrue(validate_item_num($item_quantity));
    }

    // Invalid Item Quantity
    /**
     * @dataProvider invalid_itemQty_strdec_provider
     **/ 
    function test_validate_item_qty_strdec_invalid($item_quantity)
    {
        $this->assertEquals("Item quantity cannot contain string or decimal", validate_item_num($item_quantity));
    }

    /**
     * @dataProvider invalid_itemQty_neg_provider
     **/ 
    function test_validate_item_qty_neg_invalid($item_quantity)
    {
        $this->assertEquals("Item quantity cannot be 0 or negative", validate_item_num($item_quantity));
    }  
    
    // Valid Item Price
    /**
     * @dataProvider valid_itemPrice_provider
     **/ 
    function test_validate_item_price_valid($item_price)
    {
        $this->assertTrue(validate_item_price($item_price));
    }

    // Invalid Item Quantity
    /**
     * @dataProvider invalid_itemPrice_str_provider
     **/ 
    function test_validate_item_price_str_invalid($item_price)
    {
        $this->assertEquals("Item price cannot contain string", validate_item_price($item_price));
    }

    /**
     * @dataProvider invalid_itemPrice_neg_provider
     **/ 
    function test_validate_item_price_neg_invalid($item_price)
    {
        $this->assertEquals("Item price cannot be 0 or negative", validate_item_price($item_price));
    }

    // ========== PROVIDERS=========
    function valid_itemName_provider()
    {
        return [
            ["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"], //valid string - only alphabets
            ["1234567890"], //valid string - only numbers
            ["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"], //valid string - only alphanumeric
            ["_ -"], //valid string - only allowed symbols
            ["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_ -"] //valid string - only alphanumeric with symbols
        ]; 
    }

    function invalid_itemName_empty_provider()
    {
        return [
            [""] //invalid string - empty
        ];
    }

    function invalid_itemName_specChar_provider()
    {
        return [
            ["~`!@#$%^&*()=+|,.<>?/"] //invalid string - not whitelisted special characters
        ];
    }

    function valid_itemDesc_provider()
    {
        return [
            ["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"], //valid string - only alphanumeric
            ["~`!@#$%^&*()=+|,.?-_ "], //valid string - only allowed symbols
            ["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890~`!@#$%^&*()=+|,.?-_ "] //valid string - only alphanumeric with symbols
        ]; 
    }

    function invalid_itemDesc_empty_provider()
    {
        return [
            [""] //invalid string - empty
        ];
    }

    function invalid_itemDesc_specChar_provider()
    {
        return [
            ["<>/"] //invalid string - not whitelisted special characters
        ];
    }

    function valid_itemQty_provider()
    {
        return [
            [123456789] //valid int - only numbers
        ]; 
    }

    function invalid_itemQty_strdec_provider()
    {
        return [
            [0.1], //invalid number - decimal
            ["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"], //invalid number - string
            ["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"], //invalid string - alphabets
            ["~`!@#$%^&*()=+|,.?-_ <>/"], //invalid string - only symbols
        ];
    }

    function invalid_itemQty_neg_provider()
    {
        return [
            [-1] //invalid number - less than 0
        ];
    }

    function valid_itemPrice_provider()
    {
        return [
            [1234567890], //valid string - only numbers
            [1.75] //valid string - decimal
        ]; 
    }

    function invalid_itemPrice_str_provider()
    {
        return [
            ["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"], //invalid string - alphanumeric
            ["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"], //invalid string - alphabets
            ["~`!@#$%^&*()=+|,.?-_ <>/"], //invalid string - only symbols
        ];
    }

    function invalid_itemPrice_neg_provider()
    {
        return [
            [-1] //invalid number - less than 0
        ];
    }
}
?>