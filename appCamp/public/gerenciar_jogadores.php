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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Times</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white w-full max-w-4xl rounded-xl shadow p-6 sm:p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Todos os Times Cadastrados</h2>
            <a href="dashboard.php" class="inline-flex items-center bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-md transition">
                <span class="material-icons-outlined mr-2 text-base">arrow_back</span> Voltar ao Painel
            </a>
        </div>

        <?php if (count($equipes) === 0): ?>
            <p class="text-center text-gray-500">Nenhum time cadastrado ainda.</p>
        <?php else: ?>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full bg-white text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Nome do Time</th>
                            <th class="px-4 py-3 text-left font-medium">Categoria</th>
                            <th class="px-4 py-3 text-left font-medium">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($equipes as $equipe): ?>
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="px-4 py-2"><?= htmlspecialchars($equipe['nome']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($equipe['categoria'] ?? '-') ?></td>
                                <td class="px-4 py-2 flex flex-wrap gap-2">
                                    <a href="gerenciar_jogadores.php?id=<?= $equipe['id'] ?>" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md transition text-xs">
                                        <span class="material-icons-outlined text-sm mr-1">groups</span> Jogadores
                                    </a>
                                    <a href="editar_time.php?id=<?= $equipe['id'] ?>" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md transition text-xs">
                                        <span class="material-icons-outlined text-sm mr-1">edit</span> Editar
                                    </a>
                                    <a href="excluir_time.php?id=<?= $equipe['id'] ?>" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md transition text-xs">
                                        <span class="material-icons-outlined text-sm mr-1">delete</span> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
