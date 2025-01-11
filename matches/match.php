<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($common->is_user_logged_in()) {
    $match_id = $_GET['match_id'];
    $series_id = $_GET['series_id'];
    $match_name = $_GET['match_name'];
    $common->delete_cookie('scorecard');
    $common->set_cookie('match_id', $match_id);
    $common->set_cookie('series_id', $series_id);
    $common->set_cookie('match_name', $match_name);
    ?>
        <html lang="">
        <head>
            <title><?php echo $match_name?></title>
            <meta charset="utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
            <link rel="icon" type="image/x-icon" href="../cricket.ico">
            <script src="../scripts.js"></script>
        </head>
        <body onload="fill_header();fill_profile();fill_footer();
                fill_scorecard('<?php echo $series_id;?>', '<?php echo $match_id;?>');">
            <div id="header"></div>
            <i class="fa fa-refresh refresh-button" onclick="location.reload();"></i>
            <div id="scorecard">Loading Scorecard</div>
            <div class="separator"></div>
            <div id="profile"></div>
            <div class="separator"></div>
            <div class="play-container">
                <div class="title"><span>Play your Bid on</span></div>
                <div class="gap"></div>
                <div class="bid_container">
                    <div class="title"><span>1st Innings</span></div>
                    <div style="display: flex">
                        <div class="bid_button_div"><a class="bid_button <?php if(!$common->is_session_enabled('a1')){echo "disabled";}?>" href="place_bid.php?session=a1">Over 1 to 6</a></div>
                        <div class="bid_button_div"><a class="bid_button <?php if(!$common->is_session_enabled('b1')){echo "disabled";}?>" href="place_bid.php?session=b1">Over 7 to 10</a></div>
                    </div>
                    <div style="display: flex">
                        <div class="bid_button_div"><a class="bid_button <?php if(!$common->is_session_enabled('c1')){echo "disabled";}?>" href="place_bid.php?session=c1">Over 11 to 16</a></div>
                        <div class="bid_button_div"><a class="bid_button <?php if(!$common->is_session_enabled('d1')){echo "disabled";}?>" href="place_bid.php?session=d1">Over 17 to 20</a></div>
                    </div>
                </div>
                <div class="gap"></div>
                <div class="bid_container">
                    <div class="title"><span>2nd Innings</span></div>
                    <div style="display: flex">
                        <div class="bid_button_div"><a class="bid_button <?php if(!$common->is_session_enabled('a2')){echo "disabled";}?>" href="place_bid.php?session=a2">Over 1 to 6</a></div>
                        <div class="bid_button_div"><a class="bid_button <?php if(!$common->is_session_enabled('b2')){echo "disabled";}?>" href="place_bid.php?session=b2">Over 7 to 10</a></div>
                    </div>
                </div>
                <div class="gap"></div>
                <div class="bid_container">
                    <div class="title"><span>Match Winner</span></div>
                    <div class="bid_button_div" style="margin-left: 10%; width: 80%"><a class="bid_button <?php if(!$common->is_session_enabled('winner')){echo "disabled";}?>" href="place_bid.php?session=winner">Who will win the match?</a></div>
                </div>
                <div class="separator"></div>
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