<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thankyou</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"]== "POST") {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);

        echo "<div class='thankyou-page'>";
        echo "<div class='message'>";
        echo "<h2>Successful registration</h2>";
        echo "<h2 class='text'><strong>Name:</strong> $name </h2><br>";
        echo "<h2 class= 'text'><strong>Email:</strong> $email </h2>";
        echo "</div>";
        echo "</div>";
    }else {
        header("Location: index.html");
        exit;
    }
    ?>
</body>
</html>