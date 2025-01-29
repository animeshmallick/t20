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
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z91TWPR0DM"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-Z91TWPR0DM');
    </script>
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
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($all_transactions as $trans) :
                    $flag = "others";
                    if ($trans->amount<0 || str_contains($trans->from,"withdraw"))
                        $flag="loss";
                    if($trans->amount>0)
                        $flag="win";
                    ?>
                    <tr class="row_<?php echo $flag;?>">
                        <td style="width: 45%;"><?php if(property_exists($trans, 'time')){echo $trans->time;}?></td>
                        <td>
                            <?php
                            if(str_contains($trans->from, "bidder_return")){
                                echo "Bid Settled";
                            } else if(str_contains($trans->from, "bidder_refund_agent")){
                                echo "Agent Refund";
                            } else if(str_contains($trans->from, "bidder")){
                                echo "Bid Placed";
                            }else if(str_contains($trans->from, "withdraw")){
                                echo "Withdraw";
                            }else{
                                echo "Recharge";
                            }
                            ?></td>
                        <td><?php
                            if($trans->amount<0){
                                echo '&#8377;'.(int)($trans->amount*-1); }
                            else {
                                echo '&#8377;'.(int)$trans->amount;
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