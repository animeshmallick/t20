<?php
class Data {
    private string $db_ip;
    private string $db_username;
    private string $db_password;
    private string $db_name;
    private string $referral_bonus;
    private string $zero_referral_bonus;
    private string $user_pending_status;
    private string $user_active_status;
    private mysqli $connection;
    private string $verification_phone_number;
    private string $path;
    private string $auth_cookie_name;
    private int $min_recharge_amount_for_referral;
    private string $bonus_recharge_type;
    private int $initial_wallet_balance;
    private string $admin_auth_cookie_name;
    private string $match_live_status;
    private int $bid_cancel_time;
    private float $expected_runs_default;

    function __construct(){
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            $this->db_username = "root";
            $this->db_password = "";
            $this->path = "http://localhost/t20/";
        }else {
            $this->db_username = "cricket";
            $this->db_password = "Cactus@1549";
            $this->path = "https://www.crickett20.in/T20/";
        }
        $this->db_ip = "localhost";
        $this->db_name = "ipl_live";
        $this->user_pending_status = "pending";
        $this->zero_referral_bonus = '0';
        $this->referral_bonus = '100';
        $this->verification_phone_number = "8093155669 or 7250224216 or 9304468820";
        $this->auth_cookie_name = "user_ref_id";
        $this->user_active_status = "active";
        $this->bonus_recharge_type = "bonus";
        $this->min_recharge_amount_for_referral = 1000;
        $this->initial_wallet_balance = 50;
        $this->admin_auth_cookie_name = "admin_ref_id";
        $this->match_live_status = "live";
        $this->bid_cancel_time = 6; //Also change in the script.js file
        $this->expected_runs_default = 50.0;
        $this->connection = new mysqli($this->db_ip, $this->db_username, $this->db_password, $this->db_name);
    }

    public function get_referral_bonus(): string
    { return $this->referral_bonus; }
    public function get_zero_referral_bonus(): string
    { return $this->zero_referral_bonus; }
    public function get_user_pending_status(): string
    { return $this->user_pending_status; }
    public function get_connection(): mysqli
    { return $this->connection; }
    public function get_verification_phone_number(): string
    { return $this->verification_phone_number; }
    public function get_path(): string
    { return $this->path; }
    public function get_auth_cookie_name(): string
    { return $this->auth_cookie_name; }
    public function get_user_active_status(): string
    { return $this->user_active_status; }
    public function get_bonus_recharge_type(): string
    { return $this->bonus_recharge_type; }
    public function get_min_recharge_amount_for_referral(): int
    { return $this->min_recharge_amount_for_referral;}
    public function get_initial_wallet_balance(): int
    {return $this->initial_wallet_balance;}
    public function get_admin_auth_cookie_name(): string
    {return $this->admin_auth_cookie_name;}
    public function get_match_live_status(): string
    { return $this->match_live_status;}
    public function get_bid_cancel_time(): int
    { return $this->bid_cancel_time;}
    public function get_expected_runs_default($over): int
    {
        if ($over <= 6)
            return 54.0;
        else if ($over <= 10)
            return 90.0;
        else if ($over <= 16)
            return 150;
        else
            return 1900;
    }
}
?>