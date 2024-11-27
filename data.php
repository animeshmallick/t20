<?php
class Data {
    private string $auth_cookie_name;
    private string $path;

    function __construct(){
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            $this->path = "http://localhost/t20/";
        }else {
            $this->path = "https://www.crickett20.in/T20/";
        }
        $this->auth_cookie_name = "user_ref_id";
    }

    public function get_auth_cookie_name(): string
    { return $this->auth_cookie_name; }
    public function get_path(): string
    { return $this->path; }
}
?>