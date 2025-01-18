<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$session = $_GET['session'];
?>
<div class="sub-title">Place New Bid</div>
<form action="place_bid_to_db.php" method="post" name="bid_form" id="bid_form">
    <input type="text" name="bid_id" value="<?php echo $common->get_unique_bid_id('winner');?>" hidden="hidden">
    <input type="text" name="session" value="<?php echo $session;?>" hidden="hidden">
    <div class="title">For Match Winner</div>
    <div style="display: flex">
        <label class="amount_span" for="amount">Bid Amount:</label>
        <input type="number" id="amount" name="amount" value="100"
               onkeyup="update_winner_slot_details('<?php echo $session; ?>')" required />
    </div>
    <div class="plux_minus_container">
        <a style="width: 5rem" class="button" onclick="increase_amount(100);"> + ₹100 </a>
        <div style="width: 33%"></div>
        <a style="width: 5rem" class="button" onclick="decrease_amount(100);"> - ₹100 </a>
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
        <label class="container">
            <input type="radio" name="slot" value="T1" id="slot_a">
            <div class="slot">
                <div id="winner_a">Loading Slots</div>
                <div id="winner_a_amount">&nbsp;</div>
            </div>
        </label>
        <label class="container">
            <input type="radio" name="slot" value="T2" id="slot_b">
            <div class="slot">
                <div id="winner_b">Loading Slots</div>
                <div id="winner_b_amount">&nbsp;</div>
            </div>
        </label>
        <div class="small-gap"></div>
        <div class="separator"></div>
        <input type="submit" value="Place Bid" id="place_bid" onclick="place_bid_text()">
        <div class="small-gap"></div>
    </div>
    <div class="timer" id="timer_slots">&nbsp;</div>
</form>