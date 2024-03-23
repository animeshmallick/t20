<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style.css?version=101">
    <title>My Bids</title>
</head>
<body>
<div class="header"><h1>IPL - 2024</h1></div>
<div class="sub-header"><h2>All Bids Placed</h2></div>

    <?php
    include "../data.php";
    include "../bid_data.php";
    include "../Common.php";
    $data = new Data();
    $bid_data = new Bid_data();
    $common = new Common();

    $match_id = $_GET['match_id'];
    $old_status = $bid_data->get_bid_placed_status();

    $ref_id = $common->get_auth_cookie($data->get_auth_cookie_name());

    if($ref_id != -1) { ?>
        <table border="1px">
            <thead>
            <tr>
                <th scope="col">Player</th>
                <th scope="col">Over</th>
                <th scope="col">Low</th>
                <th scope="col">High</th>
                <th scope="col">Bid</th>
                <th scope="col">Rate</th>
                <th scope="col">Actual Run</th>
                <th scope="col">Actual Rate</th>
                <th scope="col">Amount</th>
                <th scope="col">Status</th>
            </tr>
            </thead>
            <tbody>
    <?php
        $sql = "Select * from `bid_table` where `match_id`='$match_id' and `ref_id`='$ref_id'";
        $result = $data->get_connection()->query($sql);
        $amount = 0.0;
        $total = 0.0;
        while($row = $result->fetch_assoc()){
            $innings = $row['innings'];
            $over = $row['over_id'];
            $bid_id = $row['bid_id'];
            $lower_run_limit = $row['run_min'];
            $upper_run_limit = $row['run_max'];
            $ref_id = $row['ref_id'];
            $rate = $row['rate'];
            $bid_amount = floatval($row['amount']);
            $actual_run = -1;
            $actual_rate = -1.0;
            $status=$row['status'];


            if ($common->over_started($data->get_connection(), $match_id, $innings, $over))
                $actual_run = $common->get_actual_run($data->get_connection(), $match_id, $innings, $over);
            $wins = -1;
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
            if ($actual_run == -1){ ?>
                <tr style="text-align: center">
                    <td><?php echo $common->get_user_name_from_ref_id($data->get_connection(), $ref_id); ?></td>
                    <td><?php echo $over;?></td>
                    <td><?php echo $lower_run_limit;?></td>
                    <td><?php echo $upper_run_limit?></td>
                    <td>Rs<?php echo $bid_amount?></td>
                    <td><?php echo $rate?></td>
                    <td><?php if ($actual_run != -1) echo $actual_run; else echo ""; ?></td>
                    <td><?php if ($actual_rate != -1) echo $actual_rate; else echo "--"; ?></td>
                    <td><?php if ($actual_rate != -1) echo "Rs".$wins; else echo "--"; ?></td>
                    <td><?php echo $status?></td>
                </tr>
            <?php }else {
                if ($wins == -1){ ?>
                    <tr class="loss" style="text-align: center">
                        <td><?php echo $common->get_user_name_from_ref_id($data->get_connection(), $ref_id); ?></td>
                        <td><?php echo $over;?></td>
                        <td><?php echo $lower_run_limit;?></td>
                        <td><?php echo $upper_run_limit?></td>
                        <td>Rs<?php echo $bid_amount?></td>
                        <td><?php echo $rate?></td>
                        <td><?php echo $actual_run; ?></td>
                        <td><?php echo "--"; ?></td>
                        <td><?php echo "--"; ?></td>
                        <td><?php echo $status?></td>
                    </tr>
                <?php }else { ?>
                    <tr class="win" style="text-align: center">
                        <td><?php echo $common->get_user_name_from_ref_id($data->get_connection(), $ref_id); ?></td>
                        <td><?php echo $over;?></td>
                        <td><?php echo $lower_run_limit;?></td>
                        <td><?php echo $upper_run_limit?></td>
                        <td>Rs<?php echo $bid_amount?></td>
                        <td><?php echo $rate?></td>
                        <td><?php if ($actual_run != -1) echo $actual_run; else echo ""; ?></td>
                        <td><?php if ($actual_rate != -1) echo $actual_rate; else echo "--"; ?></td>
                        <td><?php if ($actual_rate != -1) echo "Rs".$wins; else echo "--"; ?></td>
                        <td><?php echo $status?></td>
                    </tr>
                <?php }
            }
        }
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Rs<?php echo $total; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Rs<?php echo $amount; ?></td>
            <td><?php if($total >= $amount) echo "Loss of Rs".($amount - $total); else echo "Profit of Rs".($amount-$total);?></td>
        </tr>
    </tbody>
</table>
<?php
} else {
    header("Location: ".$data->get_path()."login/login.php");
}
    ?>

