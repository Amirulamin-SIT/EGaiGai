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

$success_msg = "";
$err_msg = "";
$item_name = "";
$desc = "";
$price = "";
$quantity = "";
$location = "";
$target_dir = "";
?>

<!DOCTYPE html>
<html lang="">

<head>
    <title>Add Product</title>
    <title>Edit Orders</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
</head>

<body>

    <?php

    // Whitelist allowed extensions. Only allow safe and critical extensions for business functionality
    $allowedExts = array('jpeg', 'jpg', 'png');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //echo print_r($_POST);
        if (isset($_POST['item_name']) && isset($_POST["desc"]) && isset($_POST["price"]) && isset($_POST["quantity"])) {
            $item_name = $_POST['item_name'];
            $desc = $_POST["desc"];
            $price = $_POST["price"];
            $quantity = $_POST["quantity"];

            require_once '../functions/add_product_validation.php';

            if (true !== $item_name_result = validate_item_name($item_name)) {

                $err_msg = $item_name_result;
            }

            // Get result and validate item description
            elseif (true !== $item_desc_result = validate_item_desc($desc)) {
                $err_msg = $item_desc_result;
            }

            // Get result and validate item price
            elseif (true !== $item_price_result = validate_item_price($price)) {
                $err_msg = $item_price_result;
            }

            // Get result and validate item quantity
            elseif (true !== $item_quantity_result = validate_item_num($quantity)) {
                $err_msg = $item_quantity_result;
            }

            // Check for file upload errors 
            elseif (($_FILES['img_upload']['size'] == 0) && ($_FILES['img_upload']['error'] == UPLOAD_ERR_NO_FILE)) {
                $err_msg = "No item image uploaded";
            }

            // Add item to DB
            else {

                // Be sure we're dealing with an upload
                if (is_uploaded_file($_FILES['img_upload']['tmp_name']) === false) {
                    throw new \Exception('Error on upload: Invalid file definition');
                }

                // Get last file extension
                $explode = explode('.', $_FILES['img_upload']['name']);
                $uploadExt = end($explode);

                $tmpName = $_FILES['img_upload']['tmp_name'];
                // basename() may prevent directory traversal attacks, but further validations are required
                $name = basename($_FILES['img_upload']['name']);
                require_once "../functions/file_upload_validation.php";
                $newFileName = new_file_name($name);
                $actualFileType = check_file_type($tmpName);

                // Checks for all possible image files requirements
                if ((($_FILES['img_upload']['type'] == 'image/jpeg') || ($_FILES['img_upload']['type'] == 'image/jpg') || ($_FILES['img_upload']['type'] == 'image/pjpeg') || ($_FILES['img_upload']['type'] == 'image/x-png') || ($_FILES['img_upload']['type'] == 'image/png'))
                    && in_array($uploadExt, $allowedExts)
                    && (($actualFileType == 'image/jpeg') || ($actualFileType == 'image/jpg') || ($actualFileType == 'image/pjpeg') || ($actualFileType == 'image/x-png') || ($actualFileType == 'image/png'))
                ) {
                    // Used only if storing in DB as BLOB
                    // $readFile = fopen($tmpName,'r');
                    // $content = fread($readFile, filesize($tmpName));
                    // // https://www.w3schools.com/php/func_string_addslashes.asp
                    // $content = addslashes($content);
                    // fclose($readFile);

                    require_once "../functions/sql/item_sql.php";
                    $location = $target_dir . $newFileName;
                    $result = add_item($item_name, $desc, $price, $quantity, $location);
                    if ($result !== true) {
                        $err_msg = $result;
                    } else {
                        $location = "..\\" . $location;
                        move_uploaded_file($tmpName, $location);
                        $success_msg = "Successfully added $item_name";
                        // header('Location: ./add_product.php');
                    }
                    // Add SQL function (insert) to store into DB Here
                    // File name -> VARCHAR(64) NOT NULL -> Use $newFileName
                    // File content -> BLOB NOT NULL -> Use $content

                }
            }
        }
    }

    ?>

    <div class="product content-wrapper">
        <h1>Add Product</h1>
        <?php
        if ($err_msg != '') {
            echo '<div class="alert alert-danger" role="error">' .
                $err_msg
                . '</div>';
        }

        if ($success_msg != '') {
            echo '<div class="alert alert-success" role="success">' .
                $success_msg
                . '</div>';
        }
        ?>

        <div class="panel-body col-md-7">
            <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="item_name" class="col-md-3 control-label">Item Name:</label>
                    <div class="col-md-9">
                        <input type="item_name" class="form-control" name="item_name" placeholder="Item Name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="file" class="col-md-6 control-label">Select image to upload:</label>
                    <div class="col-md-9">
                        <input type="file" name="img_upload" placeholder="img_upload" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="desc" class="col-md-3 control-label">Description:</label>
                    <div class="col-md-9">
                        <textarea class="form-control" rows="10" name="desc" placeholder="Description"></textarea>
                    </div>
                </div>
                <div class=" form-group">
                    <label for="price" class="col-md-3 control-label">Price:</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="price" placeholder="Price" required>
                    </div>
                </div>
                <div class=" form-group">
                    <label for="quantity" class="col-md-3 control-label">Quantity</label>
                    <div class="col-md-9">
                        <input type="number" name="quantity" min="0" max="10" placeholder="Quantity" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-3 col-md-9">
                        <button id="btn" type="submit" name="add" class="btn btn-info"><i class="icon-hand-right"></i>Add Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

</body>

</html>
<?php include "../footer.php" ?>