<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once dirname(__FILE__) . "/sql.php";
require_once dirname(__FILE__) . "/cart_sql.php";

//check that order is valid, true if valid, false if not
function validate_order($orderID, $userID)
{
    $conn = get_conn();
    $db_order = $conn->prepare("SELECT COUNT(*) FROM orders WHERE order_no = ? AND user_iduser = ? AND disabled = false");
    $db_order->bind_param("ii", $orderID, $userID);
    if (!$db_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_order->get_result();
    $orderCount = $result->fetch_row()[0];
    if ($orderCount == 1) {
        return true;
    }
    return false;
}



function check_order_exists($orderID)
{
    $conn = get_conn();
    $db_order = $conn->prepare("SELECT COUNT(*) FROM orders WHERE order_no = ? AND disabled = false");
    $db_order->bind_param("i", $orderID);
    if (!$db_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_order->get_result();
    //
    $orderCount = $result->fetch_row()[0];
    if ($orderCount == 1) {
        return true;
    }
    return false;
}

function getAllOrdersAdmin($page, $no_of_orders)
{
    $limit = ($page - 1) * $no_of_orders;
    $offset = $page * $no_of_orders;
    $conn = get_conn();
    $db_order = $conn->prepare("SELECT * FROM orders LIMIT ?,?");
    $db_order->bind_param("ii", $limit, $offset);

    if (!$db_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_order->get_result();
    return $result;
}

function getAllOrdersAdminNoLimit()
{
    $conn = get_conn();
    $db_order = $conn->prepare("SELECT * FROM orders");
    if (!$db_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_order->get_result();
    return $result;
}

function getOrdersByUserId($userid, $page, $no_of_orders)
{
    $limit = ($page - 1) * $no_of_orders;
    $offset = $page * $no_of_orders;
    $conn = get_conn();
    $db_order = $conn->prepare("SELECT * FROM orders WHERE user_iduser = ? AND status='PAID' AND disabled = false LIMIT ?,?");
    $db_order->bind_param("iii", $userid, $limit, $offset);

    if (!$db_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_order->get_result();
    return $result;
}

function getOrdersByUserIdNoLimit($userid)
{

    $conn = get_conn();
    $db_order = $conn->prepare("SELECT * FROM orders WHERE user_iduser = ? AND status='PAID'");
    $db_order->bind_param("i", $userid);
    if (!$db_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_order->get_result();
    return $result;
}

function getOrderItems($order_no)
{
    $conn = get_conn();
    $db_order = $conn->prepare("SELECT i1.item_name, o1.quantity, i1.price, i1.image_location, o2.status
                                FROM order_item o1, orders o2, item i1
                                WHERE o2.order_no = ?
                                AND o2.order_no = o1.orders_order_no 
                                AND o1.item_iditem = i1.iditem
                                ORDER BY o2.date");
    $db_order->bind_param("i", $order_no);
    if (!$db_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_order->get_result();
    return $result;
}

function getPaidOrderItems($order_no)
{
    $conn = get_conn();
    $db_order = $conn->prepare("SELECT i1.item_name, o1.quantity, i1.price, i1.image_location, o2.status
                                FROM order_item o1, orders o2, item i1
                                WHERE o2.order_no = ?
                                AND o2.order_no = o1.orders_order_no 
                                AND o1.item_iditem = i1.iditem
                                AND o2.status = 'PAID'
                                ORDER BY o2.date");
    $db_order->bind_param("i", $order_no);
    if (!$db_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $result = $db_order->get_result();
    return $result;
}




function create_order($userid, $conn = "")
{
    if ($conn == "") {
        $conn = get_conn();
    }
    $db_insert_order = $conn->prepare("INSERT INTO orders SET user_iduser = ?, delivery_address = ' ', date = NOW(), status = 'incheckout', disabled = false, timeout = DATE_ADD(NOW(), INTERVAL 2 DAY)");
    $db_insert_order->bind_param("i", $userid);
    if (!$db_insert_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $order_no = $db_insert_order->insert_id;
    $order_string = "order_" . strval($order_no);
    $orderitems_string = "orderitems_" . strval($order_no);


    //add order items
    $db_insert_order_items = $conn->prepare("INSERT INTO order_item(item_iditem, orders_order_no, quantity, item_price) SELECT item_id, ?, quantity, price FROM cart_view WHERE user_iduser = ? AND disabled = false AND available = true");
    $db_insert_order_items->bind_param("ii", $order_no, $userid);
    if (!$db_insert_order_items->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    //subtract item quantity
    $db_sub_order_items = $db_insert_order_items = $conn->prepare("UPDATE item INNER JOIN order_item ON item.iditem = order_item.item_iditem SET item.quantity = (item.quantity - order_item.quantity) WHERE order_item.orders_order_no = ?");
    $db_sub_order_items->bind_param("i", $order_no);
    if (!$db_sub_order_items->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }


    //disable order after x time
    $db_insert_order_event = $conn->query("CREATE EVENT $order_string ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 2 DAY DO UPDATE orders SET disabled = true WHERE order_no = $order_no");


    //return items to inventory
    $db_add_order_items_event = $conn->query("CREATE EVENT $orderitems_string ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 2 DAY DO UPDATE item INNER JOIN order_item ON item.iditem = order_item.item_iditem SET item.quantity = (item.quantity - order_item.quantity) WHERE order_item.orders_order_no = $order_no");

    return $order_no;
}

function cancel_order($order_no)
{
    $conn = get_conn();
    $db_order = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE order_no = ?");
    $db_order->bind_param("i", $order_no);
    if (!$db_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function checked_out($orderid, $delivery_address)
{
    $conn = get_conn();
    $db_checkout = $conn->prepare("UPDATE orders SET status = 'checkedout', delivery_address = ? WHERE order_no = ? AND disabled = false");
    $db_checkout->bind_param("si", $delivery_address, $orderid);
    if (!$db_checkout->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function paid_order($orderid)
{
    $conn = get_conn();
    if (check_order_exists($orderid) === true) {
        $db_paid = $conn->prepare("UPDATE orders SET status = 'PAID' WHERE order_no = ? AND disabled = false");
        $db_paid->bind_param("i", $orderid);
        if (!$db_paid->execute()) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $db_remove_order_event = $conn->query("DROP EVENT order_$orderid");
        $db_remove_order_items_event = $conn->query("DROP EVENT orderitems_$orderid");
        return true;
    }
    return false;
}

function update_delivery_status($orderid, $status)
{
    $conn = get_conn();
    $db_update_cart_order = $conn->prepare("UPDATE orders SET delivered = ? WHERE order_no = ? AND disabled = false");
    $db_update_cart_order->bind_param("ii", $status, $orderid);
    if (!$db_update_cart_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function get_order_status($order_id)
{
    $conn = get_conn();
    if (check_order_exists($order_id) === true) {
        $db_getorder = $conn->prepare("SELECT DISTINCT o2.status, o2.delivered, o2.date
                                        FROM order_item o1, orders o2, item i1
                                        WHERE o2.order_no = ?
                                        AND o1.orders_order_no = o2.order_no
                                        AND o1.item_iditem = i1.iditem
                                        ORDER BY o2.date");
        $db_getorder->bind_param("i", $order_id);
        if (!$db_getorder->execute()) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $result = $db_getorder->get_result();
        $address = $result->fetch_assoc();

        return $address;
    }
    return false;
}


function get_order_address($order_id)
{
    $conn = get_conn();
    if (check_order_exists($order_id) === true) {
        $db_getorder = $conn->prepare("SELECT DISTINCT o2.date, o2.delivery_address
                                        FROM order_item o1, orders o2, item i1
                                        WHERE o2.order_no = ?
                                        AND o1.orders_order_no = o2.order_no
                                        AND o1.item_iditem = i1.iditem
                                        ORDER BY o2.date");
        $db_getorder->bind_param("i", $order_id);
        if (!$db_getorder->execute()) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $result = $db_getorder->get_result();
        $address = $result->fetch_assoc();

        return $address;
    }
    return false;
}


function get_order($order_id,$iduser='TRUE')
{
    $conn = get_conn();
    if (check_order_exists($order_id) === true) {
        $db_getorder = $conn->prepare("SELECT o2.order_no, i1.item_Name, o1.quantity, o1.item_price, i1.image_location
                                        FROM order_item o1, orders o2, item i1
                                        WHERE o2.order_no = ? AND o2.user_iduser=?
                                        AND o1.orders_order_no = o2.order_no
                                        AND o1.item_iditem = i1.iditem AND o2.status='PAID'
                                        ORDER BY o2.date");
        $db_getorder->bind_param("is", $order_id,$iduser);
        if (!$db_getorder->execute()) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $result = $db_getorder->get_result();
        return $result;
    }
    return false;
}

function remove_order($orderid)
{
    disable_order($orderid);
    //$conn = get_conn();
    //$db_remove_order = $conn->prepare("DELETE FROM ordesr WHERE order_no = ?");
    //$db_remove_order->bind_param("i", $orderid);
    //if (!$db_remove_order->execute()) {
    //    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    //}
}


function disable_order($order_id)
{
    $conn = get_conn();
    $db_disable_cart_order = $conn->prepare("UPDATE orders SET disabled = true WHERE order_no = ?");
    $db_disable_cart_order->bind_param("i", $order_id);
    if (!$db_disable_cart_order->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

function get_order_price($order_id)
{
    $conn = get_conn();
    if (check_order_exists($order_id) === true) {
        $db_getordercount = $conn->prepare("SELECT SUM(item_price*quantity) FROM order_item WHERE orders_order_no = ?");
        $db_getordercount->bind_param("i", $order_id);
        if (!$db_getordercount->execute()) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $result = $db_getordercount->get_result();
        $price = $result->fetch_row()[0];
        return $price;
    }
    return false;
}
    
    //echo create_order(1);
