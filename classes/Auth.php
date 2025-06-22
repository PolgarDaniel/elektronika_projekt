<?php
class Auth {
    private $conn;

    // Konstruktor pripoji objekt k databazovemu spojeniu
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Funkcia login sa pokusi prihlasit pouzivatela na zaklade emailu a hesla
    public function login($email, $password) {
        // Pripraveny SQL dopyt pre ziskanie informacii o pouzivatelovi
        $stmt = $this->conn->prepare("SELECT user_id, user_name, user_email, user_password, user_role FROM users WHERE user_email = ? LIMIT 1");
        $stmt->bind_param('s', $email); // Naviazanie emailu ako parametra

        if ($stmt->execute()) {
            // Naviazanie premennych na stlpce z databazy
            $stmt->bind_result($user_id, $user_name, $user_email, $hashed_password, $user_role);
            $stmt->store_result(); // Ulozenie vysledku na dalsie pouzitie

            // Overenie, ci bol najdeny prave jeden pouzivatel
            if ($stmt->num_rows === 1) {
                $stmt->fetch(); // Nacitanie dat

                // Overenie hesla pomocou funkcie password_verify
                if (password_verify($password, $hashed_password)) {
                    // Nastavenie session premennych po uspesnom prihlaseni
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $user_name;
                    $_SESSION['user_email'] = $user_email;
                    $_SESSION['user_role'] = $user_role;
                    $_SESSION['logged_in'] = true;

                    return true; // Prihlasenie prebehlo uspesne
                }
            }
        }

        return false; // Prihlasenie zlyhalo
    }
}

