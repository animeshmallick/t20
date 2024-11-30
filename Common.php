<?php
class Common {
    private string $amazon_api_end_point;
    function __construct()
    {
        $this->amazon_api_end_point = "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com";
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
    public function get_scorecard_latest($series_id, $match_id): object
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

    public function delete_cookies(): void
    {
        setcookie('match_id', "", time() - (3600), "/");
        setcookie('series_id', "", time() - (3600), "/");
        setcookie('match_name', "", time() - (3600), "/");
        setcookie('innings', "", time() - (3600), "/");
        setcookie('slot', "", time() - (3600), "/");
    }

    public function get_user_from_db(mixed $phone, mixed $password): bool|string
    {
        $url = $this->amazon_api_end_point . "/login/".$phone."/".$password;
        return $this->get_response_from_url($url);
    }
    public function get_user_from_ref_id(string $ref_id): string
    {
        $url = $this->amazon_api_end_point . "/login/ref_id/".$ref_id;
        return $this->get_response_from_url($url);
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

    public function is_valid_match($scorecard): bool
    {
        return !isset($scorecard->error) && !str_contains($scorecard->source, 'amazon');
    }

    public function is_valid_slot(string $slot): bool
    {
        if (strlen($slot) != 2)
            return false;
        if ($slot[0] == 'a' || $slot[0] == 'b' || $slot[0] == 'c' || $slot[0] == 'd') {
            if ($slot[1] == 1 || $slot[1] == 2) {
                return true;
            }
        }
        return false;
    }

    public function is_valid_user(string $get_auth_cookie_name): bool
    {
        $ref_id = $this->get_cookie($get_auth_cookie_name);
        if ($ref_id == null || $ref_id == "")
            return false;
        $user = json_decode($this->get_user_from_ref_id($ref_id));
        if (isset($user->error))
            return false;
        return true;
    }

    public function delete_cookie(string $get_auth_cookie_name): void
    {
        setcookie($get_auth_cookie_name, "", time() - (3600), "/");
    }

    public function     is_eligible_for_bid($scorecard, $bid_innings, $slot): bool{
        $eligible_overID = 0;
        if($slot == 'a'){
            $eligible_overID = ($bid_innings * 100) + 6;}
        elseif($slot == 'b'){
            $eligible_overID = ($bid_innings * 100) + 10;}
        elseif($slot == 'c'){
            $eligible_overID = ($bid_innings * 100) + 16;}
        elseif ($slot == 'd'){
            $eligible_overID = ($bid_innings * 100) + 20;}
        if($scorecard->over_id <= $eligible_overID){
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
             "wallet_balance" => 0
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
    function get_all_bids_from_match(string $series_id, string $match_id): array
    {
        $url = $this->amazon_api_end_point . "/get_match_bids/" . $series_id . "/" . $match_id;
        return json_decode($this->get_response_from_url($url));
    }

    public function get_total_amount_from_bids(array $all_bids, $innings, $slot): float
    {
        $amount = 0.0;
        foreach ($all_bids as $bid) {
            if ($bid->innings == $innings && $bid->slot == $slot)
                $amount += $bid->amount;
        }
        return $amount;
    }

    public function get_bid_distributed_amount(array $all_bids, string $bid_innings,
                                               string $slot, int $predicted_runs, string $operator): float
    {
        $amount = 0.0;
        foreach ($all_bids as $bid){
            if ($bid->innings == $bid_innings && $bid->slot == $slot && $bid->operator == $operator) {
                if ($operator == 'less' && $bid->target_score < ($predicted_runs + 5))
                    $amount += ($bid->amount * $bid->rate);
                if ($operator == 'more' && $bid->target_score > ($predicted_runs - 5))
                    $amount += ($bid->amount * $bid->rate);
            }
        }
        return $amount;
    }
}