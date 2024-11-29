<?php
class Common {
    private function get_response_from_url($url): bool|string
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
        $url = 'https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/get_all_matches'; // replace with your URL
        $response = $this->get_response_from_url($url);
        $response = substr(str_replace("},{","}&&{", $response), 1, -1);
        $matches = [];
        $match_id = 0;
        foreach (explode('&&', $response) as $match) {
            $matches[$match_id] = json_decode($match);
            $match_id = $match_id + 1;
        }
        return $matches;
    }
    public function get_scorecard_latest($series_id, $match_id): string
    {
        $url = 'https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/scores/' . $series_id . '/' . $match_id . '/latest';
        return $this->get_response_from_url($url);
    }
    public function validate_unique_ref_id($ref_id): string
    {
        $url = 'https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/validate_ref_id/' . $ref_id;
        return json_decode($this->get_response_from_url($url))->result == true;
    }

    public function get_cookie(string $name): string
    {
        if (isset($_COOKIE[$name]))
            return $_COOKIE[$name];
        return "";
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
        $url = "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/login/".$phone."/".$password;
        return $this->get_response_from_url($url);
    }
    public function get_user_from_ref_id(string $ref_id): string
    {
        $url = "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/login/ref_id".$ref_id;
        return $this->get_response_from_url($url);
    }
    public function is_new_phone_number(string $phone): bool
    {
        $url = "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/login/ref_id/".$phone;
        return isset(json_decode($this->get_response_from_url($url))->error);
    }

    public function set_cookie(string $cookie_name, string $cookie_value): void
    {
        setcookie($cookie_name, $cookie_value, time() + (3600), "/");
    }

    public function is_valid_match($scorecard): bool
    {
        return !str_contains($scorecard->source, 'amazon');
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

    public function is_eligible_for_bid($scorecard, $bid_innings, $slot): bool{
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
        $url = 'https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/save_new_user';
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
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $response = curl_exec($ch);
        echo $response;
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            return false;
        }
        curl_close($ch);
        return true;
    }
}