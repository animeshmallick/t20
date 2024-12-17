<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["amount"]) && isset($_POST["slot"])) {
    $ref_id = $common->get_cookie($data->get_auth_cookie_name());
    $match_id = $common->get_cookie("match_id");
    $series_id = $common->get_cookie("series_id");
    $match_name = $common->get_cookie("match_name");
    $innings = $common->get_cookie("innings");
    $session = $common->get_cookie("session");
    $slot = $_POST["slot"];

    $scorecard = $common->get_scorecard_latest($series_id, $match_id);
    if ($common->is_active_user($data->get_auth_cookie_name()) &&
        $common->is_valid_match($scorecard) &&
        in_array($innings, [1,2]) && in_array($session, ['a', 'b', 'c', 'd']) &&
        $common->is_eligible_for_bid($scorecard, $innings, $session)) {

        $amount = $_POST["amount"];
        $slot = $_POST["slot"];
        $bid_id = (int)$_POST["bid_id"];

        $bid_bookie_response = $common->get_bid_bookie_details($series_id, $match_id, $innings, $session, $amount);
        $rate = $slot == 'x' ? $bid_bookie_response->rate_1 :
                    ($slot == 'y' ? $bid_bookie_response->rate_2 :
                        ($slot == 'z' ? $bid_bookie_response->rate_3 : 0));
        $bid_runs_string = $slot == 'x' ? "Runs Less Than ".$bid_bookie_response->predicted_runs_a :
                                ($slot == 'y' ? "Runs between [".$bid_bookie_response->predicted_runs_a." to ".$bid_bookie_response->predicted_runs_b."]" :
                                    ($slot == 'z' ? "Runs More Than ".$bid_bookie_response->predicted_runs_b : 0));
        $run_min = $slot == 'x' ? 0 : ($slot == 'y' ? $bid_bookie_response->predicted_runs_a : ($slot == 'z' ? $bid_bookie_response->predicted_runs_b + 1 : 9999));
        $run_max = $slot == 'x' ? $bid_bookie_response->predicted_runs_a - 1 : ($slot == 'y' ? $bid_bookie_response->predicted_runs_b : ($slot == 'z' ? 9999 : -1))
        ?>
        <html lang="en">
        <head>
            <title>Confirm Bid Placed</title>
            <meta charset="utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
            <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
            <script src="../scripts.js"></script>
        </head>
        <body onload="fill_header();fill_scorecard();fill_controls();fill_footer();">
            <div id="header"></div>
            <div id="scorecard"></div>
            <div class="separator"></div>
        <?php
        if($common->is_new_bid_id($bid_id)) {
            if($common->get_user_balance($ref_id) >= $amount) {
                if ($common->insert_new_bid_to_db($bid_id, $ref_id, $series_id, $match_id, $innings, $session, $slot,
                    $run_min, $run_max, $rate, $amount, "placed")) {
                    /*Todo:
                        -> Currently the return type of update_balance is void.
                        -> If update_balance returns FALSE then delete the bid by marking as inactive.
                    */
                    $common->update_balance($bid_id, $ref_id, ((float)$amount) * -1.0);
                    ?>
                    <div class="bid_container">
                        <div class="bid-success-title">Bid Placed : Success</div>
                        <div class="gap"></div>
                        <div class="bid_details_success"><span>
                            <?php echo $bid_runs_string;?>
                        </span></div>
                        <div class="bid_details_success"><span>
                            Bid Placed For Amount ₹<?php echo $amount; ?>
                        </span></div>
                        <div class="bid_details_success"><span>
                            On Winning You will receive ₹<?php echo (int)($amount * $rate); ?>
                        </span></div>
                        <div class="small-separator"></div>
                        <a class="button" href="match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>&flag=1">Place Another Bid</a>
                    </div>
                    <?php
                } else { ?>
                    <div class="bid_container">
                        <div class="bid-failure-title">Bid Placed : Failure</div>
                        <div class="bid_details_failure"><span>
                            <?php echo $bid_runs_string;?>
                        </span></div>
                        <div class="bid_details_failure"><span>
                            Bid Amount ₹<?php echo $amount; ?>
                        </span></div>
                        <div class="small-gap"></div>
                        <div class="bid-failure-title">Bid Placed : Failure</div>
                        <div class="small-separator"></div>
                        <a class="button" href="match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>&flag=1">Place Another Bid</a>
                    </div>
                <?php }
            }else { ?>
                <div class="bid_container">
                    <div class="bid-failure-title">Bid Placed : Failure</div>
                    <div class="bid_details_failure"><span>
                        <?php echo $bid_runs_string;?>
                    </span></div>
                    <div class="bid_details_failure"><span>
                        Bid Amount ₹<?php echo $amount; ?>
                    </span></div>
                    <div class="small-gap"></div>
                    <div class="bid-failure-title">Not enough balance to place BID</div>
                    <div class="small-separator"></div>
                    <a class="button" href="match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>&flag=1">Place Another Bid</a>
                </div>
            <?php }
        } else { ?>
            <div class="bid_container">
                <div class="bid-failure-title">Bid Placed : Failure</div>
                <div class="bid_details_failure"><span>
                        <?php echo $bid_runs_string;?>
                    </span></div>
                <div class="bid_details_failure"><span>
                        Bid Amount ₹<?php echo $amount; ?>
                    </span></div>
                <div class="small-gap"></div>
                <div class="bid-failure-title">Duplicate Bid ID Found : Bid Not placed</div>
                <div class="small-separator"></div>
                <a class="button" href="match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>&flag=1">Place Another Bid</a>
            </div>
        <?php } ?>
        <div class="separator"></div>
        <div id="main_controls"></div>
        <div class="separator"></div>
        <div id="footer"></div>
        </body>
        </html>
        <?php
    }
    else {
        header("Location: ".$data->get_path());
    }
} else {
    header("Location: ".$data->get_path());
}
