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

    function __construct(){
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            $this->path = "http://localhost/t20/";
        }else {
            $this->path = "https://www.crickett20.in/T20/";
        }
        $this->auth_cookie_name = "user_ref_id";
		$this->exp_runs_slotA = 54;
		$this->exp_runs_slotB = 90;
		$this->exp_runs_slotC = 144;
		$this->exp_runs_slotD = 180;
		$this->balls_slotA = 36;
		$this->balls_slotB = 60;
		$this->balls_slotC = 96;
		$this->balls_slotD = 120;
    }

    public function get_auth_cookie_name(): string
    { return $this->auth_cookie_name; }
    public function get_path(): string
    { return $this->path; }
	public function get_exp_runs_slotA(): int
	{ return $this->exp_runs_slotA; }
	public function get_exp_runs_slotB(): int
	{ return $this->exp_runs_slotB; }
	public function get_exp_runs_slotC(): int
	{ return $this->exp_runs_slotC; }
	public function get_exp_runs_slotD(): int
	{ return $this->exp_runs_slotD; }
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
}	
?>