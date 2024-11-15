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
    <link rel="stylesheet" type="text/css" href="../style.css?version=<?php echo time(); ?>">
    <title>Over</title>
    <link rel="icon" type="image/x-icon" href="../  cricket.ico">
</head>
<body>
<div class="header"><h1>IPL - 2024</h1></div>
<?php

if (!$common->over_started($data->get_connection(), $match_id, $innings, $over)) {
    $expected_run = $common->get_expected_runs_from_over($data->get_connection(), $match_id, $innings, $over);
    if($expected_run != -1) { ?>
        <div class="sub-header"><h2>Available Balance : <?php echo $common->get_wallet_balance($data->get_connection(), $common->get_auth_cookie($data->get_auth_cookie_name())); ?></h2></div>
        <h2>
        <a class="play" href="book.php?match_id=<?php echo $match_id;?>&innings=<?php echo $innings;?>&over=<?php echo $over;?>">Place New Bid </a>
            </h2>
    <?php
    } else { ?>
        <div class="sub-header"><h1>Over <?php echo $over;?> Yet to open. Please come back after sometime</h1></h2></div>
<?php }
} else{ ?>
    <div class="sub-header"><h1>Over <?php echo $over; ?> Over Closed for new Orders</h1></h2></div>
<?php } ?>
<hr>
<h2>All Your Orders on Over <?php echo $over; ?></h2>
<hr>
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
    $amount = 0.0;
    $total = 0.0;
    while($row = $result->fetch_assoc()){
        $lower_run_limit = (int)$row['run_min'];
        $upper_run_limit = (int)$row['run_max'];
        $actual_run = $common->get_actual_run($data->get_connection(), $match_id, $innings, $over);
        $bid_amount = $row['amount'];
        $rate = floatval($row['rate']);
        $status = $row['status'];

        $wins = -1;
        $actual_rate = -1;
        if (!str_contains($status, "cancel")) {
            if ($actual_run != -1) {
                if ($actual_run >= $lower_run_limit && $actual_run <= $upper_run_limit) {
                    $actual_rate = $rate;
                    $amount += $actual_rate * $bid_amount;
                    $wins = $bid_amount * $rate;
                }
            }
            $total += $bid_amount;
        }
    if (!str_contains($status, "cancel")) {
        if ($actual_run == -1){ ?>
            <tr style="text-align: center">
                <td><?php echo $lower_run_limit;?></td>
                <td><?php echo $upper_run_limit?></td>
                <td>--</td>
                <td>Rs<?php echo $bid_amount?></td>
                <td><?php echo $rate; ?></td>
                <td>--</td>
                <td><?php echo $status?></td>
            </tr>
            <?php }else {
                if ($wins == -1){ ?>
                    <tr class="loss" style="text-align: center">
                        <td><?php echo $lower_run_limit;?></td>
                        <td><?php echo $upper_run_limit?></td>
                        <td><?php echo $actual_run?></td>
                        <td>Rs<?php echo $bid_amount?></td>
                        <td><?php echo $rate; ?></td>
                        <td><?php echo "--"; ?></td>
                        <td><?php echo $status?></td>
                    </tr>
                <?php }else { ?>
                    <tr class="win" style="text-align: center">
                        <td><?php echo $lower_run_limit;?></td>
                        <td><?php echo $upper_run_limit?></td>
                        <td><?php echo $actual_run?></td>
                        <td>Rs<?php echo $bid_amount?></td>
                        <td><?php echo $rate; ?></td>
                        <td><?php echo $wins; ?></td>
                        <td><?php echo $status?></td>
                    </tr>
                <?php }
                }
        } else { ?>
        <tr class="cancel" style="text-align: center">
            <td><?php echo $lower_run_limit;?></td>
            <td><?php echo $upper_run_limit?></td>
            <td>--</td>
            <td>Rs<?php echo $bid_amount?></td>
            <td><?php echo $rate; ?></td>
            <td>--</td>
            <td><?php echo $status?></td>
        </tr>
    <?php }
    }
    if ((($amount - $total) > 0) && $common->get_actual_run($data->get_connection(), $match_id, $innings, $over) != -1) { ?>
    <tr class="win" style="text-align: center">
        <td></td>
        <td></td>
        <td></td>
        <td>Rs<?php echo $total; ?></td>
        <td></td>
        <td>Rs<?php echo $amount; ?></td>
        <td style="font-weight: bold"><?php echo "Profit of Rs".($amount-$total);?></td>
    </tr>
    <?php } else if ((($total - $amount) > 0) && $common->get_actual_run($data->get_connection(), $match_id, $innings, $over) != -1) { ?>
    <tr class="loss" style="text-align: center">
        <td></td>
        <td></td>
        <td></td>
        <td>Rs<?php echo $total; ?></td>
        <td></td>
        <td>Rs<?php echo $amount; ?></td>
        <td style="font-weight: bold"><?php echo "Loss of Rs".($total-$amount);?></td>
    </tr>
    <?php } else { ?>
        <td></td>
        <td></td>
        <td></td>
        <td>Rs<?php echo $total; ?></td>
        <td></td>
        <td>Rs<?php echo $amount; ?></td>
        <td></td>
    <?php }?>
    </tbody>
    </table>
    <p>Runs mentioned above are inclusive of actual runs.</p>
    <?php
} else { ?>
    <h2>You have not placed any orders yet.</h2>
    <hr>
<?php }


if ($over > 1){
    $prev = $over - 1;
    ?>
    <a href="<?php echo $data->get_path()."matches/over.php?match_id=".$match_id."&innings=".$innings."&over=".$prev; ?>">Previous Over</a>
<?php }
?>

<?php
if ($over < 21){
    $next = $over + 1;
    ?>
    <a href="<?php echo $data->get_path()."matches/over.php?match_id=".$match_id."&innings=".$innings."&over=".$next; ?>">Next Over</a>
<?php }
?>
<hr>
<a href="../matches/index.php">Go Home</a>
<hr>
<div class="footer">
    <p>Created By: US.</p>
    <p>Contact Us On : </p>
</div>
</body>
</html>


