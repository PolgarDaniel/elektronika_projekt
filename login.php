<?php 
session_start();
require_once 'classes/Database.php';
require_once 'classes/Auth.php';
require_once 'classes/User.php';

if (isset($_SESSION['logged_in'])) {
    header('Location: account.php');
    exit;
}

$db = new Database();
$conn = $db->connect();

$auth = new Auth($conn);
$user = new User($conn);

$errorMessage = "";

if (isset($_POST['Login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Najprv over, či užívateľ existuje
    if (!$user->userExists($email)) {
        $errorMessage = "User doesn't exist.";
    } else {
        // Pokus o prihlásenie
        if ($auth->login($email, $password)) {
            // Získaj rolu používateľa
            $sql = "SELECT user_role FROM users WHERE user_email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $userData = $result->fetch_assoc();

            if ($userData) {
                $_SESSION['user_role'] = $userData['user_role'];

                if ($userData['user_role'] === 'admin') {
                    header('Location: admin.php?message=Logged in as admin');
                    exit;
                } else {
                    header('Location: account.php?message=Logged in successfully');
                    exit;
                }
            }
        } else {
            $errorMessage = "Wrong password.";
        }
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
                <?php if (!empty($errorMessage)) : ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
                <?php endif; ?>
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