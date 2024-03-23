<?php
include '../data.php';
include "../Common.php";
$data = new Data();
$common = new Common();

if ($common->get_auth_cookie($data->get_admin_auth_cookie_name()) > 0) {
    header("Location: ".$data->get_path()."admin/menu.php");
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
        <link rel="stylesheet" href="../style.css?version=101" type="text/css">
        <title>Admin</title>
    </head>
    <body>
    <div class="header"><h1>IPL - 2024</h1></div>
    <div class="row">
        <div class="col-3 col-s-3 menu">
            <ul>
                <li><a class="wide" href="admin_login.php">Login</a></li>
            </ul>
        </div>

        <div class="col-6 col-s-9">
            <h1>IPL 2024</h1>
            <p>Admin Panel</p>
        </div>

    </div>
    <div class="footer">
        <p>Created By: US.</p>
        <p>Contact Us On : </p>
    </div>
    </body>
    </html>
<?php } ?>