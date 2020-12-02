<?php
require dirname(__DIR__, 1).'/vendor/autoload.php';
require_once dirname(__FILE__, 1)."/functions/sql/checkout_sql.php";
require_once dirname(__FILE__, 1)."/functions/sql/order_sql.php";

//1. Import the PayPal SDK client that was created in `Set up Server-Side SDK`.
include "./PaypalClient.php";
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest;

function buildRequestBody()
{
    return "{}";
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
}

$json = file_get_contents('php://input');
$data = json_decode($json);

// 2. Set up your server to receive a call from the client
if (isset($data->orderID) && isset($data->order_no) && isset($data->authorizationID) && isset($data->userID))
{
    $orderID = $data->orderID;
    $order_no = $data->order_no;
    $authorizationID = $data->authorizationID;
    $iduser = $data->userID;

    //verify if order is valid
    //return failure if not exists
    if (!validate_order($order_no, $iduser))
    {
        echo "{'error': 'invalid order_no'}";
    }
    else{
        $request = new AuthorizationsCaptureRequest($authorizationID);
        $request->body = buildRequestBody();
        //$creds = parse_ini_file(dirname(__FILE__, 2)."/.credentials/paypal_creds.ini");
        $client = PaypalClient::client();

        //Get Order info from Paypal
        $response = $client->execute(new OrdersGetRequest($orderID));
        $ppAmt = $response->result->purchase_units[0]->amount->value;
        $orderPrice = get_order_price($order_no);

        //Validate price matches
        if ($ppAmt != $orderPrice)
        {
            echo "{'error': 'payment does not match price'}";
        }
        // Complete Order
        $response = $client->execute($request);
        if($response->result->status === "COMPLETED")
        {
            paid($order_no, $iduser);
            echo("true");
        }
    }
}
else{
    http_response_code(400);
}

?>