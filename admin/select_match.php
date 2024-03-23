<?php
include "../data.php";
include "../Common.php";
$data = new Data();
$common = new Common();

$sql = "SELECT DISTINCT `match_id` FROM `scorecard`";
$result = (new Data())->get_connection()->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style.css?version=<?php echo time(); ?>">
    <title>Admin - Match</title>
    <style>
        body {
            background-color: #d7f6d6;
            background-size: cover;
        }
    </style>
</head>
<body>
<div class="header"><h1>IPL - 2024 - Matches</h1></div>
<hr>
<h2 class="success">Matches</h2>
<?php
for ($i=0; $row = $result->fetch_assoc();$i++){?>
    <a href="over_status.php?match_id=<?php echo $row['match_id']; ?>"><?php echo $common->get_match_name($data->get_connection(), $row['match_id']);?></a>
    <?php } ?>
<h1></h1>
<hr>
<h1></h1>
<h1></h1>
<div class="footer">
    <p>Created By: US.</p>
    <p>Contact Us On : </p>
</div>
</body>
</html>
