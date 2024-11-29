<html>
<head>
    <link rel="stylesheet" type="text/css" href="../styles/style.css?version=<?php echo time(); ?>">
</head>
<div class="main_container">
    <div class="sub-title">Login</div>
    <form action="../login/login.php" method="POST">
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