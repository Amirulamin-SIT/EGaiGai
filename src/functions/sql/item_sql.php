<?php
require_once dirname(__FILE__) . "/sql.php";

function getAllItem($page, $no_of_products)
{
    $limit = ($page - 1) * $no_of_products;
    $offset = $page * $no_of_products;

    $conn = get_conn();
    $db_getItem = $conn->prepare("SELECT * FROM item WHERE disabled = false LIMIT ?, ? ");
    $db_getItem->bind_param("ii", $limit, $offset);

    if (!$db_getItem->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_getItem->get_result();
    return $result;
}

function getAllItemAdmin($page, $no_of_products)
{
    $limit = ($page - 1) * $no_of_products;
    $offset = $page * $no_of_products;

    $conn = get_conn();
    $db_getItem = $conn->prepare("SELECT * FROM item LIMIT ?, ? ");
    $db_getItem->bind_param("ii", $limit, $offset);

    if (!$db_getItem->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_getItem->get_result();
    return $result;
}

function getAllItemNoLimitAdmin()
{
    $conn = get_conn();
    $db_getItem = $conn->prepare("SELECT * FROM item");
    if (!$db_getItem->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_getItem->get_result();
    return $result;
}

function getAllItemNoLimit()
{
    $conn = get_conn();
    $db_getItem = $conn->prepare("SELECT * FROM item WHERE disabled = false");
    $db_getItem->execute();
    if (!$db_getItem->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_getItem->get_result();
    return $result;
}

function getSearchItem($name, $page, $no_of_products)
{
    $limit = ($page - 1) * $no_of_products;
    $offset = $page * $no_of_products;
    require_once dirname(__FILE__) .'/../dataValidation.php';
    $name =xssSanit($name);

    $conn = get_conn();
    $db_getItem = $conn->prepare("SELECT * FROM item WHERE item_Name LIKE CONCAT('%',?,'%') AND disabled = false LIMIT ?, ? ");
    $db_getItem->bind_param("sii", $name, $limit, $offset);
    $db_getItem->execute();

    if (!$db_getItem->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_getItem->get_result();
    return $result;
}

function getSearchItemNoLimit($name)
{
    $conn = get_conn();
    $db_getItem = $conn->prepare("SELECT * FROM item WHERE item_Name LIKE CONCAT('%',?,'%') AND disabled = false");
    $db_getItem->bind_param("s", $name);
    if (!$db_getItem->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_getItem->get_result();
    return $result;
}

function getRecentItem()
{
    $conn = get_conn();
    $db_getItem = $conn->prepare("SELECT * FROM item WHERE disabled = false LIMIT 4");
    if (!$db_getItem->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_getItem->get_result();
    return $result;
}

function validate_item($item_id)
{
    $conn = get_conn();
    $db_getItem = $conn->prepare("SELECT count(*) FROM item WHERE iditem = ? AND disabled = false");
    $db_getItem->bind_param("i", $item_id);
    if (!$db_getItem->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_getItem->get_result();
    $itemCount = $result->fetch_row()[0];
    if ($itemCount == 1) {
        return true;
    }
    return false;
}

function validate_item_quantity($itemid, $quantity, $conn = "")
{
    if ($conn == "") {
        $conn = get_conn();
    }

    $db_getItemCount = $conn->prepare("SELECT quantity FROM item WHERE iditem = ? AND disabled = false");
    $db_getItemCount->bind_param("i", $itemid);
    if (!$db_getItemCount->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_getItemCount->get_result();
    $itemCount = $result->fetch_row()[0];
    if ($itemCount >= $quantity) {
        return true;
    }
    return false;
}

function add_item($item_name, $item_description, $item_price, $quantity, $img_location, $conn = "")
{
    $conn = get_conn();
    $db_insert_cart_item = $conn->prepare("INSERT INTO item SET item_Name = ?, description = ?, price = ?, quantity = ?, image_location = ?");
    $db_insert_cart_item->bind_param("ssdis", $item_name, $item_description, $item_price, $quantity, $img_location);

    if (!$db_insert_cart_item->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    // return $db_insert_cart_item->insert_id;
    return true;
}

function get_itemDisabled($item_id)
{
    $conn = get_conn();
    $db_getItem = $conn->prepare("SELECT * FROM item WHERE iditem = ?");
    $db_getItem->bind_param("i", $item_id);
    if (!$db_getItem->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_getItem->get_result();
    $item = $result->fetch_assoc();
    return $item;
}

function get_item($item_id)
{
    $conn = get_conn();
    if (validate_item($item_id) == true) {
        $db_getItem = $conn->prepare("SELECT * FROM item WHERE iditem = ? AND disabled = false");
        $db_getItem->bind_param("i", $item_id);
        if (!$db_getItem->execute()) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $result = $db_getItem->get_result();
        $item = $result->fetch_assoc();
        return $item;
    }
    return false;
}

function remove_item($itemid)
{
    $conn = get_conn();
    $db_remove_item = $conn->prepare("DELETE FROM item WHERE iditem = ?");
    $db_remove_item->bind_param("i", $itemid);
    if (!$db_remove_item->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function update_item($item_id, $item_name, $item_description, $item_price, $quantity, $disabled)
{
    $conn = get_conn();
    $db_update_cart_item = $conn->prepare("UPDATE item SET item_Name = ?, description = ?, price = ?, quantity = ?, disabled = ? WHERE iditem = ?");
    $db_update_cart_item->bind_param("ssdiii", $item_name, $item_description, $item_price, $quantity, $disabled, $item_id);
    if (!$db_update_cart_item->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function disable_item($item_id)
{
    $conn = get_conn();
    $db_disable_cart_item = $conn->prepare("UPDATE item SET disabled = true WHERE iditem = ?");
    $db_disable_cart_item->bind_param("i", $item_id);
    if (!$db_disable_cart_item->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}


function add_item_image($itemid, $image_location)
{
    $conn = get_conn();
}

function remove_item_image($itemid, $image_location)
{
    $conn = get_conn();
}

function subtract_item_quantity($item_id, $quantity)
{
    $conn = get_conn();
    $db_update_cart_item = $conn->prepare("UPDATE item SET quantity = (quantity - ?) WHERE iditem = ?");
    $db_update_cart_item->bind_param("ii", $quantity, $item_id);
    if (!$db_update_cart_item->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function add_item_quantity($item_id, $quantity)
{
    $conn = get_conn();
    $db_update_cart_item = $conn->prepare("UPDATE item SET quantity = (quantity + ?) WHERE iditem = ?");
    $db_update_cart_item->bind_param("ii", $quantity, $item_id);
    if (!$db_update_cart_item->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

//add_item();
//remove_item(1);
//update_item(2, $item_name = "yesnt", $item_price = 1.3, $quantity = 2);
