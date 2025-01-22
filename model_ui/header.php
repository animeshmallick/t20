<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <script src="../scripts.js?version=<?php echo time(); ?>">
    </script>
</head>
<body onload="fill_balance()">
    <div class="title-container">
        <a href="javascript:void(0)" id='side-bar-icon' class="nav" onclick="w3_open()" style="border-radius: 1rem;">&#9816;</a>
        <div style="margin: 0; padding: 0">
            <div style="margin: 0; padding: 0"><span class="title" onclick="redirect_to_home()" style="cursor: grab">CricketT20</span></div>
            <?php
            include "../data.php";
            include "../Common.php";
            $data = new Data();
            $common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
            if ($common->is_user_logged_in()){
                ?>
                <div class="profile" onclick="redirect_to_home()" style="cursor: grab">
                    <div>&nbsp;Hi, <?php echo $common->get_cookie('fname') . " " . $common->get_cookie('lname'); ?>.</div>
                    <div style="font-size: 0.8rem" id="balance">Getting Balance</div>
                </div>
            <?php } ?>
        </div>

    <nav class="w3-sidebar w3-bar-block w3-animate-left w3-top" style="font-size: 1rem;z-index:3;width:75%;display:none;left:0;" id="side-bar-container">
            <div class="bid_container">
                <div class="title">Controls</div>
                <div class="scorecard-container">
                    <a href="../index/index.php" onclick="w3_close()" class="w3-mobile w3-bar-item w3-center nav_item" style="font-weight: bolder;">Home</a>
                    <div class="separator"></div>
                    <?php if ($common->get_cookie('match_id') != "" && $common->get_cookie('series_id') != "" && $common->is_user_logged_in()){ ?>
                    <a href="../views/show_all_bids.php" onclick="w3_close()" class="w3-mobile w3-bar-item w3-center nav_item">Show My Bids</a>
                    <?php } ?>
                    <?php if($common->is_user_logged_in()){ ?>
                    <a href="../views/show_all_recharge.php" onclick="w3_close()" class="w3-mobile w3-bar-item w3-center nav_item">My Transactions</a>
                    <a href="../views/withdraw_funds.php" onclick="w3_close()" class="w3-mobile w3-bar-item w3-center nav_item">Withdraw</a>
                    <?php } ?>
                    <div class="separator"></div>
                    <a href="../logout.php" onclick="w3_close()" class="w3-mobile w3-bar-item w3-center nav_item" style="font-weight: bolder;">Logout</a>
                </div>
                <?php if ($common->is_user_an_admin()){ ?>
                        <div class="scorecard-container">
                            <div class="title">Admins Only</div>
                            <?php if ($common->get_cookie('match_id') != "" && $common->get_cookie('series_id') != ""){ ?>
                                <a href="../views/admin_match_dashboard.php" onclick="w3_close()" class="w3-mobile w3-bar-item w3-center nav_item">Match Dashboard</a>
                            <?php } ?>
                            <a href="../admin/activate_user.php" onclick="w3_close()" class="w3-mobile w3-bar-item w3-center nav_item">Activate New User</a>
                            <a href="../admin/recharge.php" onclick="w3_close()" class="w3-mobile w3-bar-item w3-center nav_item">Recharge User Account</a>
                        </div>
                    <div class="separator"></div>
                <?php } ?>
                <?php if ($common->is_user_an_agent()){ ?>
                    <div class="scorecard-container">
                        <div class="title">Agents Only</div>
                        <a href="../admin/recharge.php" onclick="w3_close()" class="w3-mobile w3-bar-item w3-center nav_item">Transfer Balance</a>
                    </div>
                    <div class="separator"></div>
                <?php } ?>
                <div class="scorecard-container">
                    <a href="javascript:void(0)" onclick="w3_close()" class="w3-mobile w3-bar-item w3-center nav_item" style="font-weight: bolder;">Close</a>
                </div>
            </div>
        </nav>
</div>
</body>
</html>