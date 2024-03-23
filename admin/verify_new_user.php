<html lang="en">
<head>
    <title>Admin- Verify New User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style.css?version=101">
</head>
<?php
include "../data.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') { ?>
    <body>
    <div class="header"><h1>Activate New User</h1></div>
        <div class="container">
            <div class="login form">
                <header>Activate New User</header>
                <form action="verify_new_user.php" method="POST">
                    <label for="phone">Phone Number:</label>
                    <input type="number" id="phone" name="phone" placeholder="9998887776" required>
                    <label for="otp">OTP:</label>
                    <input type="text" id="otp" name="otp" placeholder="********" required>
                    <input type="submit" value="Submit" class="button">
                    <p id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></p>
                </form>
                <a class="button wide login" href="index.php">Go Home</a>
            </div>
        </div>
    </body>
<?php }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];
    $ref_id = $_POST['otp'];
    $data = new Data();
    $connection = $data->get_connection();
    if (is_valid_verification($connection, $phone, $ref_id, $data->get_user_pending_status())) {
        activate_user($connection, $ref_id, $data->get_user_active_status());
    }else { ?>
    <body>
        <div class="header"><h1>IPL - 2024 - Registration</h1></div>
        <div class="row">
            <div class="col-6 col-s-9">
                <h2 class="error">User Activation Failed due to invalid OTP/Phone.</h2>
                <a href="index.php">Go Home</a>
            </div>
        </div>
    </body>
        <?php
    }
}

function is_valid_verification($connection, $phone, $ref_id, $status) {
    $sql = "Select * from `users` where `phone`=$phone and `ref_id`=$ref_id and `status`='$status'";
    return $connection->query($sql)->num_rows == 1;
}
function activate_user($connection, $ref_id, $status) {
    $sql = "UPDATE `users` SET `status`='$status' WHERE `ref_id`='$ref_id'";
    if ($connection->query($sql) === True) { ?>
    <body>
        <div class="header"><h1>IPL - 2024 - Registration</h1></div>
        <div class="row">
            <div class="col-6 col-s-9">
                <h2 class="success">Activation Successful.</h2>
                <a href="index.php">Go Home</a>
            </div>
        </div>
    </body>
    <?php
    } else { ?>
        <body>
        <div class="header"><h1>IPL - 2024 - Registration</h1></div>
        <div class="row">
            <div class="col-6 col-s-9">
                <h2 class="error">Activation Failed.</h2>
                <a href="index.php">Go Home</a>
            </div>
        </div>
        </body>
<?php
    }
}
?>
</html>