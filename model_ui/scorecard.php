<!DOCTYPE html>
<html lang="">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <script src="../scripts.js?version=<?php echo time(); ?>"></script>
</head>
<body>
    <div class="scorecard-container" id='scorecard-container'>
        <div style="display: flex">
            <div class="sub-title" style="width: 80%">Scorecard</div>
            <a id='detailed-scorecard' class="detailed_scorecard_button" href="../model_ui/detailed_scorecard.php">Details</a>
        </div>
        <div class="match-score-main" id="current-over-id">&nbsp;</div>
        <div class="match-detail">
            <div style="display: flex">
                <div class="teams_details" id='team1_details' style="display: flex; justify-content: space-between;">
                    <div>
                        <img id="team1_logo" src="../images/field.png"  alt="T1.Img" height="50px" width="50px" style="object-fit: cover; border-radius: 0.5rem;">
                    </div>
                    <div style="background: rgba(245,255,250,0.5)">
                        <div><span id="team1_name" style="padding-right: 1rem">&nbsp;</span></div>
                        <div><span id="team1_score" style="padding-right: 1rem">&nbsp;</span></div>
                    </div>
                </div>
                <div class="teams_details" id='team2_details' style="display: flex; justify-content: space-between;">
                    <div style="background: rgba(245,255,250,0.5)">
                        <div style="padding-left: 0.1rem"><span id="team2_name">&nbsp;</span></div>
                        <div><span id="team2_score" style="padding-left: 1rem">&nbsp;</span></div>
                    </div>
                    <div>
                        <img id="team2_logo" src="../images/field.png"  alt="T2.Img" height="50px" width="50px" style="object-fit: cover; border-radius: 0.5rem;">
                    </div>
                </div>
            </div>
        </div>
        <div class="vs">
            <span id="match_additional_details">&nbsp;</span>
        </div>
        <div class="small-gap"></div>
        <div class="match-detail" style="display: flex;">
            <div class="batsmen" style="text-align: left">
                <span id="crr">CRR: 8.47</span>
            </div>
            <div class="batsmen" style="text-align: right">
                <span id="rrr">RRR: 9.10</span>
            </div>
        </div>
        <div class="small-gap"></div>
        <div class="match-detail">
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
        <div class="small-gap"></div>
        <div class="match-detail"">
            <div class="batsmen" style="width: 100%">
                <span id="partnership">Partnership: 0 (0)</span>
            </div>
            <div class="batsmen" style="width: 100%">
                <span id="last_batsman">Last Batsman: Rohit Sharma 0(0) @ 77/4</span>
            </div>
        </div>
        <div class="small-gap"></div>
        <div class="match-detail" style="background: mistyrose">
            <div style="display: flex">
                <div class="current-over"><span id="this_over_summary">&nbsp;</span></div>
                <div class="get-previous-over">
                    <a class="show-more-over" id="get-previous-over"
                       onclick="add_new_over_data()">
                        Last Over
                    </a> </div>
            </div>
            <div class="current-over-container" id="current-over-container">
            </div>
            <div class="previous-over-container" id="previous-over-container">

            </div>
        </div>
        <div class="timer" id="timer" style="display: none">&nbsp;</div>
    </div>
</body>
</html>
