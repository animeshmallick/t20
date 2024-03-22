<?php

class User {
    private $fname;
    private $lname;
    private $phone;
    private $password;

    function __construct($fname, $lname, $phone, $password){
        $this->fname = $fname;
        $this->lname = $lname;
        $this->phone = $phone;
        $this->password = $password;
    }

    //Getter
    public function get_fname() {
        return $this->fname;
    }
    public function get_lname() {
        return $this->lname;
    }
    public function get_phone() {
        return $this->phone;
    }
    public function get_password() {
        return $this->password;
    }
}
