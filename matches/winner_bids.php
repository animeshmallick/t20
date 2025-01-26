<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$session = $_GET['session'];
$room = intval($_GET['room']);
?>
<div class="sub-title">Place New Bid</div>
<form action="place_bid_to_db.php" method="post" name="bid_form" id="bid_form">
    <input type="text" name="bid_id" value="<?php echo $common->get_unique_bid_id('winner');?>" hidden="hidden">
    <input type="text" name="session" value="<?php echo $session;?>" hidden="hidden">
    <input type="text" name="room" value="<?php echo $room;?>" hidden="hidden">
    <div class="title">For Match Winner</div>
    <label class="amount_span" id="amount-display" for="amount">Amount of &#8377;<?php echo $room === 1 ? 100 : ($room == 2 ? 700 : ($room === 3 ? 1500 : 0)); ?> </label>
    <div class="plux_minus_container">
        <a class="button plus-minus" onclick="decrease_amount('<?php echo $session;?>','<?php echo $room;?>','<?php echo 100;?>');"> <i class="fas fa-minus"></i> &#8377;100 </a>
        <input type="range" id="amount" name="amount" step="1" class="slider" oninput="updateAmount('<?php echo $session; ?>', '<?php echo $room;?>')"
               min="<?php echo $room === 1 ? 1 : ($room == 2 ? 501 : ($room === 3 ? 1501 : 0)); ?>"
               max="<?php echo $room === 1 ? 500 : ($room == 2 ? 1500 : ($room === 3 ? 2500 : 0)); ?>"
               value="<?php echo $room === 1 ? 100 : ($room == 2 ? 750 : ($room === 3 ? 2000 : 0)); ?>"
                data-value = "Slide to change money !!">
        <a class="button plus-minus" onclick="increase_amount('<?php echo $session;?>','<?php echo $room;?>','<?php echo 100;?>');"> <i class="fas fa-plus"></i> &#8377;100 </a>
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
                <div class="small-separator"></div>
                <div id="winner_a_amount">&nbsp;</div>
            </div>
        </label>
        <label class="container">
            <input type="radio" name="slot" value="T2" id="slot_b">
            <div class="slot">
                <div id="winner_b">Loading Slots</div>
                <div class="small-separator"></div>
                <div id="winner_b_amount">&nbsp;</div>
            </div>
        </label>
        <div class="separator"></div>
        <span class="error" id="room-error"></span>
        <input type="submit" value="Place Bid" id="place_bid" onclick="place_bid_text()">
        <div class="small-gap"></div>
    </div>
    <div class="timer" id="timer_slots">&nbsp;</div>
</form>