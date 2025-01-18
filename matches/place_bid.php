<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($common->is_user_logged_in()){
    if (isset($_GET['session']) &&
        ($common->is_valid_session_slot($_GET['session']) || $_GET['session'] == "winner") || $_GET['session'] == "special") {

        $session = $_GET['session'];
        $series_id = $common->get_cookie("series_id");
        $match_id = $common->get_cookie("match_id");
        ?>
            <html lang="">
                <head>
                    <title>Place Your Bid</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
                    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
                    <link rel="icon" type="image/x-icon" href="../cricket.ico">
                    <script src="../scripts.js?version=<?php echo time(); ?>"></script>
                </head>
                <body onload="update_session_slot_details('<?php echo $session;?>');
                        update_winner_slot_details('<?php echo $session; ?>');
                        fill_header();fill_balance();
                        fill_scorecard('<?php echo $series_id;?>', '<?php echo $match_id;?>');
                        fill_footer();">
                    <div id="header"></div>
                    <i class="fa fa-refresh refresh-button" onclick="location.reload();"></i>
                    <div class="bid_container">
                        <?php
                        if ($common->is_valid_session_slot($session)) {
                            echo file_get_contents($common->get_path()."matches/session_bids.php?session=".$session);
                        } else if ($session == 'winner') {
                            echo file_get_contents($common->get_path()."matches/winner_bids.php?session=".$session);
                        } else if($session == 'special') {
                            echo file_get_contents($common->get_path()."matches/special_bids.php?session=".$session);
                        }else { ?>
                                <div class="title">Invalid Slot. Not reachable statement</div>
                        <?php }?>
                        <div class="bid_button_div" style="margin-left: 25%"><a class="button" href="match.php?match_id=<?php echo $common->get_cookie('match_id'); ?>&series_id=<?php echo $common->get_cookie('series_id'); ?>&match_name=<?php echo $common->get_cookie('match_name'); ?>">Go Back</a></div>
                    </div>
                    <div class="separator"></div>
                    <div id="scorecard"></div>
                    <div class="separator"></div>
                    <div id="footer"></div>
                </body>
            </html>
        <?php
        } else {
            header("Location: ".$data->get_path()."/index.php");
        }

} else {header("Location: ../index.php");}
?>