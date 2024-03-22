<?php
class Ball {
    private $match_id;
    private $inning;
    private $ball_id;
    private $run;
    private $wicket;
    function __construct($match_id, $inning, $ball_id, $run, $wicket){
        $this->inning = $inning;
        $this->ball_id = $ball_id;
        $this->run = $run;
        $this->match_id = $match_id;
        $this->wicket = $wicket;
    }

    public function get_innings(){return $this->inning;}
    public function get_ball_id(){return $this->ball_id;}
    public function get_runs(){return $this->run;}
    public function get_match_id(){return $this->match_id;}
    public function get_wickets(){return $this->wicket;}
}
