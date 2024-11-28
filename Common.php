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

    public function set_cookie(string $cookie_name, string $cookie_value): void
    {
        setcookie($cookie_name, $cookie_value, time() + (3600), "/");
    }
}