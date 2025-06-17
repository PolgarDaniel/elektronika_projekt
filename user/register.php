<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="register-page">
        <div class='container'>
            <div class="form-box" id="register-form">
                <form action="thankyou.php" method="POST">
                    <h2>Register</h2>
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="role" required>
                        <option value="">--Select Role--</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit" name="Login">Login</button>
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validácia
    if (strlen($password) < 6) {
        header('Location: register.php?error=Password must be at least 6 characters');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: register.php?error=Invalid email format');
        exit;
    }

    if ($user->userExists($email)) {
        header('Location: register.php?error=User already exists');
        exit;
    }

    if ($user->register($name, $email, $password, $role)) {
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;
        $_SESSION['logged_in'] = true;
        header('Location: thankyou.php');
        exit;
    } else {
        header('Location: register.php?error=Could not create account');
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="register-page">
        <div class='container'>
            <div class="form-box" id="register-form">
                <form action="register.php" method="POST">
                    <h2>Register</h2>
                    <p style="color: red;"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="role" required>
                        <option value="">--Select Role--</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit" name="Register">Register</button>
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>