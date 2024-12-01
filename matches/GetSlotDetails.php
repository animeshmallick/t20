<?php
header('Content-Type: application/json');
include "../Common.php";
include "../SlotScores.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$scores = new Scores($data);

$match_id = $_GET["match_id"];
$series_id = $_GET["series_id"];
$bid_innings = (int)$_GET["bid_innings"];
$slot = $_GET["slot"];
$amount = (float)$_GET['amount'];

$scorecard = $common->get_scorecard_latest($series_id, $match_id);

if($common->is_eligible_for_bid($scorecard, $bid_innings, $slot)){
    $predicted_runs = $scores->get_slot_runs($bid_innings,$scorecard,$slot);

    $all_bids = $common->get_all_bids_from_match($series_id, $match_id);

    $amount_collected = $common->get_total_amount_from_bids($all_bids, $bid_innings, $slot);
    $amount_distributed_less = $common->get_bid_distributed_amount($all_bids, $bid_innings, $slot, $predicted_runs, "less");
    $amount_distributed_more = $common->get_bid_distributed_amount($all_bids, $bid_innings, $slot, $predicted_runs, "more");

    $remaining_amount_less = $amount_collected - $amount_distributed_less;
    $remaining_amount_more = $amount_collected - $amount_distributed_more;

    $total = $data->get_loss_capacity_for_each_slot() + $amount;
    $diff = abs($remaining_amount_more - $remaining_amount_less);

    if ($diff > $data->get_loss_capacity_for_each_slot()){
        $rate1 = 4;
        $rate2 = 0;
    }else {
        $x = ($total - $diff) / 2;
        $y = $total - $x;
        $rate1 = ($x / $total) * $data->get_max_rate_allowed_for_slots();
        $rate2 = ($y / $total) * $data->get_max_rate_allowed_for_slots();
    }

    $output = array(
        "predicted_runs" => $predicted_runs,
        "rate_1" => $rate1,
        "rate_2" => $rate2
    );
    echo json_encode($output);
}
else {
    echo json_encode(array("error" => "Biding Closed For This Slot"));
}
?>