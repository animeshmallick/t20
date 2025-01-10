<?php
include "data.php";
include "Common.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$common->logout();
header("Location: ".$data->get_path());
