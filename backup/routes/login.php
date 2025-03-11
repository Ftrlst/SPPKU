<?php
require_once __DIR__ . '/../controllers/AuthController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new AuthController();
    $auth->login($_POST['username'], $_POST['password']);
}
?>
