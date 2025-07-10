<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/JogadorController.php';
require_once __DIR__ . '/../app/controllers/EquipeController.php';

$equipe_id = $_GET['id'] ?? null;

if (!$equipe_id) {
    echo "Equipe não encontrada.";
    exit;
}

$equipeController = new EquipeController($pdo);
$equipe = $equipeController->buscarEquipePorId($equipe_id);

if (!$equipe) {
    echo "Equipe não encontrada ou você não tem permissão para acessá-la.";
    exit;
}

$jogadorController = new JogadorController($pdo);
$jogadores = $jogadorController->listarJogadoresPorEquipe($equipe_id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Jogadores - <?= htmlspecialchars($equipe['nome']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white w-full max-w-4xl rounded-xl shadow p-6 sm:p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">
                Jogadores - <?= htmlspecialchars($equipe['nome']) ?>
            </h2>
            <a href="times.php" class="inline-flex items-center bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-md transition">
                <span class="material-icons-outlined mr-2 text-base">arrow_back</span> Voltar
            </a>
        </div>

        <div class="flex justify-end mb-4">
            <a href="adicionar_jogador.php?equipe_id=<?= $equipe_id ?>"
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                <span class="material-icons-outlined mr-2 text-base">add_circle</span> Adicionar Jogador
            </a>
        </div>

        <?php if (count($jogadores) === 0): ?>
            <p class="text-center text-gray-500">Nenhum jogador cadastrado nesta equipe ainda.</p>
        <?php else: ?>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full bg-white text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Nome do Jogador</th>
                            <th class="px-4 py-3 text-left font-medium">Posição</th>
                            <th class="px-4 py-3 text-left font-medium">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jogadores as $jogador): ?>
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="px-4 py-2"><?= htmlspecialchars($jogador['nome']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($jogador['posicao'] ?? '-') ?></td>
                                <td class="px-4 py-2 flex flex-wrap gap-2">
                                    <a href="editar_jogador.php?id=<?= $jogador['id'] ?>" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md transition text-xs">
                                        <span class="material-icons-outlined text-sm mr-1">edit</span> Editar
                                    </a>
                                    <a href="excluir_jogador.php?id=<?= $jogador['id'] ?>&equipe_id=<?= $equipe_id ?>" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md transition text-xs" onclick="return confirm('Tem certeza que deseja excluir este jogador?')">
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
