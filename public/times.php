<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/EquipeController.php';
require_once __DIR__ . '/../app/controllers/JogadorController.php';


$equipeController = new EquipeController($pdo);
$equipes = $equipeController->listarTodasEquipes();


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Equipes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white w-full max-w-4xl rounded-xl shadow p-6 sm:p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Todas as Equipes Cadastradas</h2>
            <a href="dashboard.php" class="inline-flex items-center bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-md transition">
                <span class="material-icons-outlined mr-2 text-base">arrow_back</span> Voltar ao Painel
            </a>
        </div>

        <!-- Botão para adicionar time -->
        <div class="flex justify-end mb-4">
            <a href="inserir_time_erp.php" 
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                <span class="material-icons-outlined mr-2 text-base">add_circle</span> Adicionar Novo Time
            </a>
        </div>

        <?php if (count($equipes) === 0): ?>
            <p class="text-center text-gray-500">Nenhuma equipe cadastrada ainda.</p>
        <?php else: ?>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full bg-white text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Nome da Equipe</th>
                            <th class="px-4 py-3 text-left font-medium">Categoria</th>
                            <th class="px-4 py-3 text-left font-medium">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($equipes as $equipe): ?>
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="px-4 py-2"><?= htmlspecialchars($equipe['nome']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($equipe['categoria']) ?></td>
                                <td class="px-4 py-2 flex flex-wrap gap-2">
                                    <a href="gerenciar_jogadores.php?id=<?= $equipe['id'] ?>" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md transition text-xs">
                                        <span class="material-icons-outlined text-sm mr-1">groups</span> Jogadores
                                    </a>
                                    <a href="editar_equipe.php?id=<?= $equipe['id'] ?>" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md transition text-xs">
                                        <span class="material-icons-outlined text-sm mr-1">edit</span> Editar
                                    </a>
                                    <a href="excluir_equipe.php?id=<?= $equipe['id'] ?>" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md transition text-xs">
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
