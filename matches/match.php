<?php
include "../Common.php";
include "../data.php";
$common = new Common();
$data = new Data();
$match_id = $_GET['match_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../overs.css?version=<?php echo time(); ?>">
    <title>Match</title>
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <style>
        body {
            background-color: #000000;
            background-size: cover;
        }
    </style>
</head>
<body>
<div class="header"><h1>IPL - 2024</h1></div>
<div class="sub-header"><h2>Available Balance : <?php echo $common->get_wallet_balance($data->get_connection(), $common->get_auth_cookie($data->get_auth_cookie_name())); ?></h2></div>
<div class="sub-header"><h1><?php echo $common->get_match_name($data->get_connection(), $match_id)?></h1></div>
<div class="row">
    <div class="innings">
        <div class="innings_container">
            <p><h1 style="color: black">First Inning</h1></p>
<?php
    for ($i = 1;$i<=20;$i++){
        if (!$common->over_started($data->get_connection(), $match_id, 1, $i)) {
                $expected_run = $common->get_expected_runs_from_over($data->get_connection(), $match_id, 1, $i);
                if($expected_run != -1) { ?>
                    <a class="open" href="over.php?match_id=<?php echo $match_id;?>&innings=1&over=<?php echo $i; ?>">Over <?php echo $i; ?></a>
                <?php } else { ?>
                    <a class="yettostart" href="over.php?match_id=<?php echo $match_id;?>&innings=1&over=<?php echo $i; ?>">Over <?php echo $i; ?></a>
                <?php }
        } else { ?>
            <a class="closed" href="over.php?match_id=<?php echo $match_id;?>&innings=1&over=<?php echo $i; ?>">Over <?php echo $i; ?></a>

    <?php } ?>
        <span>&nbsp;</span>
   <?php }
?>
        </div>
    </div>
    <div class="innings">
        <div class="innings_container">
            <p style="text-align: center"><h1 style="color: black">Second Innings</h1></p>
        <?php
    for ($i = 1;$i<=20;$i++){
        if (!$common->over_started($data->get_connection(), $match_id, 2, $i)) {
            $expected_run = $common->get_expected_runs_from_over($data->get_connection(), $match_id, 2, $i);
            if($expected_run != -1) { ?>
                <a class="open" href="over.php?match_id=<?php echo $match_id;?>&innings=2&over=<?php echo $i; ?>">Over <?php echo $i; ?></a>
            <?php } else { ?>
                <a class="yettostart" href="over.php?match_id=<?php echo $match_id;?>&innings=2&over=<?php echo $i; ?>">Over <?php echo $i; ?></a>
            <?php }
        } else { ?>
            <a class="closed" href="over.php?match_id=<?php echo $match_id;?>&innings=2&over=<?php echo $i; ?>">Over <?php echo $i; ?></a>
        <?php
        }
        ?>
        <span>&nbsp;</span>
            <?php
    }
        ?>
    </div>
</div>
<a class="button wide login" href="../index.php">Go Home</a>
<a class="button wide login" href="show_all.php?match_id=<?php echo $match_id;?>">Show My Bids</a>
<hr>
<div class="footer">
    <p>Created By: US.</p>
    <p>Contact Us On : </p>
</div>
</body>
</html>
