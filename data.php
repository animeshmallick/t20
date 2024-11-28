<?php
class Data {
    private string $auth_cookie_name;
    private string $path;
	private int $exp_runs_slotA;
	private int $exp_runs_slotB;
	private int $exp_runs_slotC;
	private int $exp_runs_slotD;
	private int $balls_slotA;
	private int $balls_slotB;
	private int $balls_slotC;
	private int $balls_slotD;
	private int $maxballs;
	private int $wicket_multiplier;

    function __construct(){
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            $this->path = "http://localhost/t20/";
        }else {
            $this->path = "https://www.crickett20.in/T20/";
        }
        $this->auth_cookie_name = "user_ref_id";
		$this->default_runs_slotA = 54;
		$this->default_runs_slotB = 90;
		$this->default_runs_slotC = 144;
		$this->default_runs_slotD = 180;
		$this->balls_slotA = 36;
		$this->balls_slotB = 60;
		$this->balls_slotC = 96;
		$this->balls_slotD = 120;
		$this->wicket_multiplier = 6;
		
    }

    public function get_auth_cookie_name(): string
    { return $this->auth_cookie_name; }
    public function get_path(): string
    { return $this->path; }
	public function get_default_runs($slot) : int
	{
		if($slot == "a"){
			return $this->default_runs_slotA;}
		elseif($slot == "b"){
			return $this->default_runs_slotB;}
		elseif($slot == "c"){
			return $this->default_runs_slotC;}
		elseif($slot == "d"){
			return $this->default_runs_slotD;}
		else{
			return -1;}
	}
	public function get_maxballs_for_slot($slot) : int
	{ 
		if($slot == "a"){
			return $this->balls_slotA;}
		elseif($slot == "b"){
			return $this->balls_slotB;}
		elseif($slot == "c"){
			return $this->balls_slotC;}
		elseif($slot == "d"){
			return $this->balls_slotD;}
		else{
			return -1;}
	}
	public function get_wicket_multiplier() : int
	{	
		return $this->wicket_multiplier;
	}
}	
?>