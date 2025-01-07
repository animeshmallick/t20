<html>
<head>
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
</head>
<?php
include "../data.php";
include "../Common.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$user = $common->get_user_from_ref_id($common->get_cookie($data->get_auth_cookie_name()));
$balance = $common->get_user_balance($user->ref_id);
?>
<div class="play-container" style="margin: 0.2rem 0.5rem">
    <div class="title">Profile</div>
    <span class="profile">Hi, <?php echo $user->fname . " " . $user->lname; ?>.</span>
    <span class="profile">Your available balance is ₹<?php echo $balance;?></span>
</div>
</html>