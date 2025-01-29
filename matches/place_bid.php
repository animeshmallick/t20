<html lang="">
<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($common->is_user_logged_in() || (isset($_GET['auth']) && $_GET['auth'] == 'locust')){
    if (isset($_GET['session']) && ($common->is_valid_session_slot($_GET['session']) || $_GET['session'] == "winner" || $_GET['session'] == "special") &&
         isset($_GET['room'])){
            $room = intval($_GET['room']);
            $session = $_GET['session'];
            $series_id = $common->get_cookie("series_id");
            $match_id = $common->get_cookie("match_id");
            ?>
            <head>
                <!-- Google tag (gtag.js) -->
                <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z91TWPR0DM"></script>
                <script>
                    window.dataLayer = window.dataLayer || [];
                    function gtag(){dataLayer.push(arguments);}
                    gtag('js', new Date());
                    gtag('config', 'G-Z91TWPR0DM');
                </script>
                <title>Place Your Bid</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
                <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                <link rel="icon" type="image/x-icon" href="../cricket.ico">
                <script src="../scripts.js?version=<?php echo time(); ?>"></script>
                <script>
                    function init() {
                        document.getElementById('amount').addEventListener('mouseup', () => onRelease('<?php echo $session; ?>', '<?php echo $room; ?>'));
                        document.getElementById('amount').addEventListener('touchend', () => onRelease('<?php echo $session; ?>', '<?php echo $room; ?>'));
                    }
                </script>
            </head>
                    <body onload="<?php if(strlen($session) == 2) { echo 'update_session_slot_details(\''. $session.'\', '.$room.');';} ?><?php if($session === 'winner'){echo 'update_winner_slot_details(\''.$session.'\', '.$room.');';} ?>updateAmount();fill_header();fill_balance();fill_scorecard('<?php echo $series_id;?>', '<?php echo $match_id;?>');fill_footer();init();">
                        <div id="header"></div>
                        <div id="scorecard"></div>
                        <div class="play-container">
                            <div class="title">Select Room Based On Bid Amount</div>
                            <div style="display: flex; justify-content: space-between">
                                <a class="room <?php echo $room === 1 ? 'room-selected' : ''?>" href="place_bid.php?session=<?php echo $_GET['session']; ?>&room=1" id="room_1"><span>&#8377;1 - &#8377;500</span></a>
                                <a class="room <?php echo $room === 2 ? 'room-selected' : ''?>" href="place_bid.php?session=<?php echo $_GET['session']; ?>&room=2" id="room_1"><span>&#8377;500 - &#8377;1500</span></a>
                                <a class="room <?php echo $room === 3 ? 'room-selected' : ''?>" href="place_bid.php?session=<?php echo $_GET['session']; ?>&room=3" id="room_1"><span>&#8377;1500 - &#8377;2500</span></a>
                            </div>
                        </div>
                        <i class="fa fa-refresh refresh-button" onclick="location.reload();"></i>
                        <div class="slot_container_outer" id="bid_container">
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
                                <div class="bid_button_div" style="width: 80%; margin-left: 10%"><a class="button" href="match.php?match_id=<?php echo $common->get_cookie('match_id'); ?>&series_id=<?php echo $common->get_cookie('series_id'); ?>&match_name=<?php echo $common->get_cookie('match_name'); ?>">Change Bid Type</a></div>
                            <div class="separator"></div>
                        </div>
                        <div class="separator"></div>
                        <div id="footer"></div>
                    </body>
                </html>
            <?php
    }else {
            header("Location: ".$data->get_path()."/index.php");
    }
} else {header("Location: ../index.php");}
?>