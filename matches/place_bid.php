<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($common->is_active_user($data->get_auth_cookie_name())){
    if (isset($_GET['slot']) && strlen($_GET['slot']) == 2 &&
                $common->is_valid_slot($_GET['slot']) &&
                $common->is_all_cookies_available(['match_id', 'series_id', 'match_name'])) {
        $slot = $_GET['slot'];
        $common->set_cookie("innings", $slot[1]);
        $common->set_cookie("slot", $slot[0]);
        $series_id = $common->get_cookie("series_id");
        $match_id = $common->get_cookie("match_id");

        $scorecard = $common->get_scorecard_latest($series_id, $match_id);
        if ($common->is_valid_match($scorecard)) {
            $team1_score = $scorecard->team1_score->runs . "/" . $scorecard->team1_score->wickets . " (" . $scorecard->team1_score->overs . ")";
            $team2_score = $scorecard->team2_score->runs . "/" . $scorecard->team2_score->wickets . " (" . $scorecard->team2_score->overs . ")";
            $this_over_string = implode("&&", $scorecard->this_over);
            ?>

            <html lang="">
                <head>
                    <title>Place Your Bid</title>
                    <meta charset="utf-8"/>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
                    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                    <script src="../scripts.js"></script>
                </head>
                <body onload="fill_header();fill_scorecard();fill_controls();fill_footer();update_slot_details(100);">
                    <div id="header"></div>
                    <i class="fa fa-refresh refresh-button" onclick="location.reload();"></i>
                    <div id="scorecard"></div>
                    <div class="separator"></div>
                    <?php
                    if ($common->is_eligible_for_bid($scorecard, $slot[1], $slot[0])) {
                    ?>
                    <div class="bid_container">
                        <div class="sub-title">Place New Bid</div>
                        <form action="place_bid_to_db.php" method="post" name="bid_form">
                            <input type="text" name="bid_id" value="<?php echo $common->get_unique_bid_id();?>" hidden="hidden">
                            <div class="title">For Innings <?php echo $slot[1];?>, <br />End Of <?php echo ($data->get_maxballs_for_slot($slot[0])/6)?>th Over</div>
                            <div style="display: flex">
                                <label class="amount_span" for="amount">Bid Amount:</label>
                                <input type="number" id="amount" name="amount" value="100"
                                       onkeyup="update_slot_details(this.value)" required />

                            </div>
                            <div class="plux_minus_container">
                                <a style="width: 5rem" class="button" onclick="increase_amount(100);"> + ₹100 </a>
                                <div style="width: 33%"></div>
                                <a style="width: 5rem" class="button" onclick="decrease_amount(100);"> - ₹100 </a>
                            </div>
                            <div class="small-gap"></div>
                            <div class="slot_container">
                                <div class="title">Choose your Slot :</div>
                                <label class="container">
                                    <input type="radio" name="operator" value="less" id="less_input">
                                    <div class="slot">
                                        <div id="slot_a_runs">Slot1</div>
                                        <div class="small-gap"></div>
                                        <div id="slot_a_amount">Slot1</div>
                                    </div>
                                </label>
                                <div class="gap"></div>
                                <label class="container">
                                    <input type="radio" name="operator" value="more" id="more_input">
                                    <div class="slot">
                                        <div id="slot_b_runs">Slot2</div>
                                        <div class="small-gap"></div>
                                        <div id="slot_b_amount">Slot2</div>
                                    </div>
                                </label>
                                <div class="gap"></div>
                                <input type="submit" value="Place Bid">
                                <div class="small-gap"></div>
                            </div>
                        </form>
                    </div>
                    <?php } else { ?>
                        <div class="bid_container">
                            <div class="title">Biding closed for this Slot</div>
                        </div>
                    <?php }?>
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