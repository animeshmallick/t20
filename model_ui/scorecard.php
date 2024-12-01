<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$match_id = $common->get_cookie("match_id");
$series_id = $common->get_cookie("series_id");
$match_name = $common->get_cookie("match_name");
$scorecard = $common->get_scorecard_latest($series_id, $match_id);
$this_over_list = $scorecard->this_over;
$current_over_id = $scorecard->over_id;
?>
<html lang="">
    <head>
        <title><?php echo $match_name?></title>
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <script src="../scripts.js"></script>
    </head>
    <body onload="fill_scorecard_data()">
        <div class="scorecard-container">
            <div class="sub-title">Scorecard</div>
            <div class="gap"></div>
            <div class="match-detail">
                <div style="display: flex">
                    <div class="teams_details" style="margin-right: 0.05rem">
                        <div style="padding-bottom: 0.4rem"><span id="team1_name"></span></div>
                        <span id="team1_score"></span>
                    </div>
                    <div class="teams_details" style="margin-left: 0.05rem">
                        <div style="padding-bottom: 0.4rem"><span id="team2_name"></span></div>
                        <span id="team2_score"></span>
                    </div>
                </div>
                <div class="vs">
                    <span id="match_additional_details"></span>
                </div>
            </div>
            <div class="gap"></div>
            <div class="match-detail">
                <div class="sub-title">Player Details</div>
                <div class="players">
                    <div class="batsmen">
                        <div style="padding-bottom: 0.3rem;text-align: left"><span id="batsman1"></span></div>
                        <div style="padding-bottom: 0.2rem;text-align: left"><span id="batsman2"></span></div>
                    </div>
                    <div class="batsmen">
                        <div style="padding-bottom: 0.2rem;text-align: right"><span id="bowler"></span></div>
                    </div>
                </div>
            </div>
            <div class="gap"></div>
            <div class="match-detail">
                <div class="sub-title">Current Over : <?php echo $scorecard->over; ?></div>
                <div style="display: flex">
                    <div class="current-over"><span id="this_over_summary"></span></div>
                    <div class="get-previous-over">
                        <a class="show-more-over" id="get-previous-over"
                           onclick="add_new_over_data()">
                            Previous Over
                        </a> </div>
                </div>
                <div class="over-container" id="over-container">
                    <div class="balls-container" name="over_id" id="<?php echo $scorecard->over_id; ?>">
                        <?php
                        $balls = 0;
                        $valid_balls = 0;
                        $width = count($this_over_list) < 4 ? 33.333 :100 / count($this_over_list);
                        $padding = count($this_over_list) < 4 ? 25 : 0;
                        foreach ($this_over_list as $ball) {
                            $balls += 1;
                            $valid_balls += 1;
                            if (strpos($ball, 'w'))
                                $valid_balls -= 1;
                            ?>
                            <div class="balls" style="width: <?php echo $width ?>%;align-content: center">
                                <span id="ball_id_<?php echo $balls; ?>"></span>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="gap"></div>
                </div>
            </div>
        </div>
    </body>
</html>