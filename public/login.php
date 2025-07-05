<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $auth = new AuthController($pdo);
    $auth->login($email, $senha);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
</head>
<body>
    <h2>Login Admin</h2>
    <form method="POST" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
