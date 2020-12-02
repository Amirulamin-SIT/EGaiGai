<?php
require_once dirname(__FILE__)."/sql.php";
require_once dirname(__FILE__)."/cart_sql.php";
require_once dirname(__FILE__)."/order_sql.php";

function inCheckout($userid)
{
    $conn = get_conn();
    if (count(get_valid_cart($userid)) > 0)
    {
        return create_order($userid, $conn);
    }
    return false;
}

function checkedout($orderid, $userid, $delivery_address)
{
    if (validate_order($orderid, $userid) && $delivery_address !='')
    {
        checked_out($orderid, $delivery_address);
        return true;
    }
    else
    {
        return false;
    }
}

function paid($orderid, $userid)
{
    if(paid_order($orderid))
    {
        clear_user_cart($userid);
    }
}


?>