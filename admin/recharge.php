<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$ref_id = $common->get_cookie($data->get_auth_cookie_name());
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $common->is_user_an_admin()){ ?>
    <html>
    <head>
        <title>Recharge User</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <link rel="icon" type="image/x-icon" href="../cricket.ico">
        <script src = "../scripts.js"></script>
    </head>
    <body onload="fill_header();fill_footer();fill_controls()">
    <div id="header"></div>
    <div class="main_container">
        <div class="sub-title">Recharge User Balance</div>
        <form action="recharge.php" method="POST">
            <input type="text" name="recharge_id" value="<?php echo $common->get_unique_recharge_id();?>" hidden="hidden">
            <label class="label" for="phone">Phone Number:</label>
            <input type="number" placeholder="Phone Number" id="phone" name="phone" required>
            <div class="gap"></div>
            <label class="label" for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" placeholder="Amount" required>
            <input type="submit" class="button" value="Recharge">
        </form>
        <p class="error" id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></p>
    </div>
    <div class="separator"></div>
    <div id="main_controls"></div>
    <div class="separator"></div>
    <div id="footer"></div>
    </body>
    </html>
<?php } else if($_SERVER['REQUEST_METHOD'] === 'POST' && $common->is_user_an_admin() && $common->is_user_logged_in()) {
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];
    $recharge_id = $_POST['recharge_id'];
    $from_ref_id = $common->get_cookie($data->get_auth_cookie_name());
    $user = $common->get_user_from_phone($phone);
    if (!isset($user->error)){
        $to_ref_id = $user->ref_id;
        $response = $common->recharge_user($recharge_id, $from_ref_id, $to_ref_id, $amount);
        header('Location:recharge.php?msg='.$response->recharge_msg);
    }else{
        header('Location:recharge.php?msg='.$user->error);
    }
} else {
    header("Location: ".$data->get_path());
}