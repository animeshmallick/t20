<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$slot = $_POST["slot"];
$session = $_POST["session"];
$amount = floatval($_POST["amount"]);
$bid_id = (int)$_POST["bid_id"];
$bid_name = $_POST["bid_name"] ?? "";

$series_id = $common->get_cookie("series_id");
$match_id = $common->get_cookie("match_id");
$match_name = $common->get_cookie("match_name");
$scorecard = $common->get_scorecard_latest($series_id, $match_id, "Place Bid To DB");

if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["amount"]) && isset($_POST["slot"]) && isset($_POST["session"])) { ?>
    <html lang="en">
    <head>
        <title>Bid Placed Confirmation</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <title>Home</title>
        <link rel="icon" type="image/x-icon" href="../cricket.ico">
        <script src="../scripts.js?version=<?php echo time(); ?>"></script>
    </head>
    <body onload="fill_header();fill_balance();
        fill_scorecard('<?php echo $series_id;?>','<?php echo $match_id;?>');
        fill_footer();">
        <div id="header"></div>
    <?php
        if ($common->is_user_logged_in() &&
        $common->is_eligible_for_session_bid($session, $scorecard->over_id)) {

            $bid_bookie_response = $common->get_bid_bookie_details($series_id, $match_id, $session, $amount);
            $rate = $slot == 'x' ? $bid_bookie_response->rate_1 :
                        ($slot == 'y' ? $bid_bookie_response->rate_2 :
                            ($slot == 'z' ? $bid_bookie_response->rate_3 : 0));
            $bid_runs_string = $slot == 'x' ? "Runs 0 to ".$bid_bookie_response->predicted_runs_a :
                                    ($slot == 'y' ? "Runs [".$bid_bookie_response->predicted_runs_a." to ".$bid_bookie_response->predicted_runs_b."]" :
                                        ($slot == 'z' ? "Runs ".$bid_bookie_response->predicted_runs_b." or more" : 0));
            $run_min = $slot == 'x' ? 0 : ($slot == 'y' ? $bid_bookie_response->predicted_runs_a : ($slot == 'z' ? $bid_bookie_response->predicted_runs_b + 1 : 490));
            $run_max = $slot == 'x' ? $bid_bookie_response->predicted_runs_a - 1 : ($slot == 'y' ? $bid_bookie_response->predicted_runs_b : ($slot == 'z' ? 490 : -1));

            $ref_id = $common->get_cookie($data->get_auth_cookie_name());
            if ($common->is_valid_bookie_response_session($bid_bookie_response)){
                $bid_place_response = $common->insert_new_session_bid_to_db($bid_id, $ref_id, $series_id, $match_id, $session,
                    $slot, $run_min, $run_max, $rate, $amount, $bid_name);
                $bid_place_response = json_decode($bid_place_response);
                if($bid_place_response->recharge_status){
                    $status = true;
                    $status_msg_1 = $bid_runs_string;
                    $status_msg_2 = "PUT &#8377;".$amount." Take &#8377;".floor(($amount * $rate));
                    $status_msg_3 = "Agent's Refund &#8377;".floor((int)$amount/10);
                    if ($common->is_user_an_agent()) {
                        $common->recharge_user($common->get_unique_recharge_id(),
                            "bidder_refund_agent_".$bid_id, $ref_id, floor($amount / 10));
                    }
                } else {
                    $status = false;
                    $status_msg_1 = $bid_place_response->recharge_msg;;
                    $status_msg_2 = "Bid Amount &#8377;".$amount;
                    $status_msg_3 = " -- ";
                }
            } else {
                $status = false;
                $status_msg_1 = "Error Response from Local Server";
                $status_msg_2 = "Bid Amount &#8377;".$amount;
                $status_msg_3 = " -- ";
            }
        } elseif ($common->is_user_logged_in() &&
        $common->is_eligible_for_winner_bid($session)) {
            $bid_bookie_response = $common->get_match_winner_bid_bookie_details($series_id, $match_id, $amount);
            $rate = $slot == 'T1' ? $bid_bookie_response->rate_1 : ($slot == 'T2' ? $bid_bookie_response->rate_2 : 0);
            $bid_runs_string = $slot == 'T1' ? $scorecard->teams[0]." Wins The Match " :
                ($slot == 'T2' ? $scorecard->teams[1]." Wins The Match " : 0);
            if($common->is_valid_bookie_response_winner($bid_bookie_response)) {
                $ref_id = $common->get_cookie($data->get_auth_cookie_name());
                $refund = 0;
                $bid_place_response = $common->insert_new_winner_bid_to_db($bid_id, $ref_id, $series_id, $match_id, $slot,
                    $rate, $amount, $bid_name);
                $bid_place_response = json_decode($bid_place_response);
                if ($bid_place_response->recharge_status) {
                    $status = true;
                    $status_msg_1 = $bid_runs_string;
                    $status_msg_2 = "PUT &#8377;".$amount." & Take &#8377;".floor((int)($amount * $rate));
                    $status_msg_3 = "You got refund of &#8377;".floor((int)$amount/10);
                    if ($common->is_user_an_agent()) {
                        $common->recharge_user($common->get_unique_recharge_id(),
                            "bidder_refund_agent_".$bid_id, $ref_id, $amount);
                    }
                } else {
                    $status = false;
                    $status_msg_1 = $bid_place_response->recharge_msg;;
                    $status_msg_2 = "Bid Amount &#8377;".$amount;
                    $status_msg_3 = " -- ";
                }
            } else {
                $status = false;
                $status_msg_1 = "Error Response from Local Server";
                $status_msg_2 = "Bid Amount &#8377;".$amount;
                $status_msg_3 = " -- ";
            }
        } else {
            header("Location: ".$data->get_path());
            $status = false;
            $status_msg_1 = "";
            $status_msg_2 = "";
            $status_msg_3 = "";
        }
        if ($status){
        ?>
            <div class="bid_container">
                <div class="bid-success-title"><p class="confirm">&#9989; Placed</p></div>
                <div class="bid_details_success"><span><?php echo $status_msg_1;?></span></div>
                <div class="bid_details_success"><span><?php echo $status_msg_2;?></span></div>
                <?php if ($common->is_user_an_agent()){?>
                    <div class="bid_details_success"><span><?php echo $status_msg_3;?></span></div>
                <?php }
        } else { ?>
            <div class="bid_container">
                <div class="bid-failure-title"><p class="confirm">&#10060; Failed</p></div>
                <div class="bid_details_failure"><span><?php echo $status_msg_1;?></span></div>
                <div class="bid_details_failure"><span><?php echo $status_msg_2;?></span></div>
                <?php if ($common->is_user_an_agent()){?>
                    <div class="bid_details_failure"><span><?php echo $status_msg_3;?></span></div>
            <?php   }
            } ?>
                <div class="small-separator"></div>
                <a class="button" style="margin-left: 12.5%; width: 75%" href="place_bid.php?session=<?php echo $session; ?>">New Bid</a>
                <div class="separator"></div>
                <a class="button secondary" style="margin-left: 12.5%; width: 75%" href="../views/show_all_bids.php">All Bids</a>
            </div>
        <div class="separator"></div>
        <div id="scorecard"></div>
        <div class="separator"></div>
        <div id="footer"></div>
    </body>
    </html>
<?php } else
{
    header("Location: ".$data->get_path());
}