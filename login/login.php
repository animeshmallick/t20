<html lang="en">
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
</head>
<body>
<?php
include '../data.php';
include "../Common.php";
$data = new Data();
$common = new Common();
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !$common->is_valid_user($data->get_auth_cookie_name())) { ?>
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
    else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$common->is_valid_user($data->get_auth_cookie_name())) {
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $user = json_decode($common->get_user_from_db($phone, $password));
        if(isset($user->error))
            header("Location: ../login/login.php?msg=" . $user->error);
        else {
            $common->set_cookie($data->get_auth_cookie_name(), $user->ref_id);
            header("Location: ../matches/index.php");
        }
    } else {
        header("Location: ".$data->get_path());
    }
?>
</body>
</html>

