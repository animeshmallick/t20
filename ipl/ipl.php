<?php
include "../data.php";

$data = new Data();
$ref_id = $_GET['ref_id'];
echo get_wallet($data->get_connection(), $ref_id);

function get_wallet($connection, $ref_id) {
    $sql = "Select * from `users` where `ref_id`=$ref_id";
    $result = $connection->query($sql);
    if ($result->num_rows == 1) {
        return $result->fetch_assoc()['wallet'];
    } else {
        return -1;
    }
}
