<?php 
session_start();

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php?message=logged out successfully');
    exit;
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
      include 'templates/header.php'
     ?>
        <div class="account">
            <div class="account-container">
                <h1 class = "account-h1">Welcome back, <?php if(isset($_SESSION['user_name'])) {echo $_SESSION['user_name']; }?><span id="userName"></span>!</h1>
                <p class = "account-p">Your email: <?php if(isset($_SESSION['user_email'])) {echo $_SESSION['user_email']; }?><span id="userEmail"></span></p>
                <a href="account.php?logout=1" id="logout-btn" class="account-btn">Log out</a>
            </div>
        </div>
</body>
</html>
