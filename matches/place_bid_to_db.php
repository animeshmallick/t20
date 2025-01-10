<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$slot = $_POST["slot"];
$session = $_POST["session"];
$amount = $_POST["amount"];
$bid_id = (int)$_POST["bid_id"];

$series_id = $common->get_cookie("series_id");
$match_id = $common->get_cookie("match_id");
$match_name = $common->get_cookie("match_name");
$scorecard = $common->get_scorecard_latest($series_id, $match_id);

if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["amount"]) && isset($_POST["slot"]) && isset($_POST["session"])) { ?>
    <html lang="en">
    <head>
        <title>Confirm Bid Placed</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <script src="../scripts.js"></script>
    </head>
    <body onload="fill_header();fill_profile();
        fill_scorecard('<?php echo $series_id;?>','<?php echo $match_id;?>');
        fill_controls();fill_footer();">
        <div id="header"></div>
        <div id="scorecard"></div>
        <div class="separator"></div>
        <div id="profile"></div>
        <div class="separator"></div>
    <?php
        if ($common->is_user_logged_in() &&
        $common->is_eligible_for_session_bid($session)) {

            $bid_bookie_response = $common->get_bid_bookie_details($series_id, $match_id, $session, $amount);
            $rate = $slot == 'x' ? $bid_bookie_response->rate_1 :
                        ($slot == 'y' ? $bid_bookie_response->rate_2 :
                            ($slot == 'z' ? $bid_bookie_response->rate_3 : 0));
            $bid_runs_string = $slot == 'x' ? "Runs Less Than ".$bid_bookie_response->predicted_runs_a :
                                    ($slot == 'y' ? "Runs between [".$bid_bookie_response->predicted_runs_a." to ".$bid_bookie_response->predicted_runs_b."]" :
                                        ($slot == 'z' ? "Runs More Than ".$bid_bookie_response->predicted_runs_b : 0));
            $run_min = $slot == 'x' ? 0 : ($slot == 'y' ? $bid_bookie_response->predicted_runs_a : ($slot == 'z' ? $bid_bookie_response->predicted_runs_b + 1 : 9999));
            $run_max = $slot == 'x' ? $bid_bookie_response->predicted_runs_a - 1 : ($slot == 'y' ? $bid_bookie_response->predicted_runs_b : ($slot == 'z' ? 9999 : -1));

            $ref_id = $common->get_cookie($data->get_auth_cookie_name());
            if ($common->is_valid_bookie_response_session($bid_bookie_response)){
                $bid_place_response = $common->insert_new_session_bid_to_db($bid_id, $ref_id, $series_id, $match_id, $session,
                                                        $slot, $run_min, $run_max, $rate, $amount);
                $bid_place_response = json_decode($bid_place_response);

                if($bid_place_response->recharge_status){ ?>
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
                        <a class="button" href="match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>">Place Another Bid</a>
                    </div>
                <?php }
                else { ?>
                <div class="bid_container">
                    <div class="bid-failure-title">Bid Placed : Failure</div>
                    <div class="bid_details_failure"><span>
                        <?php echo $bid_place_response->recharge_msg; ?>
                    </span></div>
                    <div class="bid_details_failure"><span>
                        Bid Amount ₹<?php echo $amount; ?>
                    </span></div>
                    <div class="small-gap"></div>
                    <div class="bid-failure-title">Bid Placed : Failure</div>
                    <div class="small-separator"></div>
                    <a class="button" href="match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>">Place Another Bid</a>
                </div>
    <?php  }
            } else { ?>
                <div class="bid_container">
                    <div class="bid-failure-title">Bid Placed : Failure</div>
                    <div class="bid_details_failure"><span>Error Response from Local Server</span></div>
                    <div class="bid_details_failure"><span>
                        Bid Amount ₹<?php echo $amount; ?>
                    </span></div>
                    <div class="small-gap"></div>
                    <div class="small-separator"></div>
                    <a class="button" href="match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>">Place Another Bid</a>
                </div>
    <?php   }
        } elseif ($common->is_user_logged_in() &&
        $common->is_eligible_for_winner_bid($session)) {

            $bid_bookie_response = $common->get_match_winner_bid_bookie_details($series_id, $match_id, $amount);
            $rate = $slot == 'T1' ? $bid_bookie_response->rate_1 : ($slot == 'T2' ? $bid_bookie_response->rate_2 : 0);
            $bid_runs_string = $slot == 'T1' ? $scorecard->teams[0]." Wins The Match " :
                ($slot == 'T2' ? $scorecard->teams[1]." Wins The Match " : 0);
            if($common->is_valid_bookie_response_winner($bid_bookie_response)) {
                $ref_id = $common->get_cookie($data->get_auth_cookie_name());
                $bid_place_response = $common->insert_new_winner_bid_to_db($bid_id, $ref_id, $series_id, $match_id, $slot,
                                                        $rate, $amount);
                $bid_place_response = json_decode($bid_place_response);
                if ($bid_place_response->recharge_status) {
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
                        <a class="button" href="match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>">Place Another Bid</a>
                    </div>

                <?php }
                else { ?>
                <div class="bid_container">
                    <div class="bid-failure-title">Bid Placed : Failure</div>
                    <div class="bid_details_failure"><span>
                    <?php echo $bid_place_response->recharge_msg;;?>
                </span></div>
                    <div class="bid_details_failure"><span>
                    Bid Amount ₹<?php echo $amount; ?>
                </span></div>
                    <div class="small-gap"></div>
                    <div class="bid-failure-title">Bid Placed : Failure</div>
                    <div class="small-separator"></div>
                    <a class="button" href="match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>">Place Another Bid</a>
                </div>
                    <?php }
            } else { ?>
                <div class="bid_container">
                    <div class="bid-failure-title">Bid Placed : Failure</div>
                    <div class="bid_details_failure"><span>Error Response from Local Server</span></div>
                    <div class="bid_details_failure"><span>
                        Bid Amount ₹<?php echo $amount; ?>
                    </span></div>
                    <div class="small-gap"></div>
                    <div class="small-separator"></div>
                    <a class="button" href="match.php?match_id=<?php echo $match_id ?>&series_id=<?php echo $series_id ?>&match_name=<?php echo $match_name; ?>">Place Another Bid</a>
                </div>
            <?php }
        } else {
            header("Location: ".$data->get_path());
        } ?>
        <div id="main_controls"></div>
        <div id="footer"></div>
    </body>
    </html>
<?php } else
{
    header("Location: ".$data->get_path());
}