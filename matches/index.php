<?php
include "../data.php";
include "../Common.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if($common->is_user_logged_in()) {
    $result = $common->get_all_matches();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <title>Home</title>
        <link rel="icon" type="image/x-icon" href="../cricket.ico">
        <script src="../scripts.js"></script>
    </head>
    <body onload="fill_header();fill_profile();fill_footer();fill_controls();">
    <div id="header"></div>
    <div id="profile"></div>
    <div class="separator"></div>
    <div class="main_container">
        <div class="sub-title">Matches</div>
        <div class="gap"></div>
    <?php
    foreach($result as $match){ ?>
            <a class="match-button" href='match.php?match_id=<?php echo $match->match_id; ?>&series_id=<?php echo $match->series_id; ?>&match_name=<?php echo $match->match_name; ?>'><?php echo $match->match_name;?></a>
    <?php
    }
    ?>
    </div>
    <div class="separator"></div>
    <div id="main_controls"></div>
    <div class="separator"></div>
    <div id="footer"></div>
    </body>
    </html>
    <?php
} else {
        header("Location: ".$data->get_path());
}

?>

