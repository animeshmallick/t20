<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($common->is_user_logged_in() && $common->is_user_an_admin()){
    $series_id = $common->get_cookie("series_id");
    $match_id = $common->get_cookie("match_id");
    $all_bids = $common->get_all_bids($series_id, $match_id);
    $session_a1 = array();
    $session_a1['count'] = 0;
    $session_a1['collected'] = 0;
    $session_a1['distributed_1'] = 0;
    $session_a1['distributed_2'] = 0;
    $session_a1['distributed_3'] = 0;

    $session_b1 = $session_a1;
    $session_c1 = $session_a1;
    $session_d1 = $session_a1;
    $session_a2 = $session_a1;
    $session_b2 = $session_a1;
    $session_winner = $session_a1;
    foreach ($all_bids as $bid){
        if ($bid->type == 'winner'){
            $session_winner['count'] += 1;
            $session_winner['collected'] += $bid->amount;
            if ($bid->slot == 'T1')
                $session_winner['distributed_1'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'T2')
                $session_winner['distributed_2'] += (int)((int)$bid->amount * $bid->rate);
            continue;
        }
        if ($bid->session.$bid->innings == 'a1'){
            $session_a1['count'] += 1;
            $session_a1['collected'] += $bid->amount;
            if ($bid->slot == 'x')
                $session_a1['distributed_1'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'y')
                $session_a1['distributed_2'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'z')
                $session_a1['distributed_3'] += (int)((int)$bid->amount * $bid->rate);
            continue;
        }
        if ($bid->session.$bid->innings == 'b1'){
            $session_b1['count'] += 1;
            $session_b1['collected'] += $bid->amount;
            if ($bid->slot == 'x')
                $session_b1['distributed_1'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'y')
                $session_b1['distributed_2'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'z')
                $session_b1['distributed_3'] += (int)((int)$bid->amount * $bid->rate);
            continue;
        }
        if ($bid->session.$bid->innings == 'c1'){
            $session_c1['count'] += 1;
            $session_c1['collected'] += $bid->amount;
            if ($bid->slot == 'x')
                $session_c1['distributed_1'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'y')
                $session_c1['distributed_2'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'z')
                $session_c1['distributed_3'] += (int)((int)$bid->amount * $bid->rate);
            continue;
        }
        if ($bid->session.$bid->innings == 'd1'){
            $session_d1['count'] += 1;
            $session_d1['collected'] += $bid->amount;
            if ($bid->slot == 'x')
                $session_d1['distributed_1'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'y')
                $session_d1['distributed_2'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'z')
                $session_d1['distributed_3'] += (int)((int)$bid->amount * $bid->rate);
            continue;
        }
        if ($bid->session.$bid->innings == 'a2'){
            $session_a2['count'] += 1;
            $session_a2['collected'] += $bid->amount;
            if ($bid->slot == 'x')
                $session_a2['distributed_1'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'y')
                $session_a2['distributed_2'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'z')
                $session_a2['distributed_3'] += (int)((int)$bid->amount * $bid->rate);
            continue;
        }
        if ($bid->session.$bid->innings == 'b2'){
            $session_b2['count'] += 1;
            $session_b2['collected'] += $bid->amount;
            if ($bid->slot == 'x')
                $session_b2['distributed_1'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'y')
                $session_b2['distributed_2'] += (int)((int)$bid->amount * $bid->rate);
            if ($bid->slot == 'z')
                $session_b2['distributed_3'] += (int)((int)$bid->amount * $bid->rate);
            continue;
        }
    }
    ?>
    <html lang="">
    <head>
        <title>Admin Match Dashboard</title>
        <meta charset="utf-8"/>
        <link rel="icon" type="image/x-icon" href="../cricket.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <script src="../scripts.js"></script>
    </head>
    <body onload="fill_header();fill_footer()">
    <div id="header"></div>
    <div class="scorecard-container">
        <div class="sub-title"><?php echo $common->get_cookie('match_name');?></div>
        <div class="small-gap"></div>
        <div class="match-detail">
            <div style="display: flex">
                <div class="bid_container" style="width: 50%;">
                    <a class="button" href="../admin/admin_match_session_dashboard.php?session=a1">Session A1</a>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Bids Placed : <?php echo $session_a1['count']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Collected : <?php echo "₹".$session_a1['collected']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S1 : <?php echo "₹".$session_a1['distributed_1']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S2 : <?php echo "₹".$session_a1['distributed_2']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S3 : <?php echo "₹".$session_a1['distributed_3']; ?></div>
                </div>
                <div class="bid_container" style="width: 50%">
                    <a class="button" href="../admin/admin_match_session_dashboard.php?session=b1">Session B1</a>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Bids Placed : <?php echo $session_b1['count']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Collected : <?php echo "₹".$session_b1['collected']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S1 : <?php echo "₹".$session_b1['distributed_1']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S2 : <?php echo "₹".$session_b1['distributed_2']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S3 : <?php echo "₹".$session_b1['distributed_3']; ?></div>
                </div>
            </div>
            <div class="separator"></div>
            <div style="display: flex">
                <div class="bid_container" style="width: 50%;">
                    <a class="button" href="../admin/admin_match_session_dashboard.php?session=c1">Session C1</a>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Bids Placed : <?php echo $session_c1['count']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Collected : <?php echo "₹".$session_c1['collected']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S1 : <?php echo "₹".$session_c1['distributed_1']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S2 : <?php echo "₹".$session_c1['distributed_2']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S3 : <?php echo "₹".$session_c1['distributed_3']; ?></div>
                </div>
                <div class="bid_container" style="width: 50%">
                    <a class="button" href="../admin/admin_match_session_dashboard.php?session=d1">Session D1</a>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Bids Placed : <?php echo $session_d1['count']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Collected : <?php echo "₹".$session_d1['collected']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S1 : <?php echo "₹".$session_d1['distributed_1']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S2 : <?php echo "₹".$session_d1['distributed_2']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S3 : <?php echo "₹".$session_d1['distributed_3']; ?></div>
                </div>
            </div>
            <div class="separator"></div>
            <div style="display: flex">
                <div class="bid_container" style="width: 50%;">
                    <a class="button" href="../admin/admin_match_session_dashboard.php?session=a2">Session A2</a>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Bids Placed : <?php echo $session_a2['count']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Collected : <?php echo "₹".$session_a2['collected']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S1 : <?php echo "₹".$session_a2['distributed_1']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S2 : <?php echo "₹".$session_a2['distributed_2']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S3 : <?php echo "₹".$session_a2['distributed_3']; ?></div>
                </div>
                <div class="bid_container" style="width: 50%">
                    <a class="button" href="../admin/admin_match_session_dashboard.php?session=b2">Session B2</a>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Bids Placed : <?php echo $session_b2['count']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Collected : <?php echo "₹".$session_b2['collected']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S1 : <?php echo "₹".$session_b2['distributed_1']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S2 : <?php echo "₹".$session_b2['distributed_2']; ?></div>
                    <div class="match-detail" style="padding: 0.3rem 1rem">Given S3 : <?php echo "₹".$session_b2['distributed_3']; ?></div>
                </div>
            </div>
            <div class="separator"></div>
            <div class="bid_container">
                <a class="button" href="../admin/admin_match_session_dashboard.php?session=winner">Winner</a>
                <div class="match-detail" style="padding: 0.5rem 1rem">Bids Placed : <?php echo $session_winner['count']; ?></div>
                <div class="match-detail" style="padding: 0.5rem 1rem">Collected : <?php echo "₹".$session_winner['collected']; ?></div>
                <div class="match-detail" style="padding: 0.5rem 1rem">Given S1 : <?php echo "₹".$session_winner['distributed_1']; ?></div>
                <div class="match-detail" style="padding: 0.5rem 1rem">Given S2 : <?php echo "₹".$session_winner['distributed_2']; ?></div>
            </div>
        </div>
    </div>
    <div class="separator"></div>
    <div id="footer"></div>
    </body>
    </html>
<?php } else {
    $common->logout();
    header("Location: ".$data->get_path());
}