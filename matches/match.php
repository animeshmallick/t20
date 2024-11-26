<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$common = new Common();
$data = new Data();
$match_id = $_GET['match_id'];
$series_id = $_GET['series_id'];
$match_name = $_GET['match_name'];
$common->set_cookie('match_id', $match_id);
$common->set_cookie('match_name', $match_name);
$common->set_cookie('series_id', $series_id);
$scorecard = json_decode($common->get_scorecard_latest($series_id, $match_id));
$team1_score = $scorecard->team1_score->runs . "/" . $scorecard->team1_score->wickets . " (" . $scorecard->team1_score->overs . ")";
$team2_score = $scorecard->team2_score->runs . "/" . $scorecard->team2_score->wickets . " (" . $scorecard->team2_score->overs . ")";
$this_over_string = implode("&&", $scorecard->this_over);
?>
<html lang="">
<head>
    <title><?php echo $match_name?></title>
    <meta content="summary_large_image" name="twitter:card"/>
    <meta content="website" property="og:type"/>
    <meta content="" property="og:description"/>
    <meta content="https://x91avs1ipp.preview-beefreedesign.com/ZFlck" property="og:url"/>
    <meta content="https://pro-bee-beepro-thumbnail.getbee.io/messages/1299033/1285255/2292406/11945799_large.jpg" property="og:image"/>
    <meta content="" property="og:title"/>
    <meta content="" name="description"/>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="match_css.css?version=<?php echo time(); ?>">
    <script src="../scripts.js"></script>
</head>
<body onload="fill_scorecard(
        '<?php echo $scorecard->teams[0]; ?>',
        '<?php echo $team1_score; ?>',
        '<?php echo $scorecard->teams[1]; ?>',
        '<?php echo $team2_score; ?>',
        '<?php echo $scorecard->match_additional_details[0]; ?>',
        '<?php echo $scorecard->bowler; ?>',
        '<?php echo $scorecard->batsmen[0]; ?>',
        '<?php echo $scorecard->batsmen[1]; ?>',
        '<?php echo $this_over_string; ?>',
        '<?php echo $scorecard->this_over_summary; ?>')">
<div class="title-container">
    <span class="title">CricketT20</span>
</div>

<div id="scorecard"></div>

<div class="separator"></div>
<div class="play-container">
    <div><span class="title">Play you Bid on</span></div>
    <span>Innings 1</span>
    <div class="bid_container">
        <div class="bid"><a class="button bid bid-button" href="place_bid.php?slot=a1">Over 1 to 6</a></div>
        <div class="bid"><a class="button bid bid-button" href="place_bid.php?slot=b1">Over 7 to 10</a></div>
        <div class="bid"><a class="button bid bid-button" href="place_bid.php?slot=c1">Over 11 to 16</a></div>
        <div class="bid"><a class="button bid bid-button" href="place_bid.php?slot=d1">Over 17 to 20</a></div>
    </div>
    <span>Innings 2</span>
    <div class="bid_container">
        <div class="bid"><a class="button bid bid-button" href="place_bid.php?slot=a2">Over 1 to 6</a></div>
        <div class="bid"><a class="button bid bid-button" href="place_bid.php?slot=b2">Over 7 to 10</a></div>
        <div class="bid"><a class="button bid bid-button" href="place_bid.php?slot=c2">Over 11 to 16</a></div>
        <div class="bid"><a class="button bid bid-button" href="place_bid.php?slot=d2">Over 17 to 20</a></div>
    </div>
</div>
<div class="controls">
    <a class="button wide control" href="../index.php">Go Home</a>
    <a class="button wide control" href="show_all.php?match_id=<?php echo $match_id;?>">Show My Bids</a>
</div>
<div class="separator"></div>
</body>
</html>