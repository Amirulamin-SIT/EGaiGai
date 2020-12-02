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
if (isset($_GET['id'])) {
    // Prepare statement and execute, prevents SQL injection
    require_once "../functions/sql/item_sql.php";
    // Fetch the product from the database and return the result as an Array
    $product = get_itemDisabled($_GET['id']);
    // Check if the product exists (array is not empty)
    if (!$product) {
        // Simple error to display if the id for the product doesn't exists (array is empty)
        die('Product does not exist!');
    }
} else {
    // Simple error to display if the id wasn't specified
    die('Product does not exist!');
}



if (isset($_POST['update'])) {
    $item_name = $_POST['item_name'];
    $image_path = $_POST['filePath'];
    $desc = $_POST['desc'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];


    if (isset($_POST["disable"])) {
        $disabled = 1;
    } else {
        $disabled = 0;
    }



    update_item($_GET['id'], $item_name, $desc, $price, $quantity, $disabled);
    $_SESSION['message'] = "Product has been updated!";
    header('location: edit_products.php');
}



if (isset($_POST['delete'])) {

    remove_item($_GET['id']);
    $_SESSION['message'] = "Product has been removed!";
    header('location: edit_products.php');
}


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
        <div class="product content-wrapper">
            <div class="panel-body col-md-6">
                <h2>Edit Product</h2>
                <form class="form-horizontal" role="form" method="POST" action="">
                    <div class="form-group">
                        <label for="item_name" class="col-md-3 control-label">Item Name:</label>
                        <div class="col-md-9">
                            <input type="item_name" class="form-control" name="item_name" placeholder="Item Name" value="<?= $product['item_Name']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="file" class="col-md-6 control-label">Select image to upload:</label>
                        <div class="col-md-9">
                            <input type="file" name="filePath" placeholder="Path File" value="<?= $product['item_Name']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="desc" class="col-md-3 control-label">Description:</label>
                        <div class="col-md-9">
                            <textarea class="form-control" rows="10" name="desc" placeholder="Description" value="<?php echo $product['description']; ?>"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-md-3 control-label">Price:</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="price" placeholder="Price" value="<?= $product['price'] ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="col-md-3 control-label">Quantity:</label>
                        <div class="col-md-9">
                            <input type="number" name="quantity" value="<?= $product['quantity'] ?>" min="0" max="10" placeholder="Quantity" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="disable" class="col-md-3 control-label">Delist</label>
                        <div class="col-md-9">
                            <input type="checkbox" name="disable" <?php if ($product['disabled'] == 1) echo ' checked' ?>>
                        </div>
                    </div>

                    <button id=" btn" type="submit" name="update" value="update" class="btn btn-info"><i class=" icon-hand-right"></i>Save Changes</button>
                    </br>

                    <button id="btn" type="submit" name="delete" class="btn btn-danger"><i class="icon-hand-right"></i> DELETE ITEM</button>
                </form>
            </div>
        </div>
    </main>
</body>

<?php include "../footer.php" ?>