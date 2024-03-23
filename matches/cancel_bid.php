<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style.css?version=<?php echo time(); ?>">
    <title>Cancel Bid</title>
</head>
<body>

<?php

include '../data.php';
include '../Common.php';
include '../bid_data.php';
$data = new Data();
$common = new Common();
$bid_data = new Bid_data();

$bid_id = $_GET['bid_id'];
$ref_id = $common->get_auth_cookie($data->get_auth_cookie_name());
$status = $bid_data->get_bid_placed_status();

$sql = "Select * from `bid_table` where `bid_id`=$bid_id and `ref_id`=$ref_id and `status`='$status'";
$result = $data->get_connection()->query($sql);
if ($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $amount = floatval($row['amount']);
    $bid_timestamp = strtotime($row['time_stamp']);
    date_default_timezone_set('Asia/Kolkata');
    $current_timestamp = time();

    if (($current_timestamp - $bid_timestamp) <= 5) {
        $sql = "UPDATE `bid_table` SET `status`='bid_cancelled' WHERE `bid_id`=$bid_id";
        if ($data->get_connection()->query($sql)){
            $common->recharge_user_wallet($data->get_connection(), $ref_id, $amount, $bid_id, "bid_cancel", $common->get_unique_tran_id($data->get_connection())); ?>
            <div class="header"><h1>Bid Cancelled Successfully</h1></div>
            <a class="wide" href="index.php">Go To Home</a>
        <?php } else { ?>
            <div class="header"><h1>Failed to Cancel the BID</h1></div>
            <a class="wide" href="index.php">Go To Home</a>
        <?php }
    } else {
        $datetime = new DateTime();
        $datetime->setTimestamp($bid_timestamp + 5); ?>
        <div class="header"><h1>Cancellation time expired.</h1></div>
        <div class="sub-header"><h2>Cancellation Closed <?php echo $datetime->format('H:i:s'); ?></h2></div>
        <a class="wide" href="index.php">Go To Home</a>
    <?php
    }
} else {
    echo "Bid not found in database or maybe not placed by you";
}
