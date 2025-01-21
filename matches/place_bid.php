<html lang="">
<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($common->is_user_logged_in()){
    if (isset($_GET['session']) &&
        ($common->is_valid_session_slot($_GET['session']) || $_GET['session'] == "winner") || $_GET['session'] == "special") {
        if (!isset($_GET['room'])){ ?>
<head>
    <title>Place Your Bid</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <script src="../scripts.js?version=<?php echo time(); ?>"></script>
    <script>
        function open_popup() {
            document.getElementById('popup').style.display = 'block';
            document.getElementById('popup-overlay').style.display = 'block';
        }
    </script>
</head>
        <body onload="open_popup()">
            <div class="popup-overlay" id="popup-overlay"></div>
            <div class="popup" id="popup">
                <div class="sub-title"><h1>Select Room !!</h1></div>
                <div class="popup-sub-header">Select Room of your choice based on interested bid amount.</div>
                <div class="bid_container">
                    <div class="title">Bid Amount</div>
                    <div class="smaller-gap"></div>
                    <a class="room_button" href="place_bid.php?session=<?php echo $_GET['session']; ?>&room=1">Less Than &#8377;500</a>
                    <div class="smaller-gap"></div>
                    <a class="room_button" href="place_bid.php?session=<?php echo $_GET['session']; ?>&room=2">&#8377;501 to &#8377;1000</a>
                    <div class="smaller-gap"></div>
                    <a class="room_button" href="place_bid.php?session=<?php echo $_GET['session']; ?>&room=3">&#8377;1001 to &#8377;2500</a>
                    <div class="smaller-gap"></div>
                </div>
            </div>
        </body>
        </html>
        <?php } else {
            $room = $_GET['room'];
            $session = $_GET['session'];
            $series_id = $common->get_cookie("series_id");
            $match_id = $common->get_cookie("match_id");
            ?>
            <head>
                <title>Place Your Bid</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
                <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                <link rel="icon" type="image/x-icon" href="../cricket.ico">
                <script src="../scripts.js?version=<?php echo time(); ?>"></script>
                <script>
                    function open_popup() {
                        document.getElementById('popup').style.display = 'block';
                        document.getElementById('popup-overlay').style.display = 'block';
                    }
                    function init() {
                        document.getElementById('amount').addEventListener('mouseup', () => onRelease('<?php echo $session; ?>', '<?php echo $room; ?>'));
                        document.getElementById('amount').addEventListener('touchend', () => onRelease('<?php echo $session; ?>', '<?php echo $room; ?>'));
                    }

                </script>
            </head>
                    <body onload="<?php if(strlen($session) == 2) { echo 'update_session_slot_details(\''. $session.'\', '.$room.');';} ?><?php if($session === 'winner'){echo 'update_winner_slot_details(\''.$session.'\', '.$room.');';} ?>updateAmount();fill_header();fill_balance();fill_scorecard('<?php echo $series_id;?>', '<?php echo $match_id;?>');fill_footer();init()">
                        <div id="header"></div>
                        <i class="fa fa-refresh refresh-button" onclick="location.reload();"></i>
                        <div class="slot_container_outer">
                            <?php
                            if ($common->is_valid_session_slot($session)) {
                                echo file_get_contents($common->get_path()."matches/session_bids.php?session=".$session."&room=".$room);
                            } else if ($session == 'winner') {
                                echo file_get_contents($common->get_path()."matches/winner_bids.php?session=".$session."&room=".$room);
                            } else if($session == 'special') {
                                echo file_get_contents($common->get_path()."matches/special_bids.php?session=".$session."&room=".$room);
                            }else { ?>
                                    <div class="title">Invalid Slot. Not reachable statement</div>
                            <?php }?>
                            <div style="display: flex">
                                <div class="bid_button_div"><a class="button" href="match.php?match_id=<?php echo $common->get_cookie('match_id'); ?>&series_id=<?php echo $common->get_cookie('series_id'); ?>&match_name=<?php echo $common->get_cookie('match_name'); ?>">Change Bid Type</a></div>
                                <div class="bid_button_div"><a class="button" href="place_bid.php?session=<?php echo $session ?>">Change Room</a></div>
                            </div>
                            <div class="separator"></div>
                        </div>
                        <div id="scorecard"></div>
                        <div class="separator"></div>
                        <div id="footer"></div>
                    </body>
                </html>
            <?php
            }
    }else {
            header("Location: ".$data->get_path()."/index.php");
    }
} else {header("Location: ../index.php");}
?>