<!DOCTYPE html>
<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$ref_user_id = $common->get_cookie($data->get_auth_cookie_name());
if($ref_user_id!=null) {
    $transactions = $common->get_all_transactions($ref_user_id);
}
else{
    header("Location: ".$data->get_path());
}
?>
<html lang="">
<head>
    <title>All Bids</title>
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <script src="../scripts.js"></script>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body onload="fill_header();fill_profile(); fill_controls();fill_footer()">
    <div id="header"></div>
    <div id="profile"></div>
    <div class="separator"></div>
    <div class="bid_container">
        <div class="bids_heading">ALL TRANSACTIONS</div>
        <div class="bid_container">
            <div class ="title">ALL RECHARGES</div>
            <table>
                <thead>
                <tr>
                    <th>REF ID</th>
                    <th>FROM</th>
                    <th>ID</th>
                    <th>AMOUNT</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($transactions as $bids) :
                    $flag = "others";
                    if ($bids->amount<0)
                        $flag="loss";
                    if($bids->amount>0)
                        $flag="win";
                    ?>
                    <tr class="row_<?php echo $flag;?>">
                        <td><?php echo $bids->to_user_id; ?></td>
                        <td><?php echo $bids->from; ?></td>
                        <td><?php echo $bids->id; ?></td>
                        <td><?php echo $bids->amount; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="separator"></div>
    <div id="main_controls"></div>
    <div id="footer"></div>
</body>
</html>