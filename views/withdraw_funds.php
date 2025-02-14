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
    <title>Withdraw funds</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <title>Home</title>
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <script src="../scripts.js?version=<?php echo time(); ?>"></script>
</head>

<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$ref_user_id = $common->get_cookie($data->get_auth_cookie_name());
$all_withdrawals=$common->get_all_withdraws($ref_user_id);
usort($all_withdrawals, function($a, $b) {
    return strcmp($a->time,$b->time) * -1;
});
if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $amount=$_POST['amount'];
        if($amount<1){
            header("Location: ../views/withdraw_funds.php?msg=" . "Please enter a valid amount");
        }
        else{
            $withdraw = $common->withdraw_amount($ref_user_id, $amount);
            header("Location: ../views/withdraw_funds.php?msg=" . $withdraw->status);
        }
}
?>
<body onload="fill_header();fill_balance();fill_profile();fill_footer();">
<div id="header"></div>
<div id="profile"></div>
<div class="separator"></div>
<div class="main_container">
    <div class="sub-title">WITHDRAW AMOUNT</div>
    <form action="withdraw_funds.php" method="POST">
        <label class="label" for="amount">Amount:</label>
        <input type="number" placeholder="Enter Amount" id="amount" name="amount" required>
        <div class="gap"></div>
        <input type="submit" class="button" value="Withdraw">
    </form>
    <p class="error" id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></p>
    <p class="gap"></p>
    <div>
        <div class="sub-title">WITHDRAWAL LIST</div>
        <div class="gap"></div>
        <table>
            <thead>
            <tr>
                <th>TIME</th>
                <th>AMOUNT</th>
                <th>STATUS</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($all_withdrawals as $withdraw) :
                $flag = "others";
                if($withdraw->status=="placed"){
                    $flag="placed";
                }
                else if($withdraw->status=="settled"){
                    $flag="win";
                }
                ?>
                <tr class="row_<?php echo $flag;?>">
                    <td><?php echo $withdraw->time; ?></td>
                    <td><?php echo $withdraw->amount; ?></td>
                    <td><?php echo $withdraw->status; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>
<div class="separator"></div>
<div id="footer"><</div>
</body>