<html lang="en">
<head>
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style.css?version=101">
    <script src="../scripts.js"></script>
</head>
<?php
include '../data.php';
include "../model/user.php";
include "../Common.php";
$data = new Data();
$common = new Common();
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !$common->get_auth_cookie($data->get_auth_cookie_name())) { ?>
    <body>
    <div class="container">
        <div class="login form">
            <header>Register</header>
            <form action="register.php" method="POST" onsubmit="return validate_register_form()" name="register_form">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" placeholder="Your First Name" required>
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" placeholder="Your Last Name">
                <label for="phone">Phone Number:</label>
                <input type="number" id="phone" name="phone" placeholder="9998887776" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <label for="ref_id">Your Reference ID (unique):</label>
                <input type="number" id="ref_id" name="ref_id" value="<?php echo get_unique_ref_id($data->get_connection()); ?>" readonly required>
                <label for="parent_ref_id">Your Clients Reference ID :</label>
                <input type="number" id="parent_ref_id" name="parent_ref_id">
                <input type="submit" class="button" value="Register">
            </form>
            <p class="error" id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></p>
            <a class="button wide login" href="../index.php">Go Home</a>
        </div>
    </div>
    </body>
<?php }

else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$common->get_auth_cookie($data->get_auth_cookie_name())) {
    $user = new User($_POST['fname'], $_POST['lname'], $_POST['phone'], $_POST['password']);
    $connection = $data->get_connection();
    $parent_ref_id = "000000000";
    if(isset($_POST['ref_id']))
        $parent_ref_id = $_POST['ref_id'];

    if(does_user_exist($connection, $user->get_phone())){
        header("Location: ".$data->get_path()."register/register.php?msg=User Already Exists. Try again with a another phone number.");
    }
    else {
        $ref_id = $_POST['ref_id'];
        $initial_bonus = $data->get_initial_wallet_balance();
        if (!is_valid_referral($connection, $ref_id))
            $parent_ref_id = "000000000";

        insert_user($connection, $user->get_fname(), $user->get_lname(), $user->get_phone(),
            $user->get_password(), $initial_bonus, $data->get_user_pending_status(),
            $ref_id, $parent_ref_id, $data->get_path());
        redirect_to_verification_page($user->get_phone(), $data->get_path());
    }
    $connection->close();
} else {
    header("Location: ".$data->get_path()."matches/");
}

function is_valid_referral($connection, $ref_id){
    $sql = "Select `ref_id` from `users` where `ref_id`=$ref_id";
    if ($ref_id == "")
        return false;
    return $connection -> query($sql) -> num_rows == 1;
}

function does_user_exist($connection, $phone){
    $sql = "Select `alias` from `users` where `phone`=$phone";
    return $connection -> query($sql) -> num_rows != 0;
}

function insert_user($connection, $fname, $lname, $phone, $password, $wallet, $status, $ref_id, $parent_ref_id, $path) {
    $sql = "INSERT INTO `users` (fname, lname, phone, password, wallet, status, ref_id, parent_ref_id) VALUES ('$fname', '$lname', '$phone', '$password', '$wallet', '$status', '$ref_id', '$parent_ref_id')";
    if (!$connection->query($sql) === TRUE) {
        header("Location: ".$path."register/register.php?msg=Something went wrong. Please try again.");
    }
}

function get_unique_ref_id($connection) {
    $ref_id = mt_rand(10000000, 99999999);
    $sql = "Select `ref_id` from `users` where `ref_id`=$ref_id";
    while($connection->query($sql)->num_rows != 0){
        $ref_id = mt_rand(10000000, 99999999);
    }
    return $ref_id;
}

function redirect_to_verification_page($phone, $path){
    header("Location: ".$path."verify/verification.php?q=$phone");
}

?>
</html>