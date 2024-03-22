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
    <link rel="stylesheet" href="../overs.css">
    <title>IPL 2024 - Match</title>
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
<?php
    for ($i = 1;$i<=20;$i++){
        if (!$common->over_started($data->get_connection(), $match_id, 1, $i)) {
                $expected_run = $common->get_expected_runs_from_over($data->get_connection(), $match_id, 1, $i);
                if($expected_run != -1) { ?>
                    <a class="open" href="over.php?match_id=<?php echo $match_id;?>&innings=1&over=<?php echo $i; ?>">Innings 1 : Over <?php echo $i; ?></a>
                <?php } else { ?>
                    <a href="over.php?match_id=<?php echo $match_id;?>&innings=1&over=<?php echo $i; ?>">Innings 1 : Over <?php echo $i; ?></a>
                <?php }
        } else { ?>
            <a href="over.php?match_id=<?php echo $match_id;?>&innings=1&over=<?php echo $i; ?>">Innings 1 : Over <?php echo $i; ?></a>

    <?php }
    }
?>
    </div>
    <div class="innings">
        <?php
    for ($i = 1;$i<=20;$i++){
        if (!$common->over_started($data->get_connection(), $match_id, 2, $i)) {
            $expected_run = $common->get_expected_runs_from_over($data->get_connection(), $match_id, 2, $i);
            if($expected_run != -1) { ?>
                <a class="open" href="over.php?match_id=<?php echo $match_id;?>&innings=2&over=<?php echo $i; ?>">Innings 2 : Over <?php echo $i; ?></a>
            <?php } else { ?>
                <a href="over.php?match_id=<?php echo $match_id;?>&innings=2&over=<?php echo $i; ?>">Innings 2 : Over <?php echo $i; ?></a>
            <?php }
        } else { ?>
            <a href="over.php?match_id=<?php echo $match_id;?>&innings=2&over=<?php echo $i; ?>">Innings 2 : Over <?php echo $i; ?></a>
        <?php
        }
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
