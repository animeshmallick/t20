<?php
include "../Common.php";
include "../data.php";
$common = new Common();
$data = new Data();
if ($common->is_valid_user($data->get_auth_cookie_name())){
    if (isset($_GET['slot']) && strlen($_GET['slot']) == 2 &&
                $common->is_valid_slot($_GET['slot']) &&
                $common->is_all_cookies_available(['match_id', 'series_id', 'match_name'])) {
        $slot = $_GET['slot'];
        $common->set_cookie("innings", $slot[1]);
        $common->set_cookie("slot", $slot[0]);
        $series_id = $common->get_cookie("series_id");
        $match_id = $common->get_cookie("match_id");

        $scorecard = json_decode($common->get_scorecard_latest($series_id, $match_id));
        if ($common->is_valid_match($scorecard)) {
            $team1_score = $scorecard->team1_score->runs . "/" . $scorecard->team1_score->wickets . " (" . $scorecard->team1_score->overs . ")";
            $team2_score = $scorecard->team2_score->runs . "/" . $scorecard->team2_score->wickets . " (" . $scorecard->team2_score->overs . ")";
            $this_over_string = implode("&&", $scorecard->this_over);
            ?>

            <html lang="">
            <head>
                <title></title>
            <meta content="summary_large_image" name="twitter:card"/>
            <meta content="website" property="og:type"/>
            <meta content="" property="og:description"/>
            <meta content="https://x91avs1ipp.preview-beefreedesign.com/ZFlck" property="og:url"/>
            <meta content="https://pro-bee-beepro-thumbnail.getbee.io/messages/1299033/1285255/2292406/11945799_large.jpg" property="og:image"/>
            <meta content="" property="og:title"/>
            <meta content="" name="description"/>
            <meta charset="utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
            <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
            <script src="../scripts.js"></script>
            </head>
            <body onload="fill_header();fill_scorecard();fill_controls();fill_footer();">
            <div id="header"></div>

            <div id="scorecard"></div>
            <div class="separator"></div>
            <div class="bid_container">
                <form action="place_bid_to_db.php" method="post" name="bid_form">
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" value="100" onkeyup="update_slot_details(this.value)" required>
                    <label for="slot">Choose your Slot :</label>
                    <label for="slot_a" name="slot"><input type="radio" id="slot_a" name="slot" value="a" checked>
                        <span id="slot_a_span"></span>
                    </label>
                    <label for="slot_b" name="slot"><input type="radio" id="slot_b" name="slot" value="b">
                        <span id="slot_b_span"></span>
                    </label>
                </form>
            </div>
            <div class="separator"></div>
            <div id="main_controls"></div>
            <div class="separator"></div>
            <div id="footer"></div>
            </body>
            </html>
        <?php
        } else {
            $common->delete_cookies();
            header("Location: ".$data->get_path()."/index.php");
        }
    }else {header("Location: index.php");}

} else {header("Location: ../index.php");}
?>