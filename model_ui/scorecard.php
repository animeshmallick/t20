<!DOCTYPE html>
<?php
include "../Common.php";
$common = new Common();
$match_id = $common->get_cookie("match_id");
$series_id = $common->get_cookie("series_id");
$match_name = $common->get_cookie("match_name");
$this_over_list = (json_decode($common->get_scorecard_latest($series_id, $match_id)))->this_over;
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
    <script src="../scripts.js"></script>
</head>
<body onload="fill_scorecard_data()">
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
                    foreach ($this_over_list as $ball) {
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