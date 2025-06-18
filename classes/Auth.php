<?php
class Auth {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT user_id, user_name, user_email, user_password, user_role FROM users WHERE user_email = ? LIMIT 1");
        $stmt->bind_param('s', $email);

        if ($stmt->execute()) {
            $stmt->bind_result($user_id, $user_name, $user_email, $hashed_password, $user_role);
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $user_name;
                    $_SESSION['user_email'] = $user_email;
                    $_SESSION['user_role'] = $user_role;
                    $_SESSION['logged_in'] = true;

                    return true; // úspešné prihlásenie
                }
            }
        }

        return false; // neúspešné prihlásenie
    }
}
