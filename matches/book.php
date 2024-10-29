<html lang="en">
<head>
    <title>Book</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style.css?version=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <script src="../scripts.js">

    </script>
    <style>
        input[type!=radio] {

        }
    </style>
</head>

<?php
include "../data.php";
include "../Common.php";
include "../bid_data.php";
$data = new Data();
$common = new Common();
$bid_data = new Bid_data();

if ($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['match_id']) and isset($_GET['innings']) and isset($_GET['over'])) {
    $match_id = $_GET['match_id'];
    $innings = $_GET['innings'];
    $over = floatval($_GET['over']);

    $upper_limit_1 = max(($over - 2)*6, 12);
    $upper_limit_2 = max(($over - 3)*6, 12);

    if (!$common->over_started($data->get_connection(), $match_id, $innings, $over)) {
        $expected_run = $common->get_expected_runs_from_over($data->get_connection(), $match_id, $innings, $over);
        if($expected_run != -1) {
            if (isset($_GET['amount'])) {
                $amount = $_GET['amount'];
                $rate_a = $common->get_rate($data->get_connection(), $bid_data->get_max_affordable_loss(), $match_id,
                    $innings, $over, $amount, 'a', $bid_data->get_bid_placed_status(), $bid_data->get_max_rate());
                $rate_b = $common->get_rate($data->get_connection(),$bid_data->get_max_affordable_loss(), $match_id,
                    $innings, $over, $amount, 'b', $bid_data->get_bid_placed_status(), $bid_data->get_max_rate());
                $rate_c = $common->get_rate($data->get_connection(),$bid_data->get_max_affordable_loss(),$match_id,
                    $innings, $over, $amount, 'c', $bid_data->get_bid_placed_status(), $bid_data->get_max_rate());
                $run_a = $expected_run - $bid_data->get_lower_diff();
                $run_aa = (int)$run_a;
                $run_b = $expected_run + $bid_data->get_upper_diff() + 1;
                $run_bb = (int)$run_b;
                ?>
                <body">
                <div class="bid_container">
                    <div class="form">
                        <header>New Bid on Over <?php echo $over;?></header>
                        <form action="book.php" method="POST" name="bid_form">
<input type="number" id="bid_id" name="bid_id" value="<?php echo get_unique_bid_id($data->get_connection());?>" readonly required hidden="hidden">
                            <input type="text" id="match_id" name="match_id" value="<?php echo $match_id;?>" readonly required hidden="hidden">
                            <input type="number" id="innings" name="innings" value="<?php echo $innings;?>" readonly required hidden="hidden">
                            <input type="number" id="over" name="over" value="<?php echo $over;?>" readonly required hidden="hidden">

                            <label for="amount">Amount:</label>
                            <input type="number" id="amount" name="amount" value="<?php echo $amount; ?>" readonly required>

                            <label for="slot">Choose a Slot for Over : <?php echo $over; ?></label>
                            <hr>
                            <label for="slot_a" name="slot"><input type="radio" id="slot_a" name="slot" value="a" checked>
                                <?php echo "Run [0 to ".$run_aa."] @ ".$common->rate_convertor($rate_a, $data->base_amount_for_rate_conversion);?>
                            </label>
                            <label for="slot_b" name="slot"><input type="radio" id="slot_b" name="slot" value="b">
                                <?php echo "Run [".($run_aa + 1)." to ".$run_bb."] @ ".$common->rate_convertor($rate_b, $data->base_amount_for_rate_conversion);?>
                            </label>
                            <label for="slot_c" name="slot"><input type="radio" id="slot_c" name="slot" value="c">
                                <?php echo "Run [".($run_bb + 1)." or more] @ ".$common->rate_convertor($rate_c, $data->base_amount_for_rate_conversion);?>
                            </label>
                            <hr>
                            <input type="submit" class="button" value="Submit">
                        </form>
                        <p class="error" id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></p>
                        <a class="wide" href="book.php?match_id=<?php echo $match_id;?>&innings=<?php echo $innings;?>&over=<?php echo $over;?>">Back</a>
                        <a class="button wide login" href="../index.php">Go Home</a>
                    </div>
                </div>
            <?php
            } else {

                ?>
                <body>
                <div class="bid_container">
                    <div class="form">
                        <header>New Bid on Over <?php echo $over;?></header>
                        <form action="book.php" method="GET" name="bid_form">
                            <input type="text" id="match_id" name="match_id" value="<?php echo $match_id;?>" readonly required hidden="hidden">
                            <input type="number" id="innings" name="innings" value="<?php echo $innings;?>" readonly required hidden="hidden">
                            <input type="number" id="over" name="over" value="<?php echo $over;?>" readonly required hidden="hidden">

                            <label for="amount">Amount:</label>
                            <input type="number" id="amount" name="amount" required>
                            <input type="submit" class="button" value="Submit">
                        </form>
                        <p class="error" id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></p>
                        <a class="wide" href="match.php?match_id=<?php echo $match_id;?>&innings=<?php echo $innings;?>&over=<?php echo $over;?>">Back</a>
                        <a class="button wide login" href="../index.php">Go Home</a>
                    </div>
                </div>
            <?php
            }
        } else
            echo "Yet to Start";
    } else
        echo "Over closed";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bid_id = $_POST['bid_id'];
    $ref_id = $common->get_auth_cookie($data->get_auth_cookie_name());
    $match_id = $_POST['match_id'];
    $innings = $_POST['innings'];
    $over = $_POST['over'];
    $slot = $_POST['slot'];
    $amount = $_POST['amount'];
    $expected_run = $common->get_expected_runs_from_over($data->get_connection(), $match_id, $innings, $over);
    if ($expected_run != -1) {
        $run_a = $expected_run - $bid_data->get_lower_diff();
        $run_aa = (int)$run_a;
        $run_b = $expected_run + $bid_data->get_upper_diff();
        $run_bb = (int)$run_b;
        if ($slot == 'a') {
            $run_min = 0;
            $run_max = $run_aa;
            $rate = $common->get_rate($data->get_connection(),$bid_data->get_max_affordable_loss(), $match_id, $innings,
                $over, $amount, 'a', $bid_data->get_bid_placed_status(), $bid_data->get_max_rate());
        } else if($slot == 'b') {
            $run_min = $run_aa + 1;
            $run_max = $run_bb;
            $rate = $common->get_rate($data->get_connection(),$bid_data->get_max_affordable_loss(), $match_id, $innings,
                $over, $amount, 'b', $bid_data->get_bid_placed_status(), $bid_data->get_max_rate());
        } else if($slot == 'c') {
            $run_min = $run_bb + 1;
            $run_max = 99;
            $rate =$common->get_rate($data->get_connection(),$bid_data->get_max_affordable_loss(), $match_id, $innings,
                $over, $amount, 'c', $bid_data->get_bid_placed_status(), $bid_data->get_max_rate());
        } else {echo "Something went wrong";}
        $status = "placed";

        if($common->get_wallet_balance($data->get_connection(), $ref_id) >= $amount) {
            $sql = "INSERT INTO `bid_table` (`bid_id`, `ref_id`, `match_id`, `innings`, `over_id`, `slot`, `run_min`, `run_max`, `amount`, `rate`, `status`) VALUES ($bid_id, $ref_id, '$match_id', $innings, $over, '$slot', $run_min, $run_max, $amount, $rate, 'placed')";
            if ($data->get_connection()->query($sql) === TRUE) {
                $common->deduct_wallet_balance($data->get_connection(), $ref_id, $amount, $bid_id, $common->get_unique_tran_id($data->get_connection()));
                ?>
                <body onload="cancel_bid_service(<?php echo $match_id;?>, <?php echo $innings;?>, <?php echo $over; ?>)">
                <div class="innings" style="color: black"><h1>Your Order is Placed Successfully</h1></div>
                <div class="header"><h1>Bid Placed for runs between [<?php echo $run_min;?> and <?php echo $run_max; ?>] of Rs<?php echo $amount;?> @ rate of <?php echo $rate; ?></h1></div>
                    <a id="cancel_bid" href="cancel_bid.php?bid_id=<?php echo $bid_id;?>"> Cancel This Bid</a>
                    <a class="live" href="over.php?match_id=<?php echo $match_id; ?>&innings=<?php echo $innings;?>&over=<?php echo $over; ?>">I don't want to Cancel</a>
                <?php } else { ?>
                <body>
                    <div class="header"><h1>Bid Placing Failed due to duplicate bid found.</h1></div>
                    <a class="button wide login" href="../index.php">Go Home</a>
                </body>
                <?php
                }
        } else { ?>
            <body>
            <div class="header"><h1>Not enough balance in the wallet. Please recharge</h1></div>
            <a class="button wide login" href="../index.php">Go Home</a>
            <?php
        }
    }
}

function get_unique_bid_id($connection) {
    $bid_id = mt_rand(10000000, 99999999);
    $sql = "Select `bid_id` from `bid_table` where `bid_id`=$bid_id";
    while($connection->query($sql)->num_rows != 0){
        $bid_id = mt_rand(10000000, 99999999);
    }
    return $bid_id;
}
?>
</body>
</html>


