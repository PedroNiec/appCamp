<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/EquipeController.php';
require_once __DIR__ . '/../app/controllers/CompeticaoController.php';

if (!isset($_GET['id'])) {
    header("Location: gerenciar_competicoes.php");
    exit;
}

$competicao_id = $_GET['id'];
$competicaoController = new CompeticaoController($pdo);
$competicao = $competicaoController->buscarCompeticaoPorId($competicao_id, $_SESSION['user_id']);

if (!$competicao) {
    echo "Competição não encontrada ou você não tem permissão para acessá-la.";
    exit;
}

$equipeController = new EquipeController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipeController->criarEquipe($_POST);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Adicionar Equipe - <?= htmlspecialchars($competicao['nome']) ?></title>
</head>
<body>
    <h2>Adicionar Equipe - <?= htmlspecialchars($competicao['nome']) ?></h2>
    <a href="gerenciar_equipes.php?id=<?= $competicao_id ?>">Voltar</a>
    <br><br>

    <form method="POST">
        <input type="hidden" name="competicao_id" value="<?= $competicao_id ?>">

        <label>Nome da Equipe:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Categoria:</label><br>
        <input type="text" name="categoria"><br><br>

        <button type="submit">Cadastrar Equipe</button>
    </form>
</body>
</html>
