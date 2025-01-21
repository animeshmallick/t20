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
$session = $_GET["session"];
$room = intval($_GET["room"]);
$amount = (float)$_GET['amount'];

$scorecard = $common->get_scorecard_latest($series_id, $match_id, "GetSessionSlotDetails");

if($common->is_eligible_for_session_bid($session, $scorecard->over_id)){
    $predicted_runs = $scores->get_slot_runs($session[1], $scorecard, $session[0]);
    $rates = $common->get_rates($series_id, $match_id, $session, $room, $amount);

    $output = array(
        "predicted_runs_a" => (int)($predicted_runs - 1.5),
        "predicted_runs_b" => (int)($predicted_runs + 1.5),
        "rate_1" => $rates[0],
        "rate_2" => $rates[1],
        "rate_3" => $rates[2]
    );
    echo json_encode($output);
}
else {
    echo json_encode(array("error" => "Biding Closed For This Slot"));
}
?>