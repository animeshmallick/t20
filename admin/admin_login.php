<html lang="en">
<head>
    <title>Admin - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" href="../style.css?version=101" type="text/css">
</head>
<body>
<?php
include '../data.php';
include "../Common.php";
$data = new Data();
$common = new Common();
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !$common->get_auth_cookie($data->get_admin_auth_cookie_name()) > 0) { ?>
    <div class="container">
        <div class="login form">
            <header>Login</header>
            <form action="admin_login.php" method="POST">
                <label for="phone">Phone Number:</label>
                <input type="number" placeholder="Phone Number" id="phone" name="phone" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <a href="#">Forgot password?</a>
                <input type="submit" class="button" value="Login">
            </form>
            <p class="error" id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></p>
            <a class="button wide login" href="index.php">Go Home</a>
        </div>
    </div>
<?php }
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$common->get_auth_cookie($data->get_admin_auth_cookie_name())) {
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $data = new Data();
    $connection = $data->get_connection();
    if (is_valid_user($connection, $phone, $password, $data->get_user_active_status(), $data->get_path())) {
        $ref_id = get_user_ref_id($connection, $phone, $password);
        set_auth_cookie($data->get_admin_auth_cookie_name(), $ref_id);
        header("Location: ".$data->get_path()."admin/menu.php");
    }
    $connection->close();
} else {
    header("Location: ".$data->get_path()."admin/");
}

function is_valid_user($connection, $phone, $password, $status, $path): bool
{
    $sql = "Select * from `admin` where `phone`=$phone and `password`='$password'";
    $result = $connection->query($sql);

    if ($result->num_rows == 1)
            return TRUE;
    else
            header("Location: ".$path."verify/verification.php?q=".$phone);
    return FALSE;
}

function get_user_ref_id($connection, $phone, $password) {
    $sql = "Select * from `admin` where `phone`=$phone and `password`='$password'";
    $result = $connection->query($sql);
    if ($result->num_rows == 1) {
        return $result->fetch_assoc()['ref_id'];
    } else {
        return -1;
    }
}

function set_auth_cookie($name, $value): void
{
    setcookie($name, $value, time()+(43000), "/");
}
?>
</body>
</html>

