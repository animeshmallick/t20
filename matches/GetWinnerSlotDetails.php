<html lang="en">
<head>
    <title>GetWinnerSlotDetails</title>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z91TWPR0DM"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-Z91TWPR0DM');
    </script>
</head>
</html>
<?php
header('Content-Type: application/json');
include "../Common.php";
include "../SlotScores.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$scores = new Scores($data);

$match_id = $_GET["match_id"];
$series_id = $_GET["series_id"];
$session = $common->get_cookie("session");
$amount = (float)$_GET['amount'];
$room = intval($_GET['room']);

$scorecard = $common->get_scorecard_latest($series_id, $match_id, "GetWinnerSlotDetails");

$all_bids = $common->get_all_bids_from_match($series_id, $match_id, 'winner', $room);
$rates = $common->get_winner_rates($all_bids, $amount);
function get_balls_remaining(mixed $scorecard): int
{
    $balls = $scorecard->over * 6;
    if(count($scorecard->this_over) == 0 || count($scorecard->this_over) == 6)
        return 120 - $balls;
    return 120 - $balls +  6 - get_valid_balls($scorecard->this_over);
}

function get_valid_balls($this_over): int
{
    $count = 0;
    for ($i=0;$i<count($this_over);$i++) {
        if (str_contains($this_over[$i], 'w') || str_contains($this_over[$i], 'nb'))
            continue;
        $count++;
    }
    return $count;
}
if ($scorecard->over_id < 218) {
    $output = array(
        "team_a" => $scorecard->teams[0],
        "team_b" => $scorecard->teams[1],
        "rate_1" => $rates[0],
        "rate_2" => $rates[1],
        "target" => $scorecard->teams[1] . " needs " . ($scorecard->team1_score->runs - $scorecard->team2_score->runs + 1) . " runs in "
            . get_balls_remaining($scorecard) . " balls",
        "innings" => $scorecard->innings
    );
}else{
    $output = array("error" => "Session Closed for Bidding");
}
echo json_encode($output);
?>