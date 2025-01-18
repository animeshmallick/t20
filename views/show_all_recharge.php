<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$ref_user_id = $common->get_cookie($data->get_auth_cookie_name());
if($common->is_user_logged_in()) {
    $transactions = $common->get_all_transactions($ref_user_id);
    usort($transactions, function($a, $b) {
        return strcmp($a->time,$b->time) * -1;
    });

    $all_transactions = array();
    foreach($transactions as $transaction) {
        if($common->get_cookie("user_type") == 'user') {
            if (!str_contains($transaction->from, 'bidder')) {
                $all_transactions[] = $transaction;
            }
        }else{
            $all_transactions[] = $transaction;
        }
    }
}
else{
    header("Location: ".$data->get_path());
}
?>
<html lang="">
<head>
    <title>All My Transactions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <title>Home</title>
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <script src="../scripts.js?version=<?php echo time(); ?>"></script>
</head>
<body onload="fill_header();fill_balance();fill_profile();fill_footer()">
    <div id="header"></div>
    <div id="profile"></div>
    <div class="separator"></div>
    <div class="bid_container">
        <div class="bids_heading">My Wallet Transaction</div>
        <div class="bid_container">
            <div class ="title">All Transactions</div>
            <table>
                <thead>
                <tr>
                    <th>Time</th>
                    <th>From</th>
                    <th>Recharge ID</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($all_transactions as $trans) :
                    $flag = "others";
                    if ($trans->amount<0)
                        $flag="loss";
                        $flag1=-1;
                    if($trans->amount>0)
                        $flag="win";
                    ?>
                    <tr class="row_<?php echo $flag;?>">
                        <td><?php if(property_exists($trans, 'time')){echo $trans->time;}?></td>
                        <td>
                            <?php
                            if(str_contains($trans->from, "bidder_return")){
                                echo "Bid Settled";
                            } else if(str_contains($trans->from, "bidder_refund_agent")){
                                echo "Agent Refund";
                            } else if(str_contains($trans->from, "bidder")){
                                echo "Bid Placed";
                            } else{
                                echo "Withdraw";
                            }
                            ?></td>
                        <td><?php echo $trans->id; ?></td>
                        <td><?php
                            if($trans->amount<0){
                                echo (int)($trans->amount*-1); }
                            else {
                                echo (int)$trans->amount;
                            }?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="separator"></div>
    <div id="footer"></div>
</body>
</html>