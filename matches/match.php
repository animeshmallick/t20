<?php
include "../Common.php";
include "../data.php";
$common = new Common();
$data = new Data();
$match_id = $_GET['match_id'];
$series_id = $_GET['series_id'];
$match_name = $_GET['match_name'];
setcookie('match_id', "", time() - (3600), "/");
setcookie('innings', "", time() - (3600), "/");
setcookie('overs', "", time() - (3600), "/");
$scorecard = json_decode($common->get_scorecard_latest($series_id, $match_id));
$team1_score = $scorecard->team1_score->runs . "/" . $scorecard->team1_score->wickets . " (" . $scorecard->team1_score->overs . ")";
$team2_score = $scorecard->team2_score->runs . "/" . $scorecard->team2_score->wickets . " (" . $scorecard->team2_score->overs . ")";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style.css?version=<?php echo time(); ?>">
    <title>Match</title>
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <script src="../scripts.js"></script>
</head>

<body onload="get_scorecard_summary('<?php echo $scorecard->teams[0]; ?>', '<?php echo $team1_score?>', '<?php echo $scorecard->teams[1]; ?>', '<?php echo $team2_score; ?>')">
<div class="header"><h1>IPL - 2024</h1></div>
<div class="sub-header"><h1><?php echo $match_name;?></h1></div>
<div class="scorecard" id="scorecard_summary">
    <div class="team1">
        <div id="team1_name"></div>
        <div id="team1_score"></div>
    </div>
    <div class="vs"><?php echo $scorecard->match_details; ?></div>
    <div class="team2">
        <div id="team2_name"></div>
        <div id="team2_score"></div>
    </div>
</div>
<div class="sub-header"><h2>Available Balance : <?php echo $common->get_wallet_balance($data->get_connection(), $common->get_auth_cookie($data->get_auth_cookie_name())); ?></h2></div>
<a class="button wide login" href="../index.php">Go Home</a>
<a class="button wide login" href="show_all.php?match_id=<?php echo $match_id;?>">Show My Bids</a>
<hr>
<div class="footer">
    <p>Created By: US.</p>
    <p>Contact Us On : </p>
</div>
</body>
</html>
