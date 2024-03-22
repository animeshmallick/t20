<?php
include "model/ball.php";
include "data.php";
$match_id ="2350b352-2d70-4abe-90cb-862edd5ef7ac";
$api_end_point = "https://api.cricapi.com/v1/cricScore?apikey=23591a2c-4eb9-4327-a13d-96311c2d4e25";
$match_not_started_status = 'Match not started';
$prev_ball = 0;
$match = get_match($api_end_point, $match_id);
if ($match == null)
    echo "Match not found. Match ID ".$match_id;
else if ($match->status == $match_not_started_status) {
    echo "Match found but not started";
}
else{
    $t1s = $match->t1s;
    $t2s = $match->t2s;
    $ball = get_new_ball($match_id, $t1s, $t2s);
    $data = new Data();
    if (is_ball_new($data->get_connection(), $match_id, $ball->get_innings(), $ball->get_ball_id(), $ball->get_runs(), $ball->get_wickets())){
        insert_new_ball($data->get_connection(), $ball->get_match_id(), $ball->get_innings(), $ball->get_ball_id(), $ball->get_runs(), $ball->get_wickets());
    } else {
        echo "Ball already noted";
    }
    echo "<br><br>";
    echo "T1 Score:".$t1s."<br>";
    echo "T2 Score:".$t2s."<br>";
    echo "match_id:".$ball->get_match_id().".<br>";
    echo "innings:".$ball->get_innings().".<br>";
    echo "ball:".$ball->get_ball_id().".<br>";
    echo "runs:".$ball->get_runs().".<br>";
    echo "wickets:".$ball->get_wickets().".<br>";
}
header("refresh: 3");

function insert_new_ball($connection, $match_id, $innings, $ball_id, $runs, $wickets) {
    $delete_sql = "DELETE FROM `scorecard` WHERE `match_id`='$match_id' and `innings`=$innings and `ball_id`=$ball_id";
    if ($connection->query($delete_sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $connection->error;
    }
    $insert_sql = "INSERT INTO `scorecard` (`match_id`, `innings`, `ball_id`, `runs`, `wickets`) VALUES ('$match_id', $innings, $ball_id, $runs, $wickets)";
    if ($connection->query($insert_sql) === TRUE) {
        echo "ScoreCard Updated";
    }
}

function is_ball_new($connection, $match_id, $innings, $ball_id, $runs, $wickets) {
    $sql = "Select * from `scorecard` where `match_id`='$match_id' and `innings`=$innings and `ball_id`=$ball_id and `runs`=$runs and `wickets`=$wickets";
    return $connection->query($sql)->num_rows == 0;
}

function get_match($api_end_point, $match_id){
    $json_result = file_get_contents($api_end_point);
    $j = json_decode($json_result);
    $matches = $j->data;
    foreach ($matches as $match){
        if ($match->id == $match_id)
            return $match;
    }
    return null;
}

function get_new_ball($match_id, $t1s, $t2s){
    if ($t1s == "")
        return new Ball($match_id, 1, 0, 0, 0);
    if ($t2s == ""){
        $t = $t1s;
        $innings = 1;
    } else {
        $t = $t2s;
        $innings = 2;
    }
    $tt = explode(" ",$t);
    $score = $tt[0];
    $over = $tt[1];
    $score_t = explode("/", $score);
    $runs = (int)$score_t[0];
    $wicket = (int)$score_t[1];
    $over_t = explode(".",substr($over,1,-1));
    if(count($over_t) == 1)
        $ball_id = ((int)$over_t[0])*6;
    else
        $ball_id = ((int)$over_t[0])*6 + (int)$over_t[1];
    return new Ball($match_id, $innings, $ball_id, $runs, $wicket);
}