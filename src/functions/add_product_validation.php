<?php

require_once 'dataValidation.php';

function validate_item_name($item_name)
{
    // Allowed characters
    $item_name_regex = '/[^a-z_ \-0-9]/i';
    // Validate item name length
    if (!strlen($item_name) > 0)
    {
        return "Item Name Must Not Be Empty";
    }
    // validate text against allowed characters
    if(preg_match($item_name_regex, $item_name))
    {
        return "Invalid Characters Detected";
    }
    // Validate for illegal tags
    if(checkForTag($item_name))
    {
        return "Item Name cannot contain < or > or /";
    }

    return true;
}

function validate_item_desc($item_desc)
{
    // Validate item desc length
    if (!strlen($item_desc) > 0)
    {
        return "Item Description must not be empty";
    }
    // Validate for illegal tags
    if(checkForTag($item_desc))
    {
        return "Item Description cannot contain < or > or /";
    }

    return true;
}

function validate_item_num($item_quantity)
{
    // Validate for numbers
    if (is_numeric($item_quantity)){
        if (floor($item_quantity) != $item_quantity)
        {
            return "Item quantity cannot contain string or decimal";
        }
    }else
    {
        return "Item quantity cannot contain string or decimal";
    }
    

    // Validate for -ve numbers and 0
    if ($item_quantity <= 0)
    {
        return "Item quantity cannot be 0 or negative";
    }

    return true;
}

function validate_item_price($item_price)
{
    // Validate for numbers
    if (!is_numeric($item_price))
    {
        return "Item price cannot contain string";
    }

    // Validate for -ve numbers and 0
    if ($item_price <= 0)
    {
        return "Item price cannot be 0 or negative";
    }

    return true;
}

?>