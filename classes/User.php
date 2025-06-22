<?php
class User {
    private $conn;

    // Konstruktor prijima databazove pripojenie
    public function __construct($db_conn) {
        $this->conn = $db_conn;
    }

    // Overi, ci uzivatel s danym emailom existuje
    public function userExists($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    // Zaregistruje noveho uzivatela do databazy
    public function register($name, $email, $password, $role) {
        // Zahesluje heslo
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (user_name, user_email, user_password, user_role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        // Ak sa vlozenie podarilo, vrat true
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Aktualizuje udaje uzivatela, ak je zadane nove heslo, heslo sa tiez aktualizuje
    public function updateUser($user_id, $name, $email, $password, $role) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE users SET user_name = ?, user_email = ?, user_password = ?, user_role = ? WHERE user_id = ?");
            $stmt->bind_param("ssssi", $name, $email, $hashed_password, $role, $user_id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET user_name = ?, user_email = ?, user_role = ? WHERE user_id = ?");
            $stmt->bind_param("sssi", $name, $email, $role, $user_id);
        }

        // Vykona sa aktualizacia a vrati sa true/false
        return $stmt->execute();
    }

    // Vrati informacie o uzivatelovi podla jeho ID
    public function getUserById($user_id) {
        $stmt = $this->conn->prepare("SELECT user_id, user_name, user_email, user_role FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    // Odstrani uzivatela z databazy podla jeho ID
    public function delete($id) {
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error); // Vyhodi chybu, ak sa priprava nepodari
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Execute failed: " . $stmt->error); // Vyhodi chybu, ak sa vykonanie nepodari
        }
    }
}

