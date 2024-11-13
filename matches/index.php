<?php
include "../data.php";
include "../Common.php";
$data = new Data();
$common = new Common();

if($common->get_auth_cookie($data->get_auth_cookie_name()) > 0) {
    $sql = "SELECT * FROM `matches`";
    $result = $common->get_all_matches();
setcookie('match_id', "", time() - (3600), "/");
setcookie('innings', "", time() - (3600), "/");
setcookie('overs', "", time() - (3600), "/");
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../style.css?version=<?php echo time(); ?>">
        <title>Home</title>
        <link rel="icon" type="image/x-icon" href="../cricket.ico">
        <style>
            body {
                background-color: #d7f6d6;
                background-size: cover;
            }
        </style>
    </head>
    <body>
        <div class="header"><h1>IPL - 2024 - Matches</h1></div>
    <?php
        $ref_id = $common->get_auth_cookie($data->get_auth_cookie_name());
        $wallet = $common->get_wallet_balance($data->get_connection(), $ref_id);
    ?>
        <div class="innings">
        <h1>Hi, <?php echo $common->get_user_name_from_ref_id($data->get_connection(), $ref_id)?></h1>
    <h1>Available Balance : <?php echo $wallet;?></h1>
        </div>
        <hr>
    <h2 class="success">Matches</h2>
    <?php
    $ch = "'";
    foreach($result as $match){ ?>
            <a class="live" href='match.php?match_id=<?php echo $match->match_id; ?>&series_id=<?php echo $match->series_id; ?>&match_name=<?php echo $match->match_name; ?>'><?php echo $match->match_name;?></a>
    <?php
    }
} else {
        header("Location: ".$data->get_path()."login/login.php");
}

?>
        <h1></h1>
        <hr>
        <h1></h1>
        <a class="wide" href="../logout.php">Logout</a>
        <h1></h1>
<div class="footer">
    <p>Created By: US.</p>
    <p>Contact Us On : </p>
</div>
    </body>
</html>
