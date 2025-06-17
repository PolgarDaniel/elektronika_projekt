<?php
class User {
    private $conn;

    public function __construct($db_conn) {
        $this->conn = $db_conn;
    }

    public function userExists($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function register($name, $email, $password, $role) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (user_name, user_email, user_password, user_role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
