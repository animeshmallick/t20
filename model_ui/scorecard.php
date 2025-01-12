<!DOCTYPE html>
<html lang="">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <script src="../scripts.js?version=<?php echo time(); ?>"></script>
</head>
<body>
    <div class="scorecard-container">
        <div class="sub-title">Scorecard</div>
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
        <div class="match-detail">
            <div class="sub-title" id="current-over-id"></div>
            <div style="display: flex">
                <div class="current-over"><span id="this_over_summary"></span></div>
                <div class="get-previous-over">
                    <a class="show-more-over" id="get-previous-over"
                       onclick="add_new_over_data()">
                        Last Over
                    </a> </div>
            </div>
            <div class="over-container" id="over-container">
            </div>
        </div>
    </div>
</body>
</html>
