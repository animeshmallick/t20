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
                    <div><span id="team1_name">&nbsp;</span></div>
                    <span id="team1_score">&nbsp;</span>
                </div>
                <div class="teams_details" style="margin-left: 0.05rem">
                    <div><span id="team2_name">&nbsp;</span></div>
                    <span id="team2_score">&nbsp;</span>
                </div>
            </div>
            <div class="vs">
                <span id="match_additional_details">&nbsp;</span>
            </div>
        </div>
        <div class="match-detail">
            <div class="sub-title">Player Details</div>
            <div class="players">
                <div class="batsmen">
                    <div style="padding-bottom: 0.1rem;text-align: left"><span id="batsman1">&nbsp;</span></div>
                    <div style="padding-bottom: 0.2rem;text-align: left"><span id="batsman2">&nbsp;</span></div>
                </div>
                <div class="batsmen">
                    <div style="padding-bottom: 0.2rem;text-align: right"><span id="bowler">&nbsp;</span></div>
                </div>
            </div>
        </div>
        <div class="match-detail">
            <div class="sub-title" id="current-over-id">&nbsp;</div>
            <div style="display: flex">
                <div class="current-over"><span id="this_over_summary">&nbsp;</span></div>
                <div class="get-previous-over">
                    <a class="show-more-over" id="get-previous-over"
                       onclick="add_new_over_data()">
                        Last Over
                    </a> </div>
            </div>
            <div class="over-container" id="over-container">
                <div style="display: flex" name="over_id" over_no="000"><div class="balls"><span>&nbsp;</span></div> </div>
            </div>
        </div>
        <div class="timer" id="timer">&nbsp;</div>
    </div>
</body>
</html>
