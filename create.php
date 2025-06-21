<?php
require_once 'classes/Database.php';
require_once 'classes/User.php';

$db = new Database();
$conn = $db->connect();
$user = new User($conn);

$name = $email = $password = $role = "";
$errorMessage = $successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST["user_name"]);
    $email = trim($_POST["user_email"]);
    $password = trim($_POST["user_password"]);
    $role = $_POST["user_role"];

    do {
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            $errorMessage = "All fields are required.";
            break;
        }

        if ($user->userExists($email)) {
            $errorMessage = "User with this email already exists.";
            break;
        }

        if ($user->register($name, $email, $password, $role)) {
            $successMessage = "User registered successfully.";
            $name = $email = $password = $role = "";
            header("Location: admin.php");
            exit;
        } else {
            $errorMessage = "Error while adding the user.";
        }

    } while (false);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create-User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>New User</h2>

        <?php 
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>
        <form method = "POST" action="">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="user_name" value = "<?php echo $name; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="user_email"  value = "<?php echo $email; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Password</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="user_password"  value = "<?php echo $password; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Role</label>
                <div class="col-sm-6">
                    <select name="user_role" class="form-select" required>
                        <option value="" <?php if ($role == "") echo "selected"; ?>>--Select Role--</option>
                        <option value="user" <?php if ($role == "user") echo "selected"; ?>>User</option>
                        <option value="admin" <?php if ($role == "admin") echo "selected"; ?>>Admin</option>
                    </select>
                </div>
            </div>

            <?php 
                if (!empty($successMessage)) {
                    echo "
                    <div class='row mb-3'>
                        <div class='offset-sm-3 col-sm-6'>
                            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>$successMessage</strong>
                               <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>
                        </div>
                    </div>
                    ";
                }
        ?>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="admin.php" role="button">Cancel</a>
                </div>
            </div>
        </form>

    </div>
    
</body>
</html>