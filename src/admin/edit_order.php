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
require_once "../functions/sql/order_sql.php";
// Check to make sure the id parameter is specified in the URL
if (isset($_GET['id'])) {
    // Prepare statement and execute, prevents SQL injection
    $order = get_order($_GET['id'],"TRUE");
    // Check if the product exists (array is not empty)
    if (!$order) {
        // Simple error to display if the id for the product doesn't exists (array is empty)
        die('Order does not exist!');
    }

    $address = get_order_address($_GET['id']);
    $status = get_order_status($_GET['id']);
} else {
    // Simple error to display if the id wasn't specified
    die('Order does not exist!');
}

if ($status['delivered'] == 0) {
    $status['delivered'] = "Undelivered";
} else {
    $status['delivered'] = "Delivered";
}
?>


<!DOCTYPE html>
<html lang="">

<head>
    <title>Orders</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <link href="../css/shipping.css" rel="stylesheet" type="text/css">

</head>

<body>
    <main>

        <div class="cart content-wrapper">
            <h1>Update Order <?= $_GET['id'] ?></h1>
            <div class="container">
                <div class="card-body">
                    <div class="track">
                        <div class="step <?php if ($status['status'] == "Confirmed" || $status['status'] == "Picked Up" || $status['status'] == "Deliverying" || $status['status'] == "Completed") echo ' active' ?> "> <span class=" icon"> <i class="fa fa-check"></i> </span> <span class="text">Order confirmed</span> </div>
                        <div class="step <?php if ($status['status'] == "Picked Up" || $status['status'] == "Deliverying" || $status['status'] == "Completed") echo ' active' ?> "> <span class="icon"> <i class="fa fa-user"></i> </span> <span class="text"> Picked by courier</span> </div>
                        <div class="step <?php if ($status['status'] == "Deliverying" || $status['status'] == "Completed") echo ' active' ?> "> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> Delivery </span> </div>
                        <div class="step <?php if ($status['status'] == "Completed") echo ' active' ?> "> <span class="icon"> <i class="fa fa-box"></i> </span> <span class="text">Completed</span> </div>
                    </div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <td colspan="2">Product</td>
                        <td>Price</td>
                        <td>Quantity</td>
                        <td>Total</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $subtotal = 0 ?>
                    <?php foreach ($order as $item) : ?>
                        <tr>
                            <td class="img">
                                <img src="../imgs/<?= $item['image_location'] ?>" width="50" height="50" alt="<?= $item['item_Name'] ?>">
                                </a>
                            </td>
                            <td>
                                <?= $item['item_Name'] ?>
                            </td>
                            <td class="price">&dollar;<?= $item['item_price'] ?></td>
                            <td class="quantity"><?= $item['quantity'] ?></td>
                            <td class="price">&dollar;<?= $item['item_price'] * $item['quantity'] ?></td>
                        </tr>
                        <?php $subtotal += $item['item_price'] ?>

                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="subtotal">
                <span class="text">Subtotal</span>
                <span class="price">&dollar;<?= $subtotal ?></span>
            </div>

            <div class="date">
                <span class="text">Order Date:</span>
                <span class="date"><?= $address['date'] ?></span>
            </div>

            <div class="address">
                <span class="text">Delivery Address:</span>
                <span class="deliver_add"><?= $address['delivery_address'] ?></span>
            </div>

            <div class="status">
                <span class="text">Order Status:</span>
                <?= $status['status']; ?>
            </div>


            <div style="margin-top: 5%;">
                <form class="form-horizontal" role="form" method="POST" action="">
                    <h3>Update order status:</h3>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="optradio" <?php if ($status['status'] == "Picked Up" || $status['status'] == "Deliverying" || $status['status'] == "Completed" || $status['status'] == "Cancelled") echo ' disabled' ?>>Picked Up
                        </label>
                    </div>
                    <div class="form-check-inline disabled">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="optradio" <?php if ($status['status'] == "Deliverying" || $status['status'] == "Completed" || $status['status'] == "Cancelled") echo ' disabled' ?>>Deliverying
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="optradio" <?php if ($status['status'] == "Completed" || $status['status'] == "Cancelled") echo ' disabled' ?>>Completed
                        </label>
                    </div>
                    <a style="color:floralwhite" href="edit_order.php?id=<?= $item['order_no'] ?>">
                        <button class="btn btn-danger" style="float:right; <?= $item['order_no'] ?>" <?php if ($status['status'] == "Picked Up" || $status['status'] == "Deliverying" || $status['status'] == "Completed" || $status['status'] == "Cancelled") echo ' disabled' ?>>
                            Cancel Order
                        </button>
                    </a>
                </form>

            </div>


        </div>

    </main>
</body>
<? include "../footer.php"?>

</html>