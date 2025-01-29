<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z91TWPR0DM"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-Z91TWPR0DM');
    </script>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
    <script src="../scripts.js?version=<?php echo time(); ?>"></script>
</head>

<?php
include '../data.php';
include "../Common.php";
$data = new Data();
$common = new Common($data->get_path(), $data->get_amazon_api_endpoint());
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !$common->is_user_logged_in() && strlen($common->get_cookie('user_type')) == 0) {
    ?>
    <body onload="fill_header();fill_footer();">
        <div id="header"></div>
        <div class="main_container">
            <div class="sub-title">Login</div>
            <form action="login.php" method="POST">
                <label class="label" for="phone">Phone Number:</label>
                <input type="number" placeholder="Phone Number" id="phone" name="phone" required>
                <div class="gap"></div>
                <label class="label" for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <input type="submit" class="button" value="Login">
            </form>
            <p class="error" id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></p>
            <a class="button" href="#">Forgot password?</a>
            <a class="button" href="../index/index.php">Go Home</a>
        </div>
<?php }
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$common->is_user_logged_in()) {
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $user = $common->get_user_from_db($phone, $password);
    if(isset($user->error))
        header("Location: ../login/login.php?msg=" . $user->error);
    else {
        if($user->status == "active"){
                $common->set_cookie('user_ref_id', $user->ref_id);
                $common->set_cookie('user_type', $user->type);
                $common->set_cookie('fname', $user->fname);
                $common->set_cookie('lname', $user->lname);
                header("Location: ../matches/index.php");
        } else { ?>
            <body onload="fill_header();fill_footer();fill_account_status('<?php echo $user->status?>');">
            <div id="header"></div>
            <div id="account_status"></div>
            <div id="footer"></div>
            <?php
        }
    }
} else {
    if($common->get_cookie('user_type') == 'pending') { ?>
        <body onload="fill_header();fill_footer();fill_account_status('pending');">
            <div id="header"></div>
            <div id="account_status"></div>
            <div id="footer"></div>
        <?php } else {
            header("Location: ../matches/index.php");
        }
}
?>
        <div id="footer"></div>
</body>
</html>

