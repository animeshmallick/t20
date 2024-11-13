<?php
class Bid_data {
    private $max_rate;
    private $max_affordable_loss;
    private $lower_diff;
    private $upper_diff;
    private $bid_placed_status;
    private $bid_closed_status;

    function __construct(){
        $this->max_affordable_loss = 1000;
        $this->max_rate = 5;
        $this->lower_diff = 1.53;
        $this->upper_diff = 1.53;
        $this->bid_placed_status = "placed";
        $this->bid_closed_status = "settled";
    }

    public function get_max_affordable_loss() { return $this->max_affordable_loss;}
    public function get_max_rate(){return $this->max_rate;}
    public function get_lower_diff(){ return $this->lower_diff; }
    public function get_upper_diff(){ return $this->upper_diff; }
    public function get_bid_placed_status() { return $this->bid_placed_status;}
    public function get_bid_closed_status() {return $this->bid_closed_status;}
}
?>