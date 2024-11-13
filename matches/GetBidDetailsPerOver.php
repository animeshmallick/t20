<?php
include "../data.php";
include "../Common.php";
include "../bid_data.php";
$data = new Data();
$common = new Common();
$bid_data = new Bid_data();
$match_id = $_GET['match_id'];
$innings = $_GET['innings'];
$over = $_GET['overs'];
$amount = $_GET['amount'];
$rate_a = $common->get_rate($data->get_connection(), $bid_data->get_max_affordable_loss(), $match_id, $innings, $over,
                            $amount, 'a', $bid_data->get_bid_placed_status(), $bid_data->get_max_rate());
$rate_b = $common->get_rate($data->get_connection(),$bid_data->get_max_affordable_loss(), $match_id, $innings, $over,
                            $amount, 'b', $bid_data->get_bid_placed_status(), $bid_data->get_max_rate());
$rate_c = $common->get_rate($data->get_connection(),$bid_data->get_max_affordable_loss(),$match_id, $innings, $over,
                            $amount, 'c', $bid_data->get_bid_placed_status(), $bid_data->get_max_rate());
$expected_run = $expected_run = $common->get_expected_runs_from_over($data->get_connection(), $match_id, $innings, $over);
$run_a = $expected_run - $bid_data->get_lower_diff();
$run_aa = (int)$run_a;
$run_aaa = $run_aa + 1;
$run_b = $expected_run + $bid_data->get_upper_diff() + 1;
$run_bb = (int)$run_b;
$run_bbb = $run_bb + 1;
$amount_a = $common->rate_convertor($rate_a, $amount);
$amount_b = $common->rate_convertor($rate_b, $amount);
$amount_c = $common->rate_convertor($rate_c, $amount);
echo "Runs [0 to $run_aa] @ Rs$amount <=> Rs$amount_a"."&&".
    "Runs [$run_aaa to $run_bb] @ Rs$amount <=> Rs$amount_b"."&&".
    "Runs [$run_bbb or above] @ Rs$amount <=> Rs$amount_c"
?>
