<?php
require_once dirname(__FILE__) . "/sql.php";
require_once dirname(__FILE__) . "/item_sql.php";


function add_to_cart($userid, $itemid, $quantity)
{
    $conn = get_conn();
    if ($quantity > 0 && validate_item($itemid, $conn) && validate_item_quantity($itemid, $quantity, $conn)) {
        delete_cart_item($userid, $itemid);
        $db_insert_cart_item = $conn->prepare("INSERT INTO cart_item SET item_iditem = ?, user_iduser = ?, quantity = ?");
        $db_insert_cart_item->bind_param("iii", $itemid, $userid, $quantity);
        if (!$db_insert_cart_item->execute()) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return true;
    } else {
        return false;
    }
    return false;
}

function delete_cart_item($userid, $itemid)
{
    $conn = get_conn();
    $db_remove_user_item = $conn->prepare("DELETE FROM cart_item WHERE user_iduser = ? AND item_iditem = ?");
    $db_remove_user_item->bind_param("ii", $userid, $itemid);
    if (!$db_remove_user_item->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function update_cart_item($userid, $itemid, $quantity)
{
    $conn = get_conn();
    $db_update_cart_item = $conn->prepare("UPDATE cart_item SET quantity = ? WHERE user_iduser = ? AND item_iditem = ?");
    $db_update_cart_item->bind_param("iii", $quantity, $userid, $itemid);
    if (!$db_update_cart_item->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}
function update_cart_quantity($userid, $itemid, $quantity)
{
    $conn = get_conn();
    $db_update_cart_item = $conn->prepare("UPDATE cart_item SET quantity = quantity + ? WHERE user_iduser = ? AND item_iditem = ?");
    $db_update_cart_item->bind_param("iii", $quantity, $userid, $itemid);
    if (!$db_update_cart_item->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function remove_cart_item($userid, $itemid)
{
    $conn = get_conn();
    $db_remove_user_items = $conn->prepare("DELETE FROM cart_item WHERE user_iduser = ? AND item_iditem = ?");
    $db_remove_user_items->bind_param("ii", $userid, $itemid);
    if (!$db_remove_user_items->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function get_cart_item($userid, $itemid)
{
    $conn = get_conn();
    $db_get_cart = $conn->prepare("SELECT * FROM cart_item WHERE user_iduser = ? AND item_iditem = ?");
    $db_get_cart->bind_param("ii", $userid, $itemid);
    $db_get_cart->execute();
    if (!$db_get_cart->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_get_cart->get_result();
    return $result;
}

function clear_user_cart($userid)
{
    $conn = get_conn();
    $db_remove_user_items = $conn->prepare("DELETE FROM cart_item WHERE user_iduser = ?");
    $db_remove_user_items->bind_param("i", $userid);
    if (!$db_remove_user_items->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function get_full_cart($userid)
{
    $conn = get_conn();
    $db_get_cart = $conn->prepare("SELECT * FROM cart_view WHERE user_iduser = ?");
    $db_get_cart->bind_param("i", $userid);
    $db_get_cart->execute();
    if (!$db_get_cart->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_get_cart->get_result();
    return $result;
}

function get_valid_cart($userid)
{
    $conn = get_conn();
    $db_get_cart = $conn->prepare("SELECT * FROM cart_view WHERE user_iduser = ? AND disabled = false AND available = true");
    $db_get_cart->bind_param("i", $userid);
    if (!$db_get_cart->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_get_cart->get_result();
    //
    $cart = $result->fetch_all();
    return $cart;
}

function getItemInCart($cart)
{
    $conn = get_conn();
    $db_get_cart = $conn->prepare("SELECT * FROM products WHERE id IN (?)");
    $db_get_cart->bind_param("s", $cart);
    if (!$db_get_cart->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_get_cart->get_result();
    //
    $cart = $result->fetch_all();
    return $cart;
}