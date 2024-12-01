<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($common->is_active_user($data->get_auth_cookie_name())) {
    if (!isset($_GET['series_id']) || !isset($_GET['match_id']) || !isset($_GET['match_name'])) {
            header("Location: ".$data->get_path());
    }
    else {
        $match_id = $_GET['match_id'];
        $series_id = $_GET['series_id'];
        $match_name = $_GET['match_name'];
        $common->set_cookie('match_id', $match_id);
        $common->set_cookie('match_name', $match_name);
        $common->set_cookie('series_id', $series_id);
        $scorecard = $common->get_scorecard_latest($series_id, $match_id);
        if($common->is_valid_match($scorecard)){
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
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
                <script src="../scripts.js"></script>
            </head>
            <body onload="fill_header();fill_scorecard();fill_controls();fill_footer();">
                <div id="header"></div>
                <i class="fa fa-refresh refresh-button" onclick="location.reload();"></i>
                <div id="scorecard"></div>
                <div class="separator"></div>
                <div class="play-container">
                    <div class="title"><span>Play your Bid on</span></div>
                    <div class="gap"></div>
                    <div class="bid_container">
                        <div class="title"><span>1st Innings</span></div>
                        <div style="display: flex">
                            <div class="bid_button_div"><a class="bid_button" href="place_bid.php?slot=a1">Over 1 to 6</a></div>
                            <div class="bid_button_div"><a class="bid_button" href="place_bid.php?slot=b1">Over 7 to 10</a></div>
                        </div>
                        <div style="display: flex">
                            <div class="bid_button_div"><a class="bid_button" href="place_bid.php?slot=c1">Over 11 to 16</a></div>
                            <div class="bid_button_div"><a class="bid_button" href="place_bid.php?slot=d1">Over 17 to 20</a></div>
                        </div>
                    </div>
                    <div class="gap"></div>
                    <div class="bid_container">
                        <div class="title"><span>2nd Innings</span></div>
                        <div style="display: flex">
                            <div class="bid_button_div"><a class="bid_button" href="place_bid.php?slot=a2">Over 1 to 6</a></div>
                            <div class="bid_button_div"><a class="bid_button" href="place_bid.php?slot=b2">Over 7 to 10</a></div>
                        </div>
                        <div style="display: flex">
                            <div class="bid_button_div"><a class="bid_button" href="place_bid.php?slot=c2">Over 11 to 16</a></div>
                            <div class="bid_button_div"><a class="bid_button" href="place_bid.php?slot=d2">Over 17 to 20</a></div>
                        </div>
                    </div>
                    <div class="gap"></div>
                </div>
                <div class="separator"></div>
                <div id="main_controls"></div>
                <div class="separator"></div>
                <div id="footer"></div>
            </body>
            </html>
<?php   } else {
            $common->delete_cookies();
            header("Location: ".$data->get_path()."/index.php");
        }
    }
} else {
    $common->delete_cookie($data->get_auth_cookie_name());
    header("Location: ".$data->get_path());
}?>