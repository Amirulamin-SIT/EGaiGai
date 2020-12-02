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
    <title>Payment</title>
</head>

<body>

    <?php 
    include "./header.php";
    if(!isset($_SESSION["iduser"]))
    {
        session_unset();
        session_destroy();
        header('Location: ./index.php');
    }
    require_once dirname(__FILE__)."/functions/sql/checkout_sql.php";
    require_once "./functions/dataValidation.php";
    if(isset($_POST["order_no"]) && isset($_SESSION["iduser"]) && isset($_POST["address"]))
    {
        $iduser = $_SESSION["iduser"];
        $order_no = $_POST["order_no"];
        $address = $_POST["address"];
        $address = xssSanit($address);
        if (!checkedout($order_no, $iduser, $address))
        {
            header('Location: ./checkout.php');
        }
        else {
        {
            $amt = get_order_price($order_no);
        }
        }
    }
    //TODO: REMOVE COMMENT 
    // header('Location: ./cart.php');
?>

    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $sandbox ?>&intent=authorize&currency=SGD">
        // Required. Replace SB_CLIENT_ID with your sandbox client ID.
    </script>
    <div class="container-fluid" style="padding-left: 5%; padding-top: 3%;">
        <div class="row"></div>
        <div class="row">
            <div class="col-md-4">
                <div id="paypal-button-container"></div>
            </div>
        </div>
    </div>

    <script>
        paypal.Buttons({
            createOrder: function (data, actions) {
                // This function sets up the details of the transaction, including the amount and line item details.
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: parseFloat('<?php echo $amt ?>'),
                            currency_code: 'SGD',
                            intent: 'authorize'
                        }
                    }]
                });
            },
            onApprove: async function (data, actions) {
                // Authorize the transaction
                actions.order.authorize().then(async function (authorization) {

                    // Get the authorization id
                    var authorizationID = authorization.purchase_units[0]
                        .payments.authorizations[0].id

                    // Call your server to validate and capture the transaction
                    let response = await fetch('./paypal-transaction-complete.php', {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            orderID: data.orderID,
                            order_no: '<?php echo $order_no ?>',
                            authorizationID: authorizationID,
                            userID: '<?php echo $iduser ?>'
                        })
                    });
                    if (response.ok) {
                        let text = await response.text();
                        alert(text);
                        if (text == "true") {
                            window.location.href = "./payment_accepted.php";
                        } else {
                            alert("Payment Capture has failed");
                        }
                    }

                });
            }
        }).render('#paypal-button-container');
        // This function displays Smart Payment Buttons on your web page.
    </script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

</body>

</html>