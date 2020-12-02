<?php
//retrieve the sandbox keys
require_once dirname(__FILE__)."/crypto.php";
$creds = parse_ini_string(decryptIni(".paypal_creds.ini.enc"));
$sandbox = $creds["client_id"];
?>
<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
</head>

<body>
    <?php 
    include "./header.php";
    require_once dirname(__FILE__)."/functions/sql/checkout_sql.php";
    if(isset($_SESSION["iduser"]))
    {
        $iduser = $_SESSION["iduser"];
        $order_no = inCheckout($iduser);
        if ($order_no === false)
        {
            header('Location: ./cart.php');
        }
    }else{
        session_unset();
        session_destroy();
        header('Location: ./index.php');
    }
        
    ?>

    <div class="container-fluid" style="padding-left: 5%; padding-top: 3%;">
        <div class="row">
            <div class="col-md-4">
                <h2>Cart</h2>
                <form role="form" action="./payment.php" method="post" autocomplete="off"> 
                    <div class="form-group">
                        <label for="text">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required="required"
                            value=''/>
                        <input type='hidden' id='order_no' name='order_no' value='<?php echo $order_no ?>'>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Go to Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>