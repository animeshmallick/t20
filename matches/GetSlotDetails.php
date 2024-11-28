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
$valid_balls = 0;

switch($slot){
	case "a":{
		if($common->is_eligible_for_bid($scorecard, $bid_innings, $slot)){
			$curr_balls_played = $scores->get_curr_balls($scorecard);
			$curr_runs = $scores->get_curr_runs($bid_innings, $scorecard);
			$r1 = $scores->get_r1($curr_runs, $curr_balls_played, $slot);
			$r2 = $scores->get_r2_without_wickets($slot);
			$r2 = $scores->update_r2_with_wickets($r2, $scorecard, $bid_innings);
			$r = $scores->get_r($r1, $r2, $curr_balls_played, $slot);
			echo $r;
			}	
		else{ echo "Over closed for bidding";}
		break;
	}
	
	case "b":{
		$eligible_overID = ($bid_innings * 100) + 10;
		if($scorecard->over_id <= $eligible_overID){
			$curr_runs = get_curr_runs($bid_innings, $scorecard, $balls_played, $data->get_maxballs_for_slot($slot));
			$predicted_runs = $data->get_exp_runs_slot($bid_innings, $scorecard, $balls_played, $slot) - (($data->get_exp_runs_slot($bid_innings, $scorecard, $balls_played, $slot) - $curr_runs) * $balls_played) / $data->get_maxballs_for_slot($slot);
			echo $predicted_runs;
		}
		else{echo "Over closed for bidding";}
		break;
	}
	
	case "c":{
		$eligible_overID = ($bid_innings * 100) + 16;
		if($scorecard->over_id <= $eligible_overID){
			$curr_runs = get_curr_runs($bid_innings, $scorecard, $balls_played, $data->get_maxballs_for_slot($slot));
			$predicted_runs = $data->get_exp_runs_slot($bid_innings, $scorecard, $balls_played, $slot) - (($data->get_exp_runs_slot($bid_innings, $scorecard, $balls_played, $slot) - $curr_runs) * $balls_played) / $data->get_maxballs_for_slot($slot);
			echo $predicted_runs;
		}
		else{ echo "Over closed for bidding";}
		break;
	}
	
	case "d":{
		$eligible_overID = ($bid_innings * 100) + 20;
		if($scorecard->over_id <= $eligible_overID){
			$curr_runs = get_curr_runs($bid_innings, $scorecard, $balls_played, $data->get_maxballs_for_slot($slot));
			$predicted_runs = $data->get_exp_runs_slot($bid_innings, $scorecard, $balls_played, $slot) - (($data->get_exp_runs_slot($bid_innings, $scorecard, $balls_played, $slot) - $curr_runs) * $balls_played) / $data->get_maxballs_for_slot($slot);
			echo $predicted_runs;
		}
		else{ echo "Over closed for bidding";}
		break;
	}
	default:{
		echo "Invalid slot";
	}
}			
?>