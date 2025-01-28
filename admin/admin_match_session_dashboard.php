<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($common->is_user_logged_in() && $common->is_user_an_admin()){
    $series_id = $common->get_cookie("series_id");
    $match_id = $common->get_cookie("match_id");
    $session = $_GET['session'];
    $all_bids = $common->get_all_bids($series_id, $match_id, 'session');
    $all_matches= $common->get_all_matches();
    $all_users = $common->get_all_users();
    $all_bids_new = array();
    foreach ($all_bids as $bid) {
        if ($bid->session == $session[0] && $bid->innings == $session[1]) {
            $all_bids_new[] = $bid;
        }
    } ?>
<html lang="">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z91TWPR0DM"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-Z91TWPR0DM');
    </script>
    <title>All Bids</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <script src="../scripts.js?version=<?php echo time(); ?>"></script>
</head>
<body onload="fill_header();fill_balance();fill_footer()">
    <div id="header"></div>
    <div class="scorecard-container">
        <div class="sub-title"><?php echo $common->get_match_name_match_id($all_matches,
                                            $common->get_cookie('match_id'),
                                            $common->get_cookie('series_id')); ?></div>
        <div class="control-title">All Bids On Session <?php echo $session; ?></div>
        <table>
            <tbody>
    <?php foreach ($all_bids_new as $bid) { ?>
                    <tr>
                        <td><?php echo $common->get_user_from_users($all_users, $bid->ref_id); ?></td>
                        <td>
                            <?php if ($bid->slot == 'x')
                                echo 'Runs '.$bid->runs_max." or Less";
                            else if($bid->slot == 'y')
                                echo "Runs ".$bid->runs_min." to ".$bid->runs_max;
                            else if($bid->slot == 'z')
                                echo "Runs ".$bid->runs_min." or More";
                            $result=explode(" VS ", $common->get_match_name_match_id($all_matches, $bid->match_id, $bid->series_id));
                            if($bid->slot=="T1")
                                echo $result[0]." Wins";
                            if($bid->slot=="T2")
                                echo $result[1]." Wins";
                            ?>
                        </td>
                        <?php $amount_string = '₹'.$bid->amount." && ₹".(int)($bid->amount * $bid->rate);
                        if($bid->status=="placed")
                            $amount_string = str_replace('&&', "may return", $amount_string);
                        else if($bid->status=="cancel")
                            $amount_string = '₹'.$bid->amount.' Cancelled';
                        else if($bid->status=="win")
                            $amount_string = str_replace('&&', "became", $amount_string);
                        else if($bid->status=="loss")
                            $amount_string = str_replace('&&', "failed to", $amount_string);
                        else
                            $amount_string = "BID Status Invalid";
                        ?>
                        <td><?php echo $amount_string; ?></td>
                        <td><?php echo $bid->status; ?></td>
                        <td><?php echo $bid->room; ?></td>
                        <?php if ($bid->status == "placed") { ?>
                        <td><a onclick="settle_bid('<?php echo $bid->bid_id;?>', 'session', '<?php echo $bid->session.$bid->innings;?>')" class="button" style="padding: 1rem 0.5rem; margin: 0" href="#">Settle</a> </td>
                        <?php } ?>
                    </tr>

    <?php }
} else {
        header("Location: ".$data->get_path());
    } ?>
            </tbody>
        </table>
        <div class="bid_button_div" style="margin-left: 25%;"><a class="button" href="../views/admin_match_dashboard.php">Go Back</a></div>
    </div>
    <div class="separator"></div>
    <div id="footer"></div>
</body>
</html>
