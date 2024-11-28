<?php
class Scores {
	private $datahelper;
	function __construct(Data $datahelper){
		$this->datahelper = $datahelper;
	}		
	
	public function get_curr_balls($scorecard): int{
		$valid_balls = 0;
		foreach($scorecard->this_over as $ball){
			if($ball != "w" && $ball != "nb"){
				$valid_balls = $valid_balls + 1;}
		}
		$curr_balls = (($scorecard->over - 1) * 6) + $valid_balls;
		return $curr_balls;
	}
	
	public function get_curr_runs($bid_innings, $scorecard): int{
		if($bid_innings == 1){
			$curr_runs = $scorecard->team1_score->runs;}
		elseif($bid_innings == 2){
			$curr_runs = $scorecard->team2_score->runs;}
		else{
			echo "Invalid bid innings";}
		return $curr_runs; 
	}
	public function get_r1($curr_runs, $curr_balls, $slot): int{
		if($curr_balls == 0){
			return $this->datahelper->get_default_runs($slot);}
		return ($curr_runs / $curr_balls) * $this->datahelper->get_maxballs_for_slot($slot);	
	}

	public function get_r2_without_wickets($slot): int{
		return $this->datahelper->get_default_runs($slot);
	}
	
	public function update_r2_with_wickets($r2, $scorecard, $bid_innings): int{
		if($bid_innings == 1){
			$r2 -= ($scorecard->team1_score->wickets * $this->datahelper->get_wicket_multiplier()); }
		else{
			$r2 -= ($scorecard->team2_score->wickets * $this->datahelper->get_wicket_multiplier()); }
		foreach($scorecard->this_over as $ball){
			if(in_array("W",str_split($ball)))
				return $r2;
		}			
		return $r2 + 3;	
	}
	
	public function get_r($r1, $r2, $curr_balls, $slot): int{
		$r = $r2 - (($r2 - $r1)* $curr_balls) / $this->datahelper->get_maxballs_for_slot($slot);
		return $r;
	}
}
?>