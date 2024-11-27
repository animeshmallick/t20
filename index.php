<?php
include 'data.php';
include "Common.php";
$data = new Data();
$common = new Common();

if ($common->get_cookie($data->get_auth_cookie_name()) > 0) {
    header("Location: ".$data->get_path()."matches/index.php");
} else {
    $common->delete_cookies();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/style.css?version=<?php echo time(); ?>">
    <title>Home</title>
    <link rel="icon" type="image/x-icon" href="cricket.ico">
</head>
<body>
<div class="header"><h1>IPL - 2024</h1></div>
<div class="row">
    <div class="col-3 col-s-3 menu">
        <ul>
            <li><a class="wide" href="login/login.php">Login</a></li>
            <li><a class="wide" href="register/register.php">Register</a></li>
        </ul>
    </div>

    <div class="col-6 col-s-9">
        <h1>IPL 2024</h1>
        <p>The 2024 Indian Premier League (also known as TATA IPL 2024) will be the 17th edition of the Indian Premier League, organised by the Board of Control for Cricket in India. The tournament will feature ten teams and will be held from 22 March to 26 May 2024.</p>
        <p>Chennai Super Kings are the defending champions, having won their fifth title during the previous season beating Gujarat Titans.</p>
    </div>

    <div class="col-3 col-s-12">
        <div class="aside">
            <h2>What?</h2>
            <p>Indian Premier League or IPL is a franchise Twenty20 cricket league in India, organised by the Board of Control for Cricket in India.</p>
            <h2>Where?</h2>
            <p>The league stage will be played at 12 stadiums in India.</p>
            <h2>How?</h2>
            <p>It is a game of Cricket played by mens and will be played in a T20 format.</p>
        </div>
    </div>
</div>
<div class="footer">
    <p>Created By: US.</p>
    <p>Contact Us On : </p>
</div>
</body>
</html>
<?php } ?>