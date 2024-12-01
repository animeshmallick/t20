<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["amount"]) && isset($_POST["operator"])) {
    $ref_id = $common->get_cookie($data->get_auth_cookie_name());
    $match_id = $common->get_cookie("match_id");
    $series_id = $common->get_cookie("series_id");
    $innings = $common->get_cookie("innings");
    $slot = $common->get_cookie("slot");

    $scorecard = $common->get_scorecard_latest($series_id, $match_id);
    if ($common->is_valid_user($data->get_auth_cookie_name()) &&
        $common->is_valid_match($scorecard) &&
        in_array($innings, [1,2]) && in_array($slot, ['a', 'b', 'c', 'd']) &&
        $common->is_eligible_for_bid($scorecard, $innings, $slot)) {

        $amount = $_POST["amount"];
        $operator = $_POST["operator"];
        $bid_id = (int)$_POST["bid_id"];

        $bid_bookie_response = $common->get_bid_bookie_details($series_id, $match_id, $innings, $slot, $amount);
        $rate = $operator == 'less' ? $bid_bookie_response->rate_1 :
            ($operator == 'more' ? $bid_bookie_response->rate_2 : 0);
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
            if ($common->insert_new_bid_to_db($bid_id, $ref_id, $series_id, $match_id, $innings, $slot,
                $bid_bookie_response->predicted_runs, $operator, $rate, $amount, "placed")) {
                ?>
                <div class="bid_container">
                    <div class="bid-success-title">Bid Placed : Success</div>
                    <div class="gap"></div>
                    <div class="bid_details_success"><span>
                        Runs <?php echo $operator; ?> than <?php echo $bid_bookie_response->predicted_runs; ?>
                    </span></div>
                    <div class="bid_details_success"><span>
                        Bid Placed For Amount ₹<?php echo $amount; ?>
                    </span></div>
                    <div class="bid_details_success"><span>
                        On Winning You will receive ₹<?php echo (int)($amount * $rate); ?>
                    </span></div>
                    <div class="small-gap"></div>
                </div>
                <?php
            } else { ?>
                <div class="bid_container">
                    <div class="bid-failure-title">Bid Placed : Failure</div>
                    <div class="bid_details_failure"><span>
                        Runs <?php echo $operator; ?> than <?php echo $bid_bookie_response->predicted_runs; ?>
                    </span></div>
                    <div class="bid_details_failure"><span>
                        Bid Placed For Amount ₹<?php echo $amount; ?>
                    </span></div>
                    <div class="small-gap"></div>
                    <div class="bid-failure-title">Bid Placed : Failure</div>
                </div>
            <?php }
        } else { ?>
            <div class="bid_container">
                <div class="bid-failure-title">Bid Placed : Failure</div>
                <div class="bid_details_failure"><span>
                        Runs <?php echo $operator; ?> than <?php echo $bid_bookie_response->predicted_runs; ?>
                    </span></div>
                <div class="bid_details_failure"><span>
                        Bid Placed For Amount ₹<?php echo $amount; ?>
                    </span></div>
                <div class="small-gap"></div>
                <div class="bid-failure-title">Duplicate Bid ID Found : Bid Not placed</div>
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
