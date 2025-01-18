<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if(isset($_GET['session'])){
    $session = $_GET['session'];
    ?>
    <div class="sub-title">Place New Bid</div>
    <form action="place_bid_to_db.php" method="post" name="bid_form" id="bid_form">
        <input type="text" name="bid_id" value="<?php echo $common->get_unique_bid_id('session'); ?>" hidden="hidden">
        <input type="text" name="session" value="<?php echo $session;?>" hidden="hidden">
        <div class="title">For Innings <?php echo $session[1];?>, End Of <?php echo ($data->get_maxballs_for_slot($session[0])/6)?>th Over</div>

        <div style="display: flex">
            <label class="amount_span" for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" value="100"
                   onkeyup="update_session_slot_details_actual('<?php echo $session;?>')" required />

        </div>
        <div class="plux_minus_container">
            <a style="padding: 0.2rem 0.5rem; margin: 0" class="button" onclick="increase_amount(100);"> + ₹100 </a>
            <a style="padding: 0.2rem 0.5rem; margin: 0" class="button" onclick="decrease_amount(100);"> - ₹100 </a>
        </div>
        <?php if ($common->is_user_an_agent()){ ?>
            <div class="separator"></div>
            <div class="bid_name">
                <label class="amount_span" for="bid_name">Bid Name :</label>
                <input type="text" id="bid_name" name="bid_name" placeholder="Bid Name/Notes"/>
            </div>
        <?php } ?>
        <div class="separator"></div>
        <div class="slot_container">
            <div class="title">Choose your Slot :</div>
            <div class="slot_container_inner">
                <label class="container">
                    <input type="radio" name="slot" value="x" id="slot_a">
                    <div class="slot">
                        <div id="slot_a_runs">Loading Slots</div>
                        <div id="slot_a_amount">&nbsp;</div>
                    </div>
                </label>
                <label class="container">
                    <input type="radio" name="slot" value="y" id="slot_b">
                    <div class="slot">
                        <div id="slot_b_runs">Loading Slots</div>
                        <div id="slot_b_amount">&nbsp;</div>
                    </div>
                </label>
                <label class="container">
                    <input type="radio" name="slot" value="z" id="slot_c">
                    <div class="slot">
                        <div id="slot_c_runs">Loading Slots</div>
                        <div id="slot_c_amount">&nbsp;</div>
                    </div>
                </label>
            </div>
            <div class="small-gap"></div>
            <div class="separator"></div>
            <input type="submit" value="Place Bid" id="place_bid" onclick="place_bid_text()">
            <div class="timer" id="timer_slots">&nbsp;</div>
        </div>
    </form>
<?php } else { ?>
<div class="title">Biding closed for this Slot</div>
<?php } ?>
