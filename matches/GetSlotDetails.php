<?php
include "../Common.php";
include "../data.php";
$common = new Common();
$data = new Data();
$match_id = $common->get_cookie("match_id");
$series_id = $common->get_cookie("series_id");
$match_name = $common->get_cookie("match_name");
$scorecard = json_decode($common->get_scorecard_latest($series_id, $match_id));
$bid_innings = $common->get_cookie("innings");
$slot = $common->get_cookie("slot");
$valid_balls = 0;
function get_curr_runs( $bid_innings, $scorecard, $balls_played, $balls_slot ): int{
	if($balls_played == 0)
		return 0;
	if($bid_innings == 1){
		$curr_runs = ($scorecard->team1_score->runs/$balls_played) * $balls_slot;}
	elseif($bid_innings == 2){
		$curr_runs = ($scorecard->team2_score->runs/$balls_played) * $balls_slot;}
	else{
		echo "Invalid bid innings";}
	return $curr_runs;
}
foreach($scorecard->this_over as $ball){
if($ball != "w" && $ball != "nb"){
		$valid_balls = $valid_balls + 1;
	}
}
$balls_played = (($scorecard->over - 1) * 6) + $valid_balls;	

switch($slot){
	
	case "a":{
		$eligible_overID = ($bid_innings * 100) + 6;
		if($scorecard->over_id <= $eligible_overID){
			$curr_runs = get_curr_runs($bid_innings, $scorecard, $balls_played, $data->get_maxballs_for_slot($slot));
			$predicted_runs = $data->get_exp_runs_slotA() - (($data->get_exp_runs_slotA() - $curr_runs) * $balls_played) / $data->get_maxballs_for_slot($slot);
			echo $predicted_runs;
		}	
		else{
			echo "Over closed for bidding";
		}
		break;
	}
	
	case "b":{
		$eligible_overID = ($bid_innings * 100) + 10;
		if($scorecard->over_id <= $eligible_overID){
			$curr_runs = get_curr_runs($bid_innings, $scorecard, $balls_played, $data->get_maxballs_for_slot($slot));
			$predicted_runs = $data->get_exp_runs_slotB() - (($data->get_exp_runs_slotB() - $curr_runs) * $balls_played) / $data->get_maxballs_for_slot($slot);
			echo $predicted_runs;
		}
		else{
			echo "Over closed for bidding";
		}
		break;
	}
	
	case "c":{
		$eligible_overID = ($bid_innings * 100) + 16;
		if($scorecard->over_id <= $eligible_overID){
			$curr_runs = get_curr_runs($bid_innings, $scorecard, $balls_played, $data->get_maxballs_for_slot($slot));
			$predicted_runs = $data->get_exp_runs_slotC() - (($data->get_exp_runs_slotC() - $curr_runs) * $balls_played) / $data->get_maxballs_for_slot($slot);
			echo $predicted_runs;
		}
		else{
			echo "Over closed for bidding";
		}
		break;
	}
	
	case "d":{
		$eligible_overID = ($bid_innings * 100) + 20;
		if($scorecard->over_id <= $eligible_overID){
			$curr_runs = get_curr_runs($bid_innings, $scorecard, $balls_played, $data->get_maxballs_for_slot($slot));
			$predicted_runs = $data->get_exp_runs_slotD() - (($data->get_exp_runs_slotD() - $curr_runs) * $balls_played) / $data->get_maxballs_for_slot($slot);
			echo $predicted_runs;
		}
		else{
			echo "Over closed for bidding";
		}
		break;
	}
	default:{
		echo "Invalid slot";
	}
}			
?>