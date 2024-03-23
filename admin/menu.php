<?php
include '../data.php';
include "../Common.php";
$data = new Data();
$common = new Common();

if ($common->get_auth_cookie($data->get_admin_auth_cookie_name()) <= 0) {
    header("Location: ".$data->get_path()."admin/index.php");
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" href="../style.css?version=<?php echo time(); ?>" type="text/css">
    <title>Admin - Menu</title>
</head>
<body>
<div class="header"><h1>IPL - 2024</h1></div>
<div class="row">
    <div class="col-3 col-s-3 menu">
        <ul>
            <li><a class="wide" href="recharge.php">Recharge</a></li>
            <li><a class="wide" href="run_over.php">ScoreCard</a></li>
            <li><a class="wide" href="verify_new_user.php">Activate New User</a></li>
            <li><a class="wide" href="select_match.php">Select Match</a></li>
        </ul>
    </div>
</div>
<div class="header"><h1>Admin Panel</h1></div>
</body>
</html>

<?php } ?>