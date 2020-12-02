<?php
include "../header.php";
require_once "../functions/sql/auth.php";
if (!isset($_SESSION["iduser"])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
}
if(mysqli_fetch_assoc(getRole($_SESSION['iduser']))['type'] != 'admin'){
    session_unset();
    session_destroy();
    header("Location: ../index.php");
}
//The amounts of products to show on each page
$num_orders_on_each_page = 4;
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
require_once "../functions/sql/order_sql.php";
$orders = getAllOrdersAdmin($current_page, $num_orders_on_each_page);

// Get the total number of order
$total_orders = mysqli_num_rows(getAllOrdersAdminNoLimit());
$order_retrieved = mysqli_num_rows($orders);
?>

<!DOCTYPE html>
<html lang="">

<head>
    <title>Edit Orders</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/style.css" rel="stylesheet" type="text/css">

</head>

<body>
    <main>
        <div class="cart content-wrapper">
            <h1>Edit Purchased</h1>
            <p><?= $total_orders ?> Orders</p>
            <?php if (empty($orders)) : ?>
                <h3 style="text-align:center;">
                    You have no order added in your Orders History
                </h3>
            <?php else : ?>
                <?php if (isset($_SESSION['message'])) : ?>
                    <div class="alert alert-success">
                        <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        ?>
                    </div>
                <?php endif ?>
                <?php foreach ($orders as $order) : ?>
                    <?php $subtotal = 0 ?>
                    <div class="container border rounded">
                        <h3 style="float: left; margin-top: 1%;">Order <?= $order['order_no']  ?></h3>
                        <table style="margin-top: -30px;">
                            <thead>
                                <tr>
                                    <td colspan="2">Product</td>
                                    <td>Price</td>
                                    <td>Quantity</td>
                                    <td>Total</td>
                                </tr>
                            </thead>

                            <button class="btn btn-primary" style="float: right; margin-top:1%;">
                                <a style="color:floralwhite;" href="edit_order.php?id=<?= $order['order_no'] ?>" class="edit_btn">
                                    Edit
                                </a>
                            </button>
                            <tbody>
                                <?php
                                $orders_items = getOrderItems($order['order_no']);
                                foreach ($orders_items as $item) : ?>
                                    <tr>
                                        <td class="img">
                                            <img src="../imgs/<?= $item['image_location'] ?>" width="50" height="50" alt="<?= $item['item_name'] ?>">
                                            </a>
                                        </td>
                                        <td>
                                            <?= $item['item_name'] ?>
                                        </td>
                                        <td class="price">&dollar;<?= $item['price'] ?></td>
                                        <td class="quantity"><?= $item['quantity'] ?></td>
                                        <td class="price">&dollar;<?= $item['price'] * $item['quantity'] ?></td>
                                    </tr>
                                    <?php $subtotal += $item['price'] ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="subtotal" style="float:left;">
                            <span class="text" style="font-weight:bold;">Status: </span>
                            <td class="status"><?= $item['status'] ?></td>
                        </div>
                        <div class="subtotal">
                            <span class="text">Subtotal</span>
                            <span class="price">&dollar;<?= $subtotal ?></span>
                        </div>
                    </div>
                <?php
                endforeach
                ?>
            <?php endif; ?>
            <div class="buttons">
                <?php if ($current_page > 1) : ?>
                    <a href="edit_orders.php?p=<?= $current_page - 1 ?>">Prev</a>
                <?php endif; ?>
                <?php if ($total_orders > ($current_page * $num_orders_on_each_page) - $num_orders_on_each_page + $order_retrieved) : ?>
                    <a href="edit_orders.php?p=<?= $current_page + 1 ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
<? include "../footer.php"?>

</html>