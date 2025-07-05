<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Painel Admin</title>
</head>
<body>
    <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    <p>Este é o Painel Admin.</p>

    <a href="criar_competicao.php">
        <button style="padding: 10px 15px; cursor: pointer;">
            Criar Competição
        </button>
    </a>
 <br><br>

    <a href="gerenciar_competicoes.php">
    <button style="padding: 10px 15px; cursor: pointer;">
        Gerenciar Competições
        </button>
    </a>

   


    <br><br>

    <a href="logout.php">Sair</a>
</body>
</html>
