<html lang="en">
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style.css?version=<?php echo time(); ?>">
    <link rel="icon" type="image/x-icon" href="../cricket.ico">
</head>
<body>
<?php
include '../data.php';
include "../Common.php";
$data = new Data();
$common = new Common();
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !$common->get_cookie($data->get_auth_cookie_name()) > 0) { ?>
        <div class="container">
            <div class="login form">
                <header>Login</header>
                <form action="login.php" method="POST">
                    <label for="phone">Phone Number:</label>
                    <input type="number" placeholder="Phone Number" id="phone" name="phone" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <a href="#">Forgot password?</a>
                    <input type="submit" class="button" value="Login">
                </form>
                <p class="error" id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></p>
                <a class="button wide login" href="../index.php">Go Home</a>
            </div>
        </div>
    <?php }
    else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$common->get_cookie($data->get_auth_cookie_name())) {
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $user = json_decode($common->get_user_from_db($phone, $password));
        $common->set_cookie($data->get_auth_cookie_name(), $user->ref_id);
        header("Location: ../matches/index.php");
    } else {
        header("Location: ".$data->get_path());
    }
?>
</body>
</html>

