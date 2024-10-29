<?php
class Data {
    public $base_amount_for_rate_conversion;
    private $db_ip;
    private $db_username;
    private $db_password;
    private $db_name;
    private $referral_bonus;
    private $zero_referral_bonus;
    private $user_pending_status;
    private $user_active_status;
    private $connection;
    private $verification_phone_number;
    private $path;
    private $auth_cookie_name;
    private $min_recharge_amount_for_referral;
    private $bonus_recharge_type;
    private $initial_wallet_balance;
    private $admin_auth_cookie_name;
    private $match_live_status;
    private $bid_cancel_time;

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
        $this->base_amount_for_rate_conversion = 100.0;
        $this->connection = new mysqli($this->db_ip, $this->db_username, $this->db_password, $this->db_name);
    }

    public function get_referral_bonus(){ return $this->referral_bonus; }
    public function get_zero_referral_bonus(){ return $this->zero_referral_bonus; }
    public function get_user_pending_status(){ return $this->user_pending_status; }
    public function get_connection(){ return $this->connection; }
    public function get_verification_phone_number() { return $this->verification_phone_number; }
    public function get_path(){ return $this->path; }
    public function get_auth_cookie_name() { return $this->auth_cookie_name; }
    public function get_user_active_status() { return $this->user_active_status; }
    public function get_bonus_recharge_type() { return $this->bonus_recharge_type; }
    public function get_min_recharge_amount_for_referral(){ return $this->min_recharge_amount_for_referral;}
    public function get_initial_wallet_balance() {return $this->initial_wallet_balance;}
    public function get_admin_auth_cookie_name() {return $this->admin_auth_cookie_name;}
    public function get_match_live_status() { return $this->match_live_status;}
    public function get_bid_cancel_time() { return $this->bid_cancel_time;}
}
?>