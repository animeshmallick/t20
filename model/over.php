<?php
class Over {
    private $match_id;
    private $innings;
    private $over_id;
    private $runs;
    private $wickets;
    private $is_valid;

    function __construct($match_id, $innings, $runs, $over_id, $wickets, $is_valid) {
        $this->match_id = $match_id;
        $this->innings = $innings;
        $this->runs = $runs;
        $this->over_id = $over_id;
        $this->wickets = $wickets;
        $this->is_valid = $is_valid;
    }

    public function get_match_id(){return $this->match_id;}
    public function get_innings(){return $this->innings;}
    public function get_over_id(){return $this->over_id;}
    public function get_runs(){return $this->runs;}
    public function get_wickets(){return $this->wickets;}
    public function is_valid(){return $this->is_valid;}
}
