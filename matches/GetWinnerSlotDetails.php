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
$session = $common->get_cookie("session");
$amount = (float)$_GET['amount'];

$scorecard = $common->get_preloaded_scorecard();
if ($scorecard == null)
    $scorecard = $common->get_scorecard_latest($series_id, $match_id, "GetWinnerSlotDetails");

$all_bids = $common->get_all_bids_from_match($series_id, $match_id, 'winner');
$rates = $common->get_winner_rates($all_bids, $amount);
$output = array(
    "team_a" => $scorecard->teams[0],
    "team_b" => $scorecard->teams[1],
    "rate_1" => $rates[0],
    "rate_2" => $rates[1]
);
echo json_encode($output)
?>