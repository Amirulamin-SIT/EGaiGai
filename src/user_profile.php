<?php
// session_start();
// include('./functions/sql/user.php');
include "./header.php";
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
} else {
    require_once "./functions/sql/auth.php";
    $user = $_SESSION["iduser"];
    $stmt = getUserDetails($user);
    $userDetail = mysqli_fetch_assoc($stmt);
}
?>

<body>
    <main>
        <div style="padding: 120px;">
            <div class="table-responsive">
                <div><span style="font-size:20px;">User Account Details:</span>
                    <div class="pull-right"><a href="./edit_profile.php" <?= $user ?>>Edit Account</a></div>
                    <table class="table">
                        <tr>
                            <th>Username</th>
                            <td><?php echo $userDetail['username'] ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $userDetail['email'] ?></td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
        </script>
    </main>
</body>

<?php include "./footer.php" ?>