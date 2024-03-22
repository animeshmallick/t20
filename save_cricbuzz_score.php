<?php
include 'data.php';
include "model/ball.php";
$match_id = $_GET['match_id'];
$innings = $_GET['innings'];
$ball_id = $_GET['ball_id'];
$runs = $_GET['runs'];
$wickets = $_GET['wickets'];

function is_new_ball($connection, $match_id, $innings, $ball_id) {
    $sql = "Select * from `scorecard` where `match_id`='$match_id' and `innings`=$innings and `ball_id`=$ball_id";
    return $connection->query($sql)->num_rows == 0;
}
function insert_new_ball($connection, $match_id, $innings, $ball_id, $runs, $wickets) {
    $insert_sql = "INSERT INTO `scorecard` (`match_id`, `innings`, `ball_id`, `runs`, `wickets`) VALUES ('$match_id', $innings, $ball_id, $runs, $wickets)";
    if ($connection->query($insert_sql) === TRUE) {
        echo "ScoreCard Updated";
    } else {
        echo "Failed to update scorecard in a valid ball.";
    }
}


$data = new Data();
if (is_new_ball($data->get_connection(), $match_id, $innings, $ball_id)) {
    insert_new_ball($data->get_connection(), $match_id, $innings, $ball_id, $runs, $wickets);
} else {
    echo "Ball already noted";
}