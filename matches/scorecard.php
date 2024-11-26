<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$common = new Common();
$data = new Data();
$match_id = $common->get_cookie("match_id");
$series_id = $common->get_cookie("series_id");
$match_name = $common->get_cookie("match_name");
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
        <div class="scorecard-container">
            <div class="team-detail">
                <div class="teams_details">
                    <div><span id="team1_name"></span></div>
                    <span id="team1_score"></span>
                </div>
                <div class="vs">
                    <span id="match_additional_details"></span>
                </div>
                <div class="teams_details">
                    <div><span id="team2_name"></span></div>
                    <span id="team2_score"></span>
                </div>
            </div>
            <div class="players_container">
                <div><span><u>Player Details</u></span></div>
                <div class="players">
                    <div class="batsmen">
                        <div><span id="batsman1"></span></div>
                        <span id="batsman2"></span>
                    </div>
                    <div class="batsmen">
                        <span id="bowler"></span>
                    </div>
                </div>
            </div>
            <div class="current-over">
                <span class="title" id="this_over_summary"></span>
                <div class="balls-container">
                    <?php
                    $balls = 0;
                    $valid_balls = 0;
                    foreach ($scorecard->this_over as $ball) {
                        $balls += 1;
                        $valid_balls += 1;
                        if (strpos($ball, 'w'))
                            $valid_balls -= 1;
                        ?>
                        <div class="balls">
                            <span id="ball_id_<?php echo $balls; ?>"></span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>