<?php
require_once 'classes/Database.php';
require_once 'classes/User.php';

try {
    // Overenie, ci je v GET parametri nastavene a nenulove 'id'
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("ID is not provided.");
    }

    // Konverzia 'id' na cele cislo pre bezpecnost
    $id = intval($_GET['id']);

    // Pripojenie k databaze
    $db = new Database();
    $conn = $db->connect();

    // Vytvorenie objektu User a vymazanie pouzivatela s danym id
    $user = new User($conn);
    $user->delete($id);

    // Presmerovanie na admin stranku po uspesnom vymazani
    header("Location: admin.php");
    exit;

} catch (Exception $e) {
    // Vypis chyby v pripade vynimky
    echo "Error: " . $e->getMessage();
}

