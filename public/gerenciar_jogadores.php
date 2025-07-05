<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/EquipeController.php';
require_once __DIR__ . '/../app/controllers/JogadorController.php';
require_once __DIR__ . '/../app/controllers/CompeticaoController.php';

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
    echo "Você não tem permissão para acessar os jogadores desta equipe.";
    exit;
}

$jugadorController = new JogadorController($pdo);
$jogadores = $jugadorController->listarJogadoresPorEquipe($equipe_id);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Jogadores - <?= htmlspecialchars($equipe['nome']) ?></title>
</head>
<body>
    <h2>Jogadores - <?= htmlspecialchars($equipe['nome']) ?></h2>
    <a href="gerenciar_equipes.php?id=<?= $competicao['id'] ?>">Voltar</a> | 
    <a href="adicionar_jogador.php?id=<?= $equipe_id ?>">Adicionar Jogador</a>
    <br><br>

    <?php if (count($jogadores) === 0): ?>
        <p>Esta equipe ainda não possui jogadores cadastrados.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>RG</th>
                    <th>Data de Nascimento</th>
                    <th>Contato</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jogadores as $jogador): ?>
                    <tr>
                        <td><?= htmlspecialchars($jogador['nome']) ?></td>
                        <td><?= htmlspecialchars($jogador['cpf']) ?></td>
                        <td><?= htmlspecialchars($jogador['rg']) ?></td>
                        <td><?= htmlspecialchars($jogador['data_nascimento']) ?></td>
                        <td><?= htmlspecialchars($jogador['contato']) ?></td>
                        <td>
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
