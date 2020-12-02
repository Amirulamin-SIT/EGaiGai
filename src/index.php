<?php
include "./header.php";
require_once "./functions/sql/item_sql.php";
$recently_added_products = getRecentItem();

?>

<!DOCTYPE html>
<html lang="">

<head>
    <title>Index</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <main style="margin:auto">

        <div class="featured">
            <h2>E-GaiGai</h2>
            <p>The New Online Scamazon Retailer </p>
        </div>
        <div class="recentlyadded content-wrapper">
            <h2>Recently Added Products</h2>
            <div class="products">
                <?php foreach ($recently_added_products as $product) : ?>
                    <a href="product.php?id=<?= $product['iditem'] ?>" class="product">
                        <img src="imgs\<?= $product['image_location'] ?>" style="max-width:180px; max-height:200px; width:auto; height:auto;" alt="<?= $product['item_Name'] ?>">
                        <span class="name"><?= $product['item_Name'] ?></span>
                        <span class="price">
                            &dollar;<?= $product['price'] ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
<?php include "./footer.php" ?>

</html>