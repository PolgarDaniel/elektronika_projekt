<?php
session_start();

require_once 'classes/Database.php';
require_once 'classes/User.php';

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

// Ak je pouzivatel uz prihlaseny, presmeruj ho podla roly
if (isset($_SESSION['logged_in'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: account.php');
    }
    exit;
}

// Inicializuj premennu pre chybovu spravu
$errorMessage = '';

// Ak prisiel formular
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Over, ci heslo ma aspon 6 znakov
    if (strlen($password) < 6) {
        $errorMessage = "Password must be at least 6 characters";
    }
    // Over format emailu
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format";
    }
    // Skontroluj, ci uz dany email nie je registrovany
    elseif ($user->userExists($email)) {
        $errorMessage = "User already exists";
    }
    else {
        // Pokus sa zaregistrovat pouzivatela
        if ($user->register($name, $email, $password, $role)) {
            // Uloz do session zakladne info o pouzivatelovi a stav prihlasenia
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = $role;
            $_SESSION['logged_in'] = true;

            // Podla roly presmeruj pouzivatela na spravnu stranku
            if ($role === 'admin') {
                header('Location: admin.php?message=Registered as admin');
            } else {
                header('Location: account.php?message=Registered successfully');
            }
            exit;
        } else {
            $errorMessage = "Could not create account";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
      include 'templates/header.php'
     ?>
    <div class="register-page">
        <div class='register-container'>
            <div class="form-box" id="register-form">
                <form action="register.php" method="POST">
                    <h2>Register</h2>
                    <?php if (!empty($errorMessage)) : ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
                    <?php endif; ?>
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