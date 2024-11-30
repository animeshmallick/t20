<html>
<head>
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
</head>
<?php
$status = $_GET['status'];
?>
<div class="account_status_container"><?php
    if($status == "pending"){?>
        <div class="title"><span>Account pending verification</span></div>
        <div class="separator"></div>
        <div class="account_status">
        <span>To Activate: Send Text/WhatsApp "ACTIVATE OTP" from your registered mobile number to +91-111111111  </span>
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
