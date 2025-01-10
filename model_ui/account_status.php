<html>
<head>
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
</head>
<?php
include "../Common.php";
include "../data.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
$status = $_GET['status'];
$ref_id = $common->get_cookie($data->get_auth_cookie_name());
?>
<div class="account_status_container"><?php
    if($status == "pending"){?>
        <div class="title"><span>Account pending verification</span></div>
        <div class="separator"></div>
        <div class="account_status">
        <span>To Activate: Send Text/WhatsApp "ACTIVATE <?php echo $ref_id; ?>" from your registered mobile number to +91-111111111  </span>
        </div>
        <div class="gap"></div>
    <?php }elseif($status == "blocked"){?>
    <div class="account_status_container">
        <div class="title"><span>Account temporary blocked</span></div>
        <div class="separator"></div>
        <div class="account_status">
            <span>Your account is temporary blocked contact admin</span>
        </div>
        <div class="gap"></div>
    <?php } ?>
    </div>
</html>
