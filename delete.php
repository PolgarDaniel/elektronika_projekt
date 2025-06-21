<?php
require_once 'classes/Database.php';
require_once 'classes/User.php';

try {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("ID is not provided.");
    }

    $id = intval($_GET['id']);

    $db = new Database();
    $conn = $db->connect();

    $user = new User($conn);
    $user->delete($id);

    header("Location: admin.php");
    exit;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
