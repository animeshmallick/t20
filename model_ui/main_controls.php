<html>
<head>
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
</head>
<div class="main-control-container">
    <div class="control-title">Controls</div>
    <div class="main_container">
        <?php
            include '../data.php';
            include "../Common.php";
            $data = new Data();
            $common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
            if($common->is_user_an_admin($common->get_cookie($data->get_auth_cookie_name())) ||
                $common->is_user_an_agent($common->get_cookie($data->get_auth_cookie_name()))){ ?>
                <a class="button" href="../admin/activate_user.php">Activate New User</a>
                <a class="button" href="../admin/recharge.php">Recharge User Account</a>
            <?php }
        ?>
        <a class="control-button" href="../views/show_all_bids.php">Show My Bids</a>
        <a class="control-button" href="../index/index.php">Go Home</a>
        <div class="small-separator"></div>
        <a class="control-button" href="../logout.php">Logout</a>
    </div>
</div>
</html>