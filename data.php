<?php
class Data {
    private int $max_rate_allowed_for_slots;
    private string $amazon_api_endpoint;
    private string $auth_cookie_name;
    private string $path;
	private int $default_runs_slotA;
	private int $default_runs_slotB;
	private int $default_runs_slotC;
	private int $default_runs_slotD;
	private int $balls_slotA;
	private int $balls_slotB;
	private int $balls_slotC;
	private int $balls_slotD;
    private int $wicket_multiplier;
    private string $new_user_pending_status;
    private float $loss_capacity_for_each_slot;

    function __construct(){
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            $this->path = "http://localhost/t20/";
        }else {
            $this->path = "https://www.crickett20.in/T20/";
        }
        $this->auth_cookie_name = "user_ref_id";
		$this->default_runs_slotA = 53;
		$this->default_runs_slotB = 90;
		$this->default_runs_slotC = 144;
		$this->default_runs_slotD = 180;
		$this->balls_slotA = 36;
		$this->balls_slotB = 60;
		$this->balls_slotC = 96;
		$this->balls_slotD = 120;
        $this->new_user_pending_status = "pending";
        $this->wicket_multiplier = 6;
        $this->loss_capacity_for_each_slot = 1000.0;
        $this->amazon_api_endpoint = "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com";
        $this->max_rate_allowed_for_slots = 4;
    }

    public function get_auth_cookie_name(): string
    { return $this->auth_cookie_name; }
    public function get_path(): string
    { return $this->path; }
    public function get_wicket_multiplier(): int
    { return $this->wicket_multiplier; }
	public function get_default_runs($slot){
        if($slot == 'a')
            return $this->default_runs_slotA;
        elseif($slot == 'b')
            return $this->default_runs_slotB;
        elseif($slot == 'c')
            return $this->default_runs_slotC;
        elseif($slot == 'd')
            return $this->default_runs_slotD;
    }
	public function get_maxballs_for_slot($slot) : int
	{ 
		switch($slot){
			case "a":{
				$maxballs = $this->balls_slotA;
				break;
			}

			case "b":{
				$maxballs = $this->balls_slotB;
				break;
			}

			case "c":{
				$maxballs = $this->balls_slotC;
				break;
			}

			case "d":{
				$maxballs = $this->balls_slotD;
				break;
			}

			default:{
				$maxballs = -1;
			}
		}
		return $maxballs;
	}

    public function get_new_user_pending_status(): string
    {
        return $this->new_user_pending_status;
    }

    public function get_loss_capacity_for_each_slot(): float
    {
        return $this->loss_capacity_for_each_slot;
    }
    public function get_amazon_api_endpoint(): string
    {
        return $this->amazon_api_endpoint;
    }

    public function get_max_rate_allowed_for_slots()
    {
        return $this->max_rate_allowed_for_slots;
    }
}
?>