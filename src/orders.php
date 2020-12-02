    <?php

//The amounts of products to show on each page
// if (!isset($_SESSION["username"])) {
//     header("Location: handle.php");
// } else {
// require_once "./functions/sql/auth.php";
// $user = $_SESSION["email"];
// // $userDetail = userDetails($user);
// $stmt = getUserDetails($user);
// $userDetail = mysqli_fetch_assoc($stmt);
include "./header.php";
if(!isset($_SESSION["iduser"]))
{
    session_unset();
    session_destroy();
    header('Location: ./index.php');
}
$num_orders_on_each_page = 4;
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
require_once "./functions/sql/order_sql.php";
// Select products ordered by the date added
$iduser = $_SESSION['iduser'];
$orders = getOrdersByUserId($iduser, $current_page, $num_orders_on_each_page);


// Get the total number of order
$total_orders = mysqli_num_rows($orders);
$total_orders = mysqli_num_rows(getOrdersByUserIdNoLimit($iduser));
//}


?>

<!DOCTYPE html>
<html lang="">

<head>
    <title>Orders</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <main>
        <div class="cart content-wrapper">
            <h1>Orders History</h1>
            <p><?= $total_orders ?> Orders</p>

            <?php if (empty($orders)) : ?>
                <h3 style="text-align:center;">
                    You have no order added in your Orders History
                </h3>
            <?php else : ?>
                <?php foreach ($orders as $order) : ?>
                    <?php $subtotal = 0 ?>
                    <div class="container border rounded">
                        <h3 style="float:left; margin-top: 1%;">Order <?= $order['order_no']  ?></h3>
                        <table style="margin-top: -30px;">
                            <thead>
                                <tr>
                                    <td colspan="2">Product</td>
                                    <td>Price</td>
                                    <td>Quantity</td>
                                    <td>Total</td>
                                </tr>
                            </thead>
                            <button class="btn btn-primary" style="float:right; margin-top: 1%;">
                                <a style="color:floralwhite;" href="order.php?id=<?= $order['order_no'] ?>">
                                    View
                                </a>
                            </button>
                            <tbody>
                                <?php
                                $orders_items = getPaidOrderItems($order['order_no']);
                                foreach ($orders_items as $item) : 
                                    
                                ?>

                                    <tr>
                                        <td class="img">
                                            <img src="imgs/<?= $item['image_location'] ?>" width="50" height="50" alt="<?= $item['item_name'] ?>">
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
                            </tbody>
                        </table>
                        <div class="subtotal" style="float:left;">
                            <span class="text" style="font-weight:bold;">Status: </span>
                            <td class="status"><?= $item['status'] ?></td>
                            <?php endforeach; ?>
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
                    <a href="orders?p=<?= $current_page - 1 ?>">Prev</a>
                <?php endif; ?>
                <?php if ($total_orders > ($current_page * $num_orders_on_each_page) - $num_orders_on_each_page + $total_orders) : ?>
                    <a href="orders?p=<?= $current_page + 1 ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
<? include "./footer.php"?>

</html>