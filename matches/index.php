<?php
include "../data.php";
include "../Common.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if($common->is_user_logged_in()) {
    $result = $common->get_all_matches();
    $common->delete_cookie('match_id');
    $common->delete_cookie('series_id');
    $common->delete_cookie('match_name');
    $common->delete_cookie('current_over_id');
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
        <title>Matches - Home</title>
        <link rel="icon" type="image/x-icon" href="../cricket.ico">
        <script src="../scripts.js?version=<?php echo time(); ?>"></script>
    </head>
    <body onload="fill_header();fill_footer();">
    <div id="header"></div>
    <div class="main_container">
        <div class="sub-title">Matches</div>
    <?php
    foreach($result as $match){ ?>
            <a class="match-button <?php if(!$match->is_live){echo 'disabled';}?>" href='match.php?match_id=<?php echo $match->match_id; ?>&series_id=<?php echo $match->series_id; ?>&match_name=<?php echo $match->match_name; ?>'><?php echo $match->match_name;?></a>
    <?php
    }
    ?>
    </div>
    <div class="separator"></div>
    <div id="footer"></div>
    </body>
    </html>
    <?php
} else {
        header("Location: ".$data->get_path());
}

?>

