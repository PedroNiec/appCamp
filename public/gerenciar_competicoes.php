<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/CompeticaoController.php';

$controller = new CompeticaoController($pdo);

$competicoes = $controller->listarCompeticoesDoUsuario($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Competições</title>
</head>
<body>
    <h2>Gerenciar Competições</h2>
    <a href="dashboard.php">Voltar ao Painel</a>
    <br><br>

    <?php if (count($competicoes) === 0): ?>
        <p>Você ainda não cadastrou nenhuma competição.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Modalidade</th>
                    <th>Local</th>
                    <th>Data Início</th>
                    <th>Data Fim</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($competicoes as $comp): ?>
                    <tr>
                        <td><?= htmlspecialchars($comp['nome']) ?></td>
                        <td><?= htmlspecialchars($comp['modalidade_nome']) ?></td>
                        <td><?= htmlspecialchars($comp['local']) ?></td>
                        <td><?= htmlspecialchars($comp['data_inicio']) ?></td>
                        <td><?= htmlspecialchars($comp['data_fim']) ?></td>
                        <td><?= htmlspecialchars($comp['status']) ?></td>
                        <td>
                            <a href="gerenciar_equipes.php?id=<?= $comp['id'] ?>">Gerenciar Equipes</a>
                            <a href="#">Ver Detalhes</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="gerenciar_jogos.php?id=<?= $comp['id'] ?>">Gerenciar Jogos</a> |
    <?php endif; ?>
</body>
</html>
