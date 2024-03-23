<html lang="en">
<head>
    <title>Admin- Run & Over</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style.css?version=<?php echo time(); ?>">
</head>
<body>
<table border="1px">
    <thead>
    <tr>
        <th scope="col">Match ID</th>
        <th scope="col">Innings</th>
        <th scope="col">Ball</th>
        <th scope="col">Runs</th>
        <th scope="col">Wickets</th>
    </tr>
    </thead>
    <tbody>
<?php
include "../data.php";

$match_id = (int)$_GET['match_id'];
$ball_by_ball = False;
$div = 6;
if (isset($_GET['ball']) && $_GET['ball'] == 'ball')
    $div = 1;

$data = new Data();
$sql = "SELECT * FROM `scorecard` WHERE `match_id`=$match_id";

$result = $data->get_connection()->query($sql);
$prev_innings = 1;
while($row = $result->fetch_assoc()){
    $ball_id = (int)$row['ball_id'];
    if($ball_id % $div == 0) { ?>
            <tr style="text-align: center">
                <td><?php echo $row['match_id'];?></td>
                <td><?php echo $row['innings'];?></td>
                <td><?php echo $row['ball_id'];?></td>
                <td><?php echo $row['runs'];?></td>
                <td><?php echo $row['wickets'];?></td>
            </tr>
        <?php
    }
}?>
    </tbody>
</table>
</body>
</html>
