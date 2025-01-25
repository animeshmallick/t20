<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($common->is_user_logged_in() || (isset($_GET['auth']) && $_GET['auth'] == 'locust')) {
    $match_id = $_GET['match_id'];
    $series_id = $_GET['series_id'];
    $match_name = $_GET['match_name'];
    $common->delete_cookie('scorecard');
    $common->set_cookie('match_id', $match_id);
    $common->set_cookie('series_id', $series_id);
    $common->set_cookie('match_name', $match_name);
    $scorecard = $common->get_scorecard_latest($series_id, $match_id, "Match Homepage");
    $over_id =  (int)($scorecard->over_id);
    ?>
        <html lang="">
        <head>
            <title><?php echo $match_name?></title>
            <meta charset="utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
            <title>Match Page</title>
            <link rel="icon" type="image/x-icon" href="../cricket.ico">
            <script src="../scripts.js?version=<?php echo time(); ?>"></script>
        </head>
        <body onload="fill_header();fill_balance();
                fill_scorecard('<?php echo $series_id;?>', '<?php echo $match_id;?>');
                fill_footer();">
            <div id="header"></div>
            <i class="fa fa-refresh refresh-button" onclick="location.reload();"></i>
            <div id="scorecard">Loading Scorecard</div>
            <div class="separator"></div>
            <div class="play-container" style="margin: 0; padding: 0">
                <div class="sub-title"><span>Play your Bid on</span></div>
                <div class="bid_container">
                    <div class="title"><span>1st Innings</span></div>
                    <div style="display: flex">
                        <div class="bid_button_div"><a class="play bid_button <?php if(!$common->is_session_enabled($over_id,'a1')){echo "disabled";}?>" href="place_bid.php?session=a1&room=1">Over 1 to 6</a></div>
                        <div class="bid_button_div"><a class="play bid_button <?php if(!$common->is_session_enabled($over_id, 'b1')){echo "disabled";}?>" href="place_bid.php?session=b1&room=1">Over 7 to 10</a></div>
                    </div>
                    <div style="display: flex">
                        <div class="bid_button_div"><a class="play bid_button <?php if(!$common->is_session_enabled($over_id,'c1')){echo "disabled";}?>" href="place_bid.php?session=c1&room=1">Over 11 to 16</a></div>
                        <div class="bid_button_div"><a class="play bid_button <?php if(!$common->is_session_enabled($over_id,'d1')){echo "disabled";}?>" href="place_bid.php?session=d1&room=1">Over 17 to 20</a></div>
                    </div>
                </div>
                <div class="separator"></div>
                <div class="bid_container">
                    <div class="title"><span>2nd Innings</span></div>
                    <div style="display: flex">
                        <div class="bid_button_div"><a class="play bid_button <?php if(!$common->is_session_enabled($over_id, 'a2')){echo "disabled";}?>" href="place_bid.php?session=a2&room=1">Over 1 to 6</a></div>
                        <div class="bid_button_div"><a class="play bid_button <?php if(!$common->is_session_enabled($over_id, 'b2')){echo "disabled";}?>" href="place_bid.php?session=b2&room=1">Over 7 to 10</a></div>
                    </div>
                </div>
                <div class="separator"></div>
                <div class="bid_container">
                    <div class="title"><span>Match Winner</span></div>
                    <div class="bid_button_div" style="margin-left: 10%; width: 80%"><a class="play bid_button <?php if(!$scorecard->is_live || (int)$scorecard->over_id > 218){echo "disabled";}?>" href="place_bid.php?session=winner&room=1">Who will win the match?</a></div>
                </div>
                <div class="separator"></div>
                <div class="bid_container">
                    <div class="title"><span>Special Bids</span></div>
                    <div class="bid_button_div" style="margin-left: 10%; width: 80%"><a class="play bid_button <?php if(!$scorecard->is_live || (int)$scorecard->over_id > 218){echo "disabled";}?>" href="place_bid.php?session=special">Open Special Bids</a></div>
                </div>
                <div class="bid_button_div" style="margin-left: 25%"><a class="button" href="index.php">Go Back</a></div>
            </div>
            <div class="separator"></div>
            <div id="footer"></div>
        </body>
        </html>
<?php
} else {
    $common->logout();
    header("Location: ".$data->get_path());
}?>