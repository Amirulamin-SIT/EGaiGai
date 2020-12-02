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
$num_products_on_each_page = 4;
// The current page, in the URL this will appear as index.php?page=products&p=1, index.php?page=products&p=2, etc...
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
// Select products ordered by the date added
require_once "../functions/sql/item_sql.php";
$products = getAllItemAdmin($current_page, $num_products_on_each_page);

// Get the total number of order
//maybe get total number of item from order
$total_products = mysqli_num_rows(getAllItemNoLimitAdmin());
$product_retrieved = mysqli_num_rows(getAllItemAdmin($current_page, $num_products_on_each_page));

$x = 1;

?>

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
            <h1>Product List</h1>
            <p><?= $total_products ?> Products</p>
            <?php if (empty($products)) : ?>
                <h3 style="text-align:center;"> You have no products in your product list.</h3>
            <?php else : ?>
                <?php if (isset($_SESSION['message'])) : ?>
                    <div class="alert alert-success">
                        <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        ?>
                    </div>
                <?php endif ?>
                <table style="margin-top: -30px;">
                    <thead>
                        <tr>
                            <td colspan="2">Product</td>
                            <td>Price</td>
                            <td>Quantity</td>
                            <td>Disabled</td>
                            <td colspan="2">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $item) : ?>
                            <?php if ($item['disabled'] == 0) {
                                $item['disabled'] = "No";
                            } else {
                                $item['disabled'] = "Yes";
                            }
                            ?>

                            <tr>
                                <td class="img">
                                    <img src="../imgs/<?= $item['image_location'] ?>" width="50" height="50" alt="<?= $item['item_name'] ?>">
                                    </a>
                                </td>
                                <td>
                                    <?= $item['item_Name'] ?>
                                </td>
                                <td class="price">&dollar;<?= $item['price'] ?></td>
                                <td class="quantity"><?= $item['quantity'] ?></td>
                                <td class="disabled"><?= $item['disabled'] ?></td>
                                <td>
                                    <a style='color:dodgerblue;' href="edit_product.php?id=<?= $item['iditem'] ?>" class="edit_btn">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <td colspan="5" style="text-align: center;">
                            <a style="color:white" href="add_product.php">
                                <button class="btn btn-primary"> + </button></a>

                        </td>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="products" style="margin-right: 12%;">
            <div class="buttons">
                <?php if ($current_page > 1) : ?>
                    <a href="edit_products.php?p=<?= $current_page - 1 ?>">Prev</a>
                <?php endif; ?>
                <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + $product_retrieved) : ?>
                    <a href="edit_products.php?p=<?= $current_page + 1 ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </main>

</body>
<?php include "../footer.php" ?>