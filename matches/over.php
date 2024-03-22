<?php
include "../data.php";
include "../Common.php";
$match_id = $_GET['match_id'];
$innings = $_GET['innings'];
$over = (int)$_GET['over'];

$data = new Data();
$common = new Common();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <title>IPL 2024 - Over</title>
</head>
<body>
<div class="header"><h1>IPL - 2024</h1></div>
<?php

if (!$common->over_started($data->get_connection(), $match_id, $innings, $over)) {
    $expected_run = $common->get_expected_runs_from_over($data->get_connection(), $match_id, $innings, $over);
    if($expected_run != -1) { ?>
        <div class="sub-header"><h2>Available Balance : <?php echo $common->get_wallet_balance($data->get_connection(), $common->get_auth_cookie($data->get_auth_cookie_name())); ?></h2></div>
        <div class="innings"><h2><?php echo "(TBD) Expected Run : ".$expected_run;?></h2></div>
        <a href="book.php?match_id=<?php echo $match_id;?>&innings=<?php echo $innings;?>&over=<?php echo $over;?>">Play</a>
    <?php
    } else { ?>
        <div class="sub-header"><h1>Yet to open. Please come back after sometime</h1></h2></div>
<?php }
} else{ ?>
    <div class="sub-header"><h1>Over Closed for new Orders</h1></h2></div>
<?php } ?>
<h2>Your Orders</h2>
<?php
$ref_id = (int)$common->get_auth_cookie($data->get_auth_cookie_name());
$sql = "Select * from `bid_table` where `match_id`='$match_id' and `innings`=$innings and `over_id`=$over and `ref_id`=$ref_id";
$result = $data->get_connection()->query($sql);
if ($result->num_rows > 0) { ?>
    <table border="1px">
        <thead>
        <tr>
            <th scope="col">Minimum Run</th>
            <th scope="col">Maximum Run</th>
            <th scope="col">Actual Run</th>
            <th scope="col">Amount</th>
            <th scope="col">Rate</th>
            <th scope="col">Total</th>
            <th scope="col">Status</th>
        </tr>
        </thead>
        <tbody>
    <?php
    while($row = $result->fetch_assoc()){ ?>
            <tr style="text-align: center">
                <td><?php $min_run = (int)$row['run_min']; echo $min_run; ?></td>
                <td><?php $max_run = (int)$row['run_max']; echo $max_run; ?></td>
                <?php $a_run = $common->get_actual_run($data->get_connection(), $match_id, $innings, $over); ?>
                <td><?php if($a_run == -1) echo "-"; else echo $a_run;?></td>
                <td><?php $amount = $row['amount']; echo $amount; ?></td>
                <td><?php $rate = floatval($row['rate']); echo $rate; ?></td>
                <td><?php if($a_run >= $min_run && $a_run <= $min_run) echo $amount * $rate; else echo $a_run;?></td>
                <td><?php echo $row['status'];?></td>
            </tr>
    <?php
    } ?>
    </tbody>
    </table>
    <p>Runs mentioned above are inclusive of actual runs.</p>
    <?php
} else { ?>
    <h2>You have not placed any orders yet.</h2>
<?php }


if ($over > 1){
    $prev = $over - 1;
    ?>
    <a href="<?php echo $data->get_path()."matches/over.php?match_id=".$match_id."&innings=".$innings."&over=".$prev; ?>">Previous Over</a>
<?php }
?>

<a href="../matches/index.php">Go Home</a>
<?php
if ($over < 21){
    $next = $over + 1;
    ?>
    <a href="<?php echo $data->get_path()."matches/over.php?match_id=".$match_id."&innings=".$innings."&over=".$next; ?>">Next Over</a>
<?php }
?>
<h4></h4>
<div class="footer">
    <p>Created By: US.</p>
    <p>Contact Us On : </p>
</div>
</body>
</html>


