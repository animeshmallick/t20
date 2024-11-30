<?php
header('Content-Type: application/json');
include "../Common.php";
include "../SlotScores.php";
include "../data.php";
$data = new Data();
$common = new Common();
$scores = new Scores($data);
$match_id = $common->get_cookie("match_id");
$series_id = $common->get_cookie("series_id");
$match_name = $common->get_cookie("match_name");
$scorecard = $common->get_scorecard_latest($series_id, $match_id);
$bid_innings = $common->get_cookie("innings");
$slot = $common->get_cookie("slot");
$amount = (float)$_GET['amount'];
if($common->is_eligible_for_bid($scorecard, $bid_innings, $slot)){
    $predicted_runs = $scores->get_slot_runs($bid_innings,$scorecard,$slot);

    $all_bids = $common->get_all_bids_from_match($series_id, $match_id);

    $amount_collected = $common->get_total_amount_from_bids($all_bids, $bid_innings, $slot);
    $amount_distributed_less = $common->get_bid_distributed_amount($all_bids, $bid_innings, $slot, $predicted_runs, "less");
    $amount_distributed_more = $common->get_bid_distributed_amount($all_bids, $bid_innings, $slot, $predicted_runs, "more");

    $remaining_amount_less = $amount_collected - $amount_distributed_less + $data->get_loss_capacity_for_each_slot();
    $remaining_amount_more = $amount_collected - $amount_distributed_more + $data->get_loss_capacity_for_each_slot();

    if ($amount_collected == 0.0) {
        $rate1 = 2;
        $rate2 = 2;
    } else {
        $rate1 = ($remaining_amount_less + $amount) / $amount;
        $rate2 = ($remaining_amount_more + $amount) / $amount;
    }
    $output = array(
        "slot1" => "Runs Less Than " . $predicted_runs . " : Returns " . ($rate1 * $amount),
        "slot2" => "Runs More Than " . $predicted_runs . " : Returns " . ($rate2 * $amount)
    );
    echo json_encode($output);
}
else {
    echo json_encode(array("error" => "Biding Closed For This Slot"));
}
?>