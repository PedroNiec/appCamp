<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/EquipeController.php';
require_once __DIR__ . '/../app/controllers/CompeticaoController.php';
require_once __DIR__ . '/../app/controllers/JogadorController.php';

if (!isset($_GET['id'])) {
    header("Location: gerenciar_competicoes.php");
    exit;
}

$equipe_id = $_GET['id'];

$equipeController = new EquipeController($pdo);
$equipe = $equipeController->buscarEquipePorId($equipe_id);

if (!$equipe) {
    echo "Equipe não encontrada.";
    exit;
}

$competicaoController = new CompeticaoController($pdo);
$competicao = $competicaoController->buscarCompeticaoPorId($equipe['competicao_id'], $_SESSION['user_id']);

if (!$competicao) {
    echo "Você não tem permissão para gerenciar jogadores desta equipe.";
    exit;
}

$jogadorController = new JogadorController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jogadorController->criarJogador($_POST);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Adicionar Jogador - <?= htmlspecialchars($equipe['nome']) ?></title>
</head>
<body>
    <h2>Adicionar Jogador - <?= htmlspecialchars($equipe['nome']) ?> (<?= htmlspecialchars($competicao['nome']) ?>)</h2>
    <a href="gerenciar_jogadores.php?id=<?= $equipe_id ?>">Voltar</a>
    <br><br>

    <form method="POST">
        <input type="hidden" name="equipe_id" value="<?= $equipe_id ?>">

        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>CPF:</label><br>
        <input type="text" name="cpf"><br><br>

        <label>RG (Opcional):</label><br>
        <input type="text" name="rg"><br><br>

        <label>Data de Nascimento:</label><br>
        <input type="date" name="data_nascimento"><br><br>

        <label>Contato:</label><br>
        <input type="text" name="contato"><br><br>

        <button type="submit">Cadastrar Jogador</button>
    </form>
</body>
</html>
