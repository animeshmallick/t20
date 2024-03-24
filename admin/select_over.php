<?php
include "../Common.php";
include "../data.php";
$common = new Common();
$data = new Data();
$match_id = $_GET['match_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style.css?version=<?php echo time(); ?>">
    <title>Admin - Overs</title>
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <style>
        body {
            background-color: #000000;
            background-size: cover;
        }
    </style>
</head>
<body>
<div class="header"><h1>IPL - 2024</h1></div>
<div class="sub-header"><h1><?php echo $common->get_match_name($data->get_connection(), $match_id)?></h1></div>
<div class="row">
    <div class="innings">
        <?php
        for ($i = 1;$i<=20;$i++){ ?>
            <a href="over_status.php?match_id=<?php echo $match_id;?>&innings=1&over=<?php echo $i; ?>">Innings 1 : Over <?php echo $i; ?></a>
        <?php }
        ?>
    </div>
    <div class="innings">
        <?php
        for ($i = 1;$i<=20;$i++){ ?>
            <a href="over_status.php?match_id=<?php echo $match_id;?>&innings=2&over=<?php echo $i; ?>">Innings 2 : Over <?php echo $i; ?></a>
        <?php }
        ?>
    </div>
</div>
<a class="button wide login" href="../index.php">Go Home</a>
<hr>
<div class="footer">
    <p>Created By: US.</p>
    <p>Contact Us On : </p>
</div>
</body>
</html>
