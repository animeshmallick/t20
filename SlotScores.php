<?php
class Scores {
	private Data $datahelper;
	function __construct(Data $datahelper){
		$this->datahelper = $datahelper;
	}		
	
	public function get_curr_balls($scorecard): int{
		$valid_balls = 0;
		foreach($scorecard->this_over as $ball){
			if($ball != "w" && $ball != "nb"){
				$valid_balls = $valid_balls + 1;}
		}
		return ((($scorecard->over - 1) * 6) + $valid_balls);
	}
	
	public function get_curr_runs($bid_innings, $scorecard): int{
        return $bid_innings == 1 ? $scorecard->team1_score->runs : $scorecard->team2_score->runs;
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
		$r2 -= $bid_innings == 1 ? ($scorecard->team1_score->wickets * $this->datahelper->get_wicket_multiplier()) :
            ($scorecard->team2_score->wickets * $this->datahelper->get_wicket_multiplier());
		foreach($scorecard->this_over as $ball){
			if(in_array("W",str_split($ball)))
				return $r2;
		}			
		return $r2 + 3;	
	}
	
	public function get_r($r1, $r2, $curr_balls, $slot): int{
		$r = $r2 - (($r2 - $r1) * $curr_balls) / $this->datahelper->get_maxballs_for_slot($slot);
		return $r;
	}
    public function get_slot_runs($bid_innings, $scorecard, $slot): int{

            $curr_balls_played = $this->get_curr_balls($scorecard);
            if($curr_balls_played == 0)
                return $this->datahelper->get_default_runs($slot);
            $curr_runs = $this->get_curr_runs($bid_innings, $scorecard);
            $r1 = $this->get_r1($curr_runs, $curr_balls_played, $slot);
            $r2 = $this->get_r2_without_wickets($slot);
            $r2 = $this->update_r2_with_wickets($r2, $scorecard, $bid_innings);
            $r = $this->get_r($r1, $r2, $curr_balls_played, $slot);
            return $r;
    }
}
?>