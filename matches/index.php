<?php
include "../data.php";
include "../Common.php";
$data = new Data();
$common = new Common();

if($common->get_cookie($data->get_auth_cookie_name()) > 0) {
    $result = $common->get_all_matches();
    $common->delete_cookies();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
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
        $ref_id = $common->get_cookie($data->get_auth_cookie_name());
    ?>
    <h2 class="success">Matches</h2>
    <?php
    $ch = "'";
    foreach($result as $match){ ?>
            <a class="live" href='match.php?match_id=<?php echo $match->match_id; ?>&series_id=<?php echo $match->series_id; ?>&match_name=<?php echo $match->match_name; ?>'><?php echo $match->match_name;?></a>
    <?php
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
    <?php
} else {
        header("Location: ".$data->get_path()."login/login.php");
}

?>

