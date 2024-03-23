<html lang="en">
<head>
    <title>IPL 2024 - Recharge</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php
include "../data.php";
include "../Common.php";
$data = new Data();
$common = new Common();

if($common->get_auth_cookie($data->get_admin_auth_cookie_name()) > 0) {

function get_admin_name_from_ref_id($connection, $ref_id) {
    $sql = "Select `fname` from `admin` where `ref_id`=$ref_id";
    $result = $connection->query($sql);
    if($result->num_rows == 1)
        return $result->fetch_assoc()['fname'];
    return "Unknown User";
}
$admin = get_admin_name_from_ref_id($data->get_connection(), $common->get_auth_cookie($data->get_admin_auth_cookie_name()));

if ($_SERVER['REQUEST_METHOD'] === 'GET') { ?>
<div class="container">
    <div class="login form">
        <header>Recharge</header>
        <form action="recharge.php" method="POST">
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" placeholder="9998887776">
            <label for="amount">Amount:</label>
            <input type="text" id="amount" name="amount" placeholder="1000">
            <label for="admin">Recharge Admin:</label>
            <select name="admin" id="admin">
                <option value="<?php echo $admin; ?>"><?php echo $admin; ?></option>
            </select><br/><br/>
            <label for="type">Choose Recharge Type:</label>
            <select name="type" id="type">
                <option value="animesh">Normal</option>
                <option value="shubham">Bonus</option>
                <option value="shamim">Others</option>
            </select>
            <br/><br/>
            <label for="tran_id">Transaction ID:</label>
            <input type="number" id="tran_id" name="tran_id" value="<?php echo $common->get_unique_tran_id($data->get_connection()); ?>" readonly>
            <input type="submit" value="Submit" class="button">
            <p class="error" id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></p>
        </form>
        <a class="wide" href="index.php">Home</a>
    </div>
</div>
</body>
<?php } if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];
    $recharge_admin = $_POST['admin'];
    $tran_id = (int)$_POST['tran_id'];
    $type = $_POST['type'];
    $connection = $data->get_connection();
    $ref_id = $common->get_ref_id_from_phone($connection, $phone, $data->get_user_active_status());
    if ($ref_id != -1) {
        $common->recharge_user_wallet($connection, $ref_id, $amount, $recharge_admin, $type, $tran_id);
        if (((int)$amount) >= $data->get_min_recharge_amount_for_referral())
            $common->give_referral_bonus($connection, $ref_id, $data->get_referral_bonus(), $data->get_bonus_recharge_type(), $tran_id);
        ?>
        <body>
            <div class="header"><h1>Recharge Successful</h1></div>
            <a href="index.php">Go Home</a>
        </body>
        <?php
    }else{ echo "Phone number not valid or user not active"; }
}
} else
    header("Location: ".$data->get_path()."admin/index.php");
?>

</html>