<?php
session_start();

// Overenie, ci je uzivatel prihlaseny a ma rolu admin
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

    <a href="index.php" class="btn btn-secondary" style="position: fixed; top: 10px; left: 10px; z-index: 1000;">
        Back to Home
    </a>

    <div class="container my-5">
        <h2>List of Users</h2>
        <a class="btn btn-primary" href="create.php" role="button">New User</a>
        <br>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Nacitanie databazy
                    require_once 'classes/Database.php';
                    $db = new Database();
                    $conn = $db->connect();

                    // Ziskanie vsetkych uzivatelov z databazy
                    $sql = "SELECT * FROM users";
                    $result = $conn->query($sql);

                    if (!$result){
                        die("Invalid query: " . $conn->error);
                    }

                    // Vypis kazdeho uzivatela do tabulky
                    while($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>$row[user_id]</td>
                            <td>$row[user_name]</td>
                            <td>$row[user_email]</td>
                            <td>$row[user_role]</td>
                            <td>
                                <a class='btn btn-primary btn-sm' href='edit.php?id=$row[user_id]'>Edit</a>
                                <a class='btn btn-danger btn-sm' href='delete.php?id=$row[user_id]'>Delete</a>
                            </td>
                        </tr>
                        ";
                    }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
