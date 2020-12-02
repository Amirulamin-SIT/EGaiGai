<?php
include "./header.php";
if(!isset($_SESSION["iduser"]))
{
    session_unset();
    session_destroy();
    header('Location: ./index.php');
}
require_once "./functions/sql/cart_sql.php";
$userid = $_SESSION['iduser'];
// If the user clicked the add to cart button on the product page we can check for the form data
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    // Set the post variables so we easily identify them, also make sure they are integer
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if (mysqli_num_rows(get_cart_item($userid, $product_id)) == 0) {
        add_to_cart($userid, $product_id, $quantity);
    } else {

        update_cart_quantity($userid, $product_id, $quantity);
    }
    $product_name = get_item($_POST['product_id']);
    $_SESSION['message'] = $product_name['item_Name'] . " has been added.";

}

// Remove product from cart, check for the URL param "remove", this is the product id, make sure it's a number and check if it's in the cart
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    // Remove the product from the shopping cart
    //unset($_SESSION['cart'][$_GET['remove']]);
    remove_cart_item($userid, $_GET['remove']);
    $product_name = get_item($_GET['remove']);

    $_SESSION['message'] = $product_name['item_Name'] . " has been removed.";
}

// Update product quantities in cart if the user clicks the "Update" button on the shopping cart page
if (isset($_POST['update'])) {
    // Loop through the post data so we can update the quantities for every product in cart
    foreach ($_POST as $k => $v) {
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            $id = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            // Always do checks and validation
            if (is_numeric($id) && $quantity > 0) {
                // Update new quantity
                //$_SESSION['cart'][$id] = $quantity;
                //}
                update_cart_item($userid, $id,  $quantity);
            }
        }
    }
    // Prevent form resubmission...
    header('Refresh:0');
    exit;
}

// Send the user to the place order page if they click the Place Order button, also the cart should not be empty
if (isset($_POST['placeorder'])) {
    header('Location: ./checkout.php');
    exit;
}

if (isset($_POST['clear'])) {
    clear_user_cart($_SESSION['iduser']);
}

// Check the session variable for products in cart
// Get all items in cart
$cart_item = get_full_cart($userid);
$subtotal = 0.00;
foreach ($cart_item as $item) {
    $subtotal += (float)$item['price'] * $item['quantity'];
}

?>

<body>
    <main>


        <div class="cart content-wrapper">
            <h1>Shopping Cart</h1>
            <form action="cart.php" method="post">
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
                        <?php if (empty($cart_item)) : ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">You have no products added in your Shopping Cart</td>
                            </tr>
                        <?php else : ?>
                            <?php if (isset($_SESSION['message'])) : ?>
                                <div class="alert alert-success">
                                    <?php
                                    echo $_SESSION['message'];
                                    unset($_SESSION['message']);
                                    ?>
                                </div>
                            <?php endif ?>
                            <?php foreach ($cart_item as $item) : $product = get_item($item['item_id']) ?>
                                <tr>
                                    <td class="img">
                                        <a href="product.php?id=<?= $item['item_id'] ?>">
                                            <img src="imgs/<?= $item['image_location'] ?>" width="50" height="50" alt="<?= $item['item_name'] ?>">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="product.php?id=<?= $item['item_id'] ?>"><?= $item['item_name'] ?></a>
                                        <br>
                                        <a href="cart.php?remove=<?= $item['item_id'] ?>" class="remove">Remove</a>
                                    </td>
                                    <td class="price">&dollar;<?= $item['price'] ?></td>
                                    <td class="quantity">
                                        <input type="number" name="quantity-<?= $item['item_id'] ?>" value="<?= $item['quantity'] ?>" min="1" max="<?= $product['quantity'] ?>" placeholder="Quantity" required>
                                    </td>
                                    <td class="price">&dollar;<?= $item['quantity'] * $item['price'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="subtotal">
                    <span class="text">Subtotal</span>
                    <span class="price">&dollar;<?= $subtotal ?></span>
                </div>
                <div class="buttons">
                    <input type="submit" value="Clear" name="clear">
                    <input type="submit" value="Update" name="update">
                    <input type="submit" value="Place Order" name="placeorder">
                </div>
            </form>
        </div>
    </main>
</body>
<?php include "./footer.php" ?>