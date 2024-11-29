<?php
include "../Common.php";
include "../SlotScores.php";
include "../data.php";
$data = new Data();
$common = new Common();
$scores = new Scores($data);
$match_id = $common->get_cookie("match_id");
$series_id = $common->get_cookie("series_id");
$match_name = $common->get_cookie("match_name");
$scorecard = json_decode($common->get_scorecard_latest($series_id, $match_id));
$bid_innings = $common->get_cookie("innings");
$slot = $common->get_cookie("slot");

if($common->is_eligible_for_bid($scorecard, $bid_innings, $slot)){
    echo $scores->get_slot_runs($bid_innings,$scorecard,$slot);
}
else {
    echo "Over closed for bidding";
}

?>