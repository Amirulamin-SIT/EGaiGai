<?php

require_once "./functions/sql/item_sql.php";
// Check to make sure the id parameter is specified in the URL
if (isset($_GET['id'])) {
    // Prepare statement and execute, prevents SQL injection
    $product = get_item($_GET['id']);
    // Check if the product exists (array is not empty)
    if (!$product) {
        // Simple error to display if the id for the product doesn't exists (array is empty)
        die('Product does not exist!');
    }
} else {
    // Simple error to display if the id wasn't specified
    die('Product does not exist!');
}




?>

<!DOCTYPE html>
<html lang="">

<head>
    <title>Products</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <main>
        <?php include "./header.php" ?>
        <div class="product content-wrapper">
            <img src="imgs/<?= $product['image_location'] ?>" style="max-width:476px; max-height:556px; width:auto; height:auto;" alt="<?= $product['item_Name'] ?>">
            <div>
                <h1 class="name"><?= $product['item_Name'] ?></h1>
                <span class="price">
                    &dollar;<?= $product['price'] ?>
                </span>
                <form method="post" action="cart.php">
                    <input type="number" name="quantity" value="1" min="1" max="<?= $product['quantity'] ?>" placeholder="Quantity" required>
                    <input type="hidden" name="product_id" value="<?= $product['iditem'] ?>">
                    <?php
                    if(!isset($_SESSION["iduser"]))
                    {
                        echo '</form>
                        <label>Please <a href="login.php">LOGIN</a> before being able to add to cart</label>';
                    }else{
                    if ($product['quantity'] > 0) {
                        echo '<input type="submit" value="Add To Cart">';
                    } else {
                        echo '<input type="submit" value="Out of Stock" disabled="disabled">';
                    }
                }
                    ?>
                </form>
                <div class="description">
                    <?= $product['description'] ?>
                </div>
            </div>
        </div>
    </main>
</body>
<?php include "./footer.php" ?>

</html>