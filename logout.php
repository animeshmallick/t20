<?php
include "data.php";
$data = new Data();
setcookie((new Data())->get_auth_cookie_name(), "", time() - 36000, "/");
echo "Cookie deleted";
header("Location: ".$data->get_path());
