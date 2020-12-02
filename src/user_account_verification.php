<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
        include "./header.php";
        require __DIR__ . "/functions/email.php";
        if(isset($_GET['resend'])){
            $email_to_send = base64_decode($_GET['resend']);
            send_email($email_to_send);
            header('Location: ./handle.php');
        }
        else{
            $url_params= unserialize(urldecode($_GET['values']));
            if($url_params['status'] == "yes"){
                echo "<title>Confirmation Sent</title>";
            }
            else{
                echo "<title>Confirmation Error</title>";
            }
        }
       
    ?>
</head>

<body>
    <div class="container-fluid" style="padding-left: 5%; padding-top: 3%;">
        <div class="row">
            <div class="col-md-5">
            <?php
            if(isset($_GET['values'])){
                $url_params= unserialize(urldecode($_GET['values']));
                if($url_params['status'] == "yes"){
                    echo "<h2>Account Verified</h2>
                    <p>Thank you for verifying your account. Your account has been <b>verified</b></p>
                    <p> Click on button at top right corner to login :)";
                }
                else{
                    $email = $url_params['email'];
                    echo "<h2>Account Verification Error</h2>
                    <p>Verification Link has expired, click <a href=\"./user_account_verification.php?resend=".$email."\"><b>me</b></a> to resend verification email</p>";
                }
            }
               
            ?>
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