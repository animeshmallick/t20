<?php
include "../data.php";
include "../bid_data.php";
include "../Common.php";
$data = new Data();
$bid_data = new Bid_data();
$common = new Common();

$match_id = $_GET['match_id'];
$old_status = $bid_data->get_bid_placed_status();

$sql = "Select * from `bid_table` where `match_id`='$match_id' and `status`='$old_status'";
$result = $data->get_connection()->query($sql);
while($row = $result->fetch_assoc()){
    $innings = $row['innings'];
    $over = $row['over_id'];
    $bid_id = $row['bid_id'];
    if ($common->over_started($data->get_connection(), $match_id, $innings, $over)) {
        $actual_run = $common->get_actual_run($data->get_connection(), $match_id, $innings, $over);
        if($actual_run != -1) {
            $lower_run_limit = $row['run_min'];
            $upper_run_limit = $row['run_max'];
            if ($actual_run >= $lower_run_limit && $actual_run <= $upper_run_limit) {
                $ref_id = $row['ref_id'];
                $rate = $row['rate'];
                $amount = $row['amount'];
                $total = $amount * $rate;
                $common->recharge_user_wallet($data->get_connection(), $ref_id, $total, $bid_id, "WIN", $common->get_unique_tran_id($data->get_connection()));
            }
            update_close_bid_status($data->get_connection(), $row['bid_id'], $bid_data->get_bid_closed_status());
        }
    }
}

function update_close_bid_status($connection, $bid_id, $status) {
    $sql = "UPDATE `bid_table` SET `status`='$status' WHERE `bid_id`='$bid_id'";
    if ($connection->query($sql) === True)
        echo "Bid ID ".$bid_id."Settled<br>";
}