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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Competições</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white w-full max-w-5xl rounded-xl shadow-sm p-6 sm:p-10">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Gerenciar Competições</h2>
            <a href="criar_competicao.php" class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-all">
                <span class="material-icons-outlined mr-2 text-base">add</span>
                Nova Competição
            </a>
        </div>

        <?php if (count($competicoes) === 0): ?>
            <p class="text-center text-gray-500">Nenhuma competição cadastrada ainda.</p>
        <?php else: ?>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full bg-white text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 font-medium text-left">Nome</th>
                            <th class="px-4 py-3 font-medium text-left">Modalidade</th>
                            <th class="px-4 py-3 font-medium text-left">Local</th>
                            <th class="px-4 py-3 font-medium text-left">Início</th>
                            <th class="px-4 py-3 font-medium text-left">Fim</th>
                            <th class="px-4 py-3 font-medium text-left">Status</th>
                            <th class="px-4 py-3 font-medium text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($competicoes as $comp): ?>
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="px-4 py-2"><?= htmlspecialchars($comp['nome']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($comp['modalidade_nome']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($comp['local']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($comp['data_inicio']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($comp['data_fim']) ?></td>
                                <td class="px-4 py-2">
                                    <?php if ($comp['status'] === 'ativa'): ?>
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full">Ativa</span>
                                    <?php elseif ($comp['status'] === 'inativa'): ?>
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded-full">Inativa</span>
                                    <?php else: ?>
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full">Finalizada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2 flex flex-wrap gap-2">
                                    <a href="gerenciar_equipes.php?id=<?= $comp['id'] ?>" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md transition">
                                        <span class="material-icons-outlined mr-1 text-sm">groups</span> Equipes
                                    </a>
                                    <a href="tabela.php?id=<?= $comp['id'] ?>" class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md transition">
                                        <span class="material-icons-outlined mr-1 text-sm">table_chart</span> Tabela
                                    </a>
                                    <a href="gerenciar_jogos.php?id=<?= $comp['id'] ?>" class="inline-flex items-center bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded-md transition">
                                        <span class="material-icons-outlined mr-1 text-sm">sports_soccer</span> Jogos
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="flex justify-center mt-8">
            <a href="dashboard.php" class="inline-flex items-center bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-md transition">
                <span class="material-icons-outlined mr-2 text-base">arrow_back</span> Voltar ao Painel
            </a>
        </div>
    </div>

</body>
</html>
