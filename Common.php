<?php
class Common {
    private string $amazon_api_end_point;
    private string $path;
    function __construct($path, $amazon_api_end_point)
    {
        $this->path = $path;
        $this->amazon_api_end_point = $amazon_api_end_point;
    }
    public function is_user_logged_in(): bool
    {
        return (int)$this->get_cookie('user_ref_id') > 0 &&
            strlen($this->get_cookie('user_type')) > 0;
    }
    private function get_response_from_url($url): string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return response as string
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    public function get_all_matches(): array
    {
        $url = $this->amazon_api_end_point . '/get_all_matches';
        return json_decode($this->get_response_from_url($url));
    }
    public function get_scorecard_latest($series_id, $match_id)
    {
        $url = $this->amazon_api_end_point . '/scores/' . $series_id . '/' . $match_id . '/latest';
        return json_decode($this->get_response_from_url($url));
    }
    public function validate_unique_ref_id($ref_id): string
    {
        $url = $this->amazon_api_end_point . '/validate_ref_id/' . $ref_id;
        return json_decode($this->get_response_from_url($url))->result == true;
    }

    public function get_cookie(string $name): string
    {
        return $_COOKIE[$name] ?? "";
    }
    public function is_all_cookies_available(array $cookie_names): bool {
        foreach ($cookie_names as $cookie_name) {
            if ($this->get_cookie($cookie_name) == "") {
                return false;
            }
        }
        return true;
    }

    public function get_user_from_db(string $phone, string $password)
    {
        $url = $this->amazon_api_end_point . "/login/".$phone."/".$password;
        return json_decode($this->get_response_from_url($url));
    }
    public function get_user_from_ref_id(string $ref_id)
    {
        $url = $this->amazon_api_end_point . "/login/ref_id/".$ref_id;
        return json_decode($this->get_response_from_url($url));
    }
    public function is_new_phone_number(string $phone): bool
    {
        $url = $this->amazon_api_end_point . "/login/phone/".$phone;
        return !isset(json_decode($this->get_response_from_url($url))->id);
    }

    public function set_cookie(string $cookie_name, string $cookie_value): void
    {
        setcookie($cookie_name, $cookie_value, time() + (3600), "/");
    }

    public function is_valid_scorecard($scorecard): bool
    {
        return !isset($scorecard->error) && !str_contains($scorecard->source, 'amazon');
    }

    public function is_valid_slot(string $slot): bool
    {
        if ($slot == 'winner')
            return true;
        if (strlen($slot) != 2)
            return false;
        if ($slot[0] == 'a' || $slot[0] == 'b' || $slot[0] == 'c' || $slot[0] == 'd') {
            if ($slot[1] == 1 || $slot[1] == 2) {
                return true;
            }
        }
        return false;
    }

    public function is_valid_user(string $ref_id): bool
    {
        if ($ref_id == null || $ref_id == "")
            return false;
        $user = $this->get_user_from_ref_id($ref_id);
        if (isset($user->error))
            return false;
        return true;
    }
    public function is_active_user(string $get_auth_cookie_name): bool
    {
        $ref_id = $this->get_cookie($get_auth_cookie_name);
        if ($ref_id == null || $ref_id == "")
            return false;
        $user = $this->get_user_from_ref_id($ref_id);
        if (isset($user->error))
            return false;
        return isset($user->status) && $user->status == "active";
    }
    public function is_admin_user(string $ref_id): bool
    {
        if ($ref_id == null || $ref_id == "")
            return false;
        $user = $this->get_user_from_ref_id($ref_id);
        if (isset($user->error))
            return false;
        return isset($user->type) && $user->type == "admin";
    }

    public function delete_cookie(string $get_auth_cookie_name): void
    {
        setcookie($get_auth_cookie_name, "", time() - (3600), "/");
    }

    public function is_eligible_for_winner_bid($session): bool {
        if ($session != 'winner')
            return false;
        $scorecard = $this->get_scorecard_latest($this->get_cookie('series_id'),
                                                $this->get_cookie('match_id'));
        return !isset($scorecard->closing_soon);
    }
    public function is_eligible_for_session_bid($session): bool {
        if (!in_array($session[1], [1,2]))
            return false;
        if(!in_array($session[0], ['a', 'b', 'c', 'd']))
            return false;
        if ($session == 'winner')
            return false;
        if (!$this->is_valid_slot($session))
            return false;
        $bid_innings =  $session[1];
        $session = $session[0];
        $eligible_overID = 0;
        if($session == 'a'){
            $eligible_overID = ($bid_innings * 100) + 6;}
        elseif($session == 'b'){
            $eligible_overID = ($bid_innings * 100) + 10;}
        elseif($session == 'c'){
            $eligible_overID = ($bid_innings * 100) + 16;}
        elseif ($session == 'd'){
            $eligible_overID = ($bid_innings * 100) + 20;}
        if((int)$this->get_cookie('current_over_id') <= $eligible_overID){
            return true;}
        else{
            return false;}
    }

    public function insert_new_user(string $fname, string $lname, string $phone, string $password, string $ref_id,
                                    string $email, string $parent_ref_id, string $status): bool
    {
        $url = $this->amazon_api_end_point . '/save_new_user';
        $data = array(
            "id" => $ref_id,
            "email" => $email,
             "fname" => $fname,
             "lname" => $lname,
             "parent_ref_id" => $parent_ref_id,
             "password" => $password,
             "phone" => $phone,
             "ref_id" => $ref_id,
             "status" => $status,
             "timestamp" => date('Y-m-d H:i:s'),
             "balance" => 0
        );
        $json_data = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($json_data)));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            return false;
        }
        curl_close($ch);
        return true;
    }
    function get_all_bids_from_match(string $series_id, string $match_id, string $type): array
    {
        $url = $this->amazon_api_end_point . "/get_match_bids/" . $series_id . "/" . $match_id;
        $all_bids = json_decode($this->get_response_from_url($url));
        $bids = array();
        foreach ($all_bids as $bid) {
            if ($bid->type == $type)
                $bids[] = $bid;
        }
        return $bids;
    }

    public function get_unique_bid_id(): int
    {
        for ($i=0; $i<100; $i++){
            $new_bid_id = mt_rand(10000000, 99999999);
            if (!isset($this->get_bid_from_bid_id($new_bid_id)->bid_id))
                return $new_bid_id;
        }
        return -1;
    }
    public function get_bid_from_bid_id($bid_id)
    {
        $url = $this->amazon_api_end_point . "/get_all_bids";
        $all_bids = json_decode($this->get_response_from_url($url));
        foreach ($all_bids as $bid){
            if ($bid->bid_id == $bid_id)
                return $bid;
        }
        return null;
    }
    public function get_bid_bookie_details(string $series_id, $match_id, string $session, int $amount)
    {
        $url = $this->path . "matches/GetSessionSlotDetails.php?match_id=".$match_id."&series_id=".$series_id."&session=".$session."&amount=".$amount;
        $response = file_get_contents($url);
        $response = '{'.explode('{', $response)[1];
        return json_decode($response);
    }
    public function get_match_winner_bid_bookie_details(string $series_id, $match_id, int $amount)
    {
        $url = $this->path . "matches/GetWinnerSlotDetails.php?match_id=".$match_id."&series_id=".$series_id."&amount=".$amount;
        $response = file_get_contents($url);
        $response = '{'.explode('{', $response)[1];
        return json_decode($response);
    }
    public function insert_new_session_bid_to_db($bid_id, $ref_id, $series_id, $match_id, $session, $slot,
                                                 $runs_min, $runs_max, $rate, $amount): bool|string
    {
        if ($rate == null)
            return false;
        $bid_data = array(
            "id" => $bid_id,
            "bid_id" => $bid_id,
            "ref_id" => $ref_id,
            "series_id" => $series_id,
            "match_id" => $match_id,
            "innings" => $session[1],
            "session" => $session[0],
            "slot" => $slot,
            "runs_min" => $runs_min,
            "runs_max" => $runs_max,
            "rate" => $rate,
            "amount" => $amount,
            "status" => "placed",
            'type' => 'session',
            "timestamp" => time()
        );
        $url = $this->amazon_api_end_point . '/save_new_bid';
        $json_bid_data = json_encode($bid_data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($json_bid_data)));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$json_bid_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            return false;
        }
        curl_close($ch);
        return $response;
    }

    public function insert_new_winner_bid_to_db($bid_id, $ref_id, $series_id, $match_id, $slot,
                                                $rate, $amount): bool|string
    {
        $bid_data = array(
            "id" => $bid_id,
            "bid_id" => $bid_id,
            "ref_id" => $ref_id,
            "series_id" => $series_id,
            "match_id" => $match_id,
            "slot" => $slot,
            "rate" => $rate,
            "amount" => $amount,
            "status" => "placed",
            'type' => 'winner',
            "timestamp" => time()
        );
        $url = $this->amazon_api_end_point . '/save_new_bid';
        $json_bid_data = json_encode($bid_data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($json_bid_data)));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$json_bid_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            return false;
        }
        curl_close($ch);
        return $response;
    }

    public function is_new_bid_id(mixed $bid_id): bool
    {
        $res = $this->get_bid_from_bid_id($bid_id);
        return $res == null;
    }

    public function get_rates(array $all_bids, int $bid_innings, string $session, float $amount): array
    {
        $x = 0.0; $a = 0.0; $b = 0.0; $c = 0.0;
        foreach ($all_bids as $bid) {
            if ($bid->innings == $bid_innings && $bid->session == $session)
                $x += (float)($bid->amount);
        }
        $x = $x - ($x/100) + $amount;

        foreach ($all_bids as $bid) {
            if ($bid->innings == $bid_innings && $bid->session == $session && $bid->slot == 'x')
                $a += (float)($bid->amount);
        }

        foreach ($all_bids as $bid) {
            if ($bid->innings == $bid_innings && $bid->session == $session && $bid->slot == 'y')
                $b += (float)($bid->amount);
        }

        foreach ($all_bids as $bid) {
            if ($bid->innings == $bid_innings && $bid->session == $session && $bid->slot == 'z')
                $c += (float)($bid->amount);
        }

        $ga = max(($x - $a), 0.0);
        $gb = max(($x - $b), 0.0);
        $gc = max(($x - $c), 0.0);
        $g = $ga + $gb + $gc;


        $ra = $ga/$g;
        $rb = $gb/$g;
        $rc = $gc/$g;

        $f = 6 / ($ra + $rb + $rc);

        $ra *= $f;
        $rb *= $f;
        $rc *= $f;

        return [$ra, $rb, $rc];
    }

    public function get_winner_rates($all_bids, $amount): array
    {
        $x = 0.0; $a = 0.0; $b = 0.0;
        foreach ($all_bids as $bid) {
            $x += (float)($bid->amount);
        }
        $x = $x - ($x/100) + $amount;

        foreach ($all_bids as $bid) {
            if ($bid->slot == 'T1')
                $a += (float)($bid->amount);
        }

        foreach ($all_bids as $bid) {
            if ($bid->slot == 'T2')
                $b += (float)($bid->amount);
        }

        $ga = max(($x - $a), 0.0);
        $gb = max(($x - $b), 0.0);
        $g = $ga + $gb;


        $ra = $ga/$g;
        $rb = $gb/$g;

        $f = 4 / ($ra + $rb);

        $ra *= $f;
        $rb *= $f;

        return [$ra, $rb];
    }
    public function get_user_balance(string $ref_id): float
    {
        $url = $this->amazon_api_end_point."/get_user_balance/".$ref_id;
        $response = json_decode($this->get_response_from_url($url));
        if (isset($response->balance)) {
            return (float)$response->balance;
        }
        return -1.0;
    }

    public function get_recharge_from_recharge_id($recharge_id)
    {

    }
    public function get_unique_recharge_id(): int
    {
        for ($i=0; $i<100; $i++){
            $new_recharge_id = mt_rand(10000000, 99999999);
            $url = $this->amazon_api_end_point . "/get_recharge_details/".$new_recharge_id;
            $recharge = json_decode($this->get_response_from_url($url));
            if (!isset($recharge->id))
                return $new_recharge_id;
        }
        return -1;
    }
    public function update_balance(int $bid_id, string $ref_id, float $amount)
    {
        $recharge_id = $this->get_unique_recharge_id();
        $from = "bidder_".$bid_id;
        return $this->recharge_user($recharge_id, $from, $ref_id, $amount);
    }

    public function is_user_an_agent(): bool
    {
        return $this->get_cookie('user_type') == "agent";
    }
    public function is_user_an_admin(): bool
    {
        return $this->get_cookie('user_type') == "admin";
    }

    public function get_user_from_phone(mixed $phone)
    {
        $url = $this->amazon_api_end_point ."/login/phone/" . $phone;
        return json_decode($this->get_response_from_url($url));
    }
    public function recharge_user($recharge_id, $from_ref_id, $to_ref_id, $amount)
    {
        $url = $this->amazon_api_end_point."/recharge/".$recharge_id."/".$from_ref_id."/".$to_ref_id."/".$amount;
        return json_decode($this->get_response_from_url($url));
    }

    public function activate_user(string $phone, string $otp, string $ref_id)
    {
        $url = $this->amazon_api_end_point."/activate_user/".$ref_id."/".$phone."/".$otp;
        return json_decode($this->get_response_from_url($url));
    }

    public function get_all_bids(string $series_id, string $match_id) {
        $url = $this->amazon_api_end_point . "/get_match_bids/" . $series_id . "/" . $match_id;
        return json_decode($this->get_response_from_url($url));
    }

    public function get_bid_from_userid($all_bids, $ref_user_id):array{
        $temp=array();
        foreach($all_bids as $bids){
            if($bids->ref_id==$ref_user_id){
                $temp[]=$bids;
            }
        }
        return $temp;
    }

    public function get_match_name_match_id($all_matches, $match_id, $series_id):string
    {
        foreach ($all_matches as $match){
            if($match->match_id==$match_id && $match->series_id==$series_id){
                return $match->match_name;
            }
        }
        return "";
    }
    public function get_all_transactions($ref_user_id):array{
        $url = $this->amazon_api_end_point . "/get_user_recharges/" . $ref_user_id;
        $all_transactions= json_decode($this->get_response_from_url($url));
        $temp=array();
        foreach($all_transactions as $bids){
            if($bids->to_user_id==$ref_user_id){
                $temp[]=$bids;
            }
        }
        return $temp;
    }

    public function get_all_bids_by_user(string $ref_user_id) {
        $url = $this->amazon_api_end_point . "/get_user_bids/" . $ref_user_id;
        return json_decode($this->get_response_from_url($url));
    }

    public function delete_session(): void
    {
        session_unset();
        session_destroy();
    }

    public function logout(): void
    {
        setcookie((new Data())->get_auth_cookie_name(), "", time() - 36000, "/");
        setcookie('fname', "", time() - 36000, "/");
        setcookie('lname', "", time() - 36000, "/");
        setcookie('match_id', "", time() - 36000, "/");
        setcookie('series_id', "", time() - 36000, "/");
        setcookie('user_type', "", time() - 36000, "/");
        setcookie('match_name', "", time() - 36000, "/");
        setcookie('current_over_id', "", time() - 36000, "/");
        $this->delete_session();
    }
    public function withdraw_amount(string $ref_user_id, string $amount){
        $url = $this->amazon_api_end_point . "/withdraw/" . $ref_user_id . "/". $amount;
        return json_decode($this->get_response_from_url($url));
    }

    public function get_all_withdraws(string $ref_user_id):array {
        $url = $this->amazon_api_end_point . "/get_all_withdraws";
        $all_withdraw_by_user= json_decode($this->get_response_from_url($url));
        $temp=array();
        foreach($all_withdraw_by_user as $withdraw){
            if($withdraw->ref_id==$ref_user_id){
                $temp[]=$withdraw;
            }
        }
        return $temp;
    }
    public function is_valid_match(): bool
    {
        return (int)$this->get_cookie('current_over_id') > 100 &&
            (int)$this->get_cookie('current_over_id') < 300;
    }

    public function is_valid_bookie_response_session(mixed $bid_bookie_response): bool
    {
        return property_exists($bid_bookie_response,'predicted_runs_a') &&
            property_exists($bid_bookie_response, 'predicted_runs_b') &&
            property_exists($bid_bookie_response,'rate_1') &&
            property_exists($bid_bookie_response, 'rate_2') &&
            property_exists($bid_bookie_response, 'rate_3');
    }

    public function is_valid_bookie_response_winner(mixed $bid_bookie_response): bool
    {
        return property_exists($bid_bookie_response, 'rate_1') &&
            property_exists($bid_bookie_response,'rate_2') &&
            property_exists($bid_bookie_response,'team_a') &&
            property_exists($bid_bookie_response, 'team_b');
    }
}