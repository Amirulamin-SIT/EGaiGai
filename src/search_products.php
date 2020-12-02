<?php
require_once "./functions/dataValidation.php";
if (isset($_GET['search'])) {
    // The amounts of products to show on each page
    $num_products_on_each_page = 4;
    $current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
    $item_name = xssSanit($_GET['search']);

    // The current page, in the URL this will appear as index.php?page=products&p=1, index.php?page=products&p=2, etc...
    require_once "./functions/sql/item_sql.php";
    $products = getSearchItem($item_name, $current_page, $num_products_on_each_page);
    // Get the total number of products

    $total_products = mysqli_num_rows(getSearchItemNoLimit($item_name));
    $product_retrieved = mysqli_num_rows(getSearchItem($item_name, $current_page, $num_products_on_each_page));
}

?>

<!DOCTYPE html>
<html lang="">

<head>
    <title>Products</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <main>
        <?php include "./header.php"; ?>
        <div class="products content-wrapper">
            <h1>Products</h1>
            <?php if (empty($products) || $total_products == 0) : ?>
                <p class="centralized">
                    We couldn't find <b> <?= $item_name ?> </b> Please search for a another item.
                </p>

            <?php else : ?>
                <p><?= $total_products ?> Products</p>
                <div class="products-wrapper">
                    <?php foreach ($products as $product) : ?>
                        <a href="product.php?id=<?= $product['iditem'] ?>" class="product">
                            <img src="imgs/<?= $product['image_location'] ?>" style="max-width:180px; max-height:200px; width:auto; height:auto;" alt="<?= $product['item_Name'] ?>">
                            <span class="name"><?= $product['item_Name'] ?></span>
                            <span class="price">
                                &dollar;<?= $product['price'] ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="buttons">
                    <?php if ($current_page > 1) : ?>
                        <a href="products.php?p=<?= $current_page - 1 ?>">Prev</a>
                    <?php endif; ?>
                    <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + $product_retrieved) : ?>
                        <a href="products.php?p=<?= $current_page + 1 ?>">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
<?php include "./footer.php" ?>

</html>