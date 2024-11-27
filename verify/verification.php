<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <title>Verification</title>
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
</head>
<body>
<div class="header"><h1>IPL - 2024 - Registration</h1></div>
<div class="row">
<?php
include '../data.php';
include "../Common.php";
$data = new Data();
$common = new Common();
if (isset($_GET['q']) && !$common->get_auth_cookie($data->get_auth_cookie_name())) {
    $phone = $_GET['q'];
    $sql = "Select * from `users` where `phone`=$phone";
    $result = $data->get_connection()->query($sql);
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();?>
        <div class="col-6 col-s-9">
            <h1>Hi, <?php echo $row['fname'];?></h1><br>
            <h2 class="error">Your Account is Not Active.</h2>
            <h3><?php echo "Please send a text/whatsappp message as \""?> <span class="error"> VERIFY <?php echo $row['ref_id'];?></span><?php echo "\" to ".$data->get_verification_phone_number()." from your registered mobile number ($phone)"; ?></h3>
            <a href="../index.php">Go Home</a>
        </div>
    <?php
    } else {
        echo "Invalid Phone Number";
    }
    $data->get_connection()->close();
} else {
    header("Location: ".$data->get_path()."matches/");
}
?>
</div>
<div class="footer">
    <p>Created By: US.</p>
    <p>Contact Us On : </p>
</div>
</body>
</html>
