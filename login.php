<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Auth.php';

if (isset($_SESSION['logged_in'])) {
    header('Location: account.php');
    exit;
}

// získať pripojenie k databáze
$db = new Database();
$conn = $db->connect();

// vytvoriť objekt autentifikácie
$auth = new Auth($conn);

// ak bol formulár odoslaný
if (isset($_POST['Login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($auth->login($email, $password)) {
        header('Location: account.php?message=logged in successfully');
        exit;
    } else {
        header('Location: login.php?error=Invalid email or password');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
      include 'templates/header.php'
     ?>
<div class="login-page">
    <div class='login-container'>
        <div class="form-box" id="login-form">
            <form action="login.php" method="POST">
                <h2>Login</h2>
                <p style= "color:red"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="Login">Login</button>
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>