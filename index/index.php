<?php
include '../data.php';
include "../Common.php";
$data = new Data();
$common = new Common();

if ($common->is_all_cookies_available([$data->get_auth_cookie_name()])) {
    header("Location: ".$data->get_path()."matches/index.php");
} else {
    $common->delete_cookies();
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
<body onload="fill_header();fill_footer();">
<div id="header"></div>
<div class="main_container">
    <a class="button" href="../login/login.php">Login</a>
    <a class="button" href="../register/register.php">Register</a>
</div>
<div class="separator"></div>
<div class="main_container">
    <div class="title">IPL 2024</div>
    <p>The 2024 Indian Premier League (also known as TATA IPL 2024) will be the 17th edition of the Indian Premier League, organised by the Board of Control for Cricket in India. The tournament will feature ten teams and will be held from 22 March to 26 May 2024.</p>
    <p>Chennai Super Kings are the defending champions, having won their fifth title during the previous season beating Gujarat Titans.</p>
</div>
<div class="separator"></div>
<div class="main_container">
    <div class="main_container">
        <div class="sub-title">What?</div>
        <p>Indian Premier League or IPL is a franchise Twenty20 cricket league in India, organised by the Board of Control for Cricket in India.</p>
    </div>
    <div class="main_container">
        <div class="sub-title">Where?</div>
        <p>The league stage will be played at 12 stadiums in India.</p>
    </div>
    <div class="main_container">
        <div class="sub-title">How?</div>
        <p>It is a game of Cricket played by mens and will be played in a T20 format.</p>
    </div>
</div>
<div class="separator"></div>
<div id="footer"></div>
</body>
</html>
<?php } ?>