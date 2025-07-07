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
$equipes = $equipeController->listarEquipesPorCompeticao($competicao_id);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Equipes - <?= htmlspecialchars($competicao['nome']) ?></title>
</head>
<body>
    <h2>Gerenciar Equipes - <?= htmlspecialchars($competicao['nome']) ?></h2>
    <a href="gerenciar_competicoes.php">Voltar</a> | 
    <a href="adicionar_equipe.php?id=<?= $competicao_id ?>">Adicionar Equipe</a>
    <br><br>

    <?php if (count($equipes) === 0): ?>
        <p>Esta competição ainda não possui equipes cadastradas.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Nome da Equipe</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipes as $equipe): ?>
                    <tr>
                        <td><?= htmlspecialchars($equipe['nome']) ?></td>
                        <td>
                            <a href="gerenciar_jogadores.php?id=<?= $equipe['id'] ?>">Ver Jogadores</a> ||
                            <a href="#">Editar</a> |
                            <a href="#">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
