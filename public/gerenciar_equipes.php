<?php
require_once __DIR__ . '/../config/database.php';

$competicao_id = $_GET['id'] ?? null;

if (!$competicao_id) {
    echo "Competição não encontrada.";
    exit;
}

// Buscar equipes cadastradas nesta competição
$stmt = $pdo->prepare("
    SELECT r.id as ranking_id, r.grupo, t.nome as nome_time
    FROM ranking r
    JOIN times t ON t.id = r.equipe_id
    WHERE r.competicao_id = :competicao_id
    ORDER BY r.grupo ASC, t.nome ASC
");
$stmt->execute([':competicao_id' => $competicao_id]);
$equipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Equipes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center p-6">
    <div class="w-full max-w-4xl bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Equipes da Competição</h2>

        <div class="flex justify-end mb-4">
            <a href="adicionar_equipe.php?id=<?= $competicao_id ?>" 
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Adicionar Equipe
            </a>
        </div>

        <?php if (count($equipes) === 0): ?>
            <p class="text-center text-gray-600">Nenhuma equipe cadastrada nesta competição.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($equipes as $equipe): ?>
                    <div class="bg-white border border-gray-200 rounded-lg shadow p-4 flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= htmlspecialchars($equipe['nome_time']) ?></h3>
                        <p class="text-sm text-gray-600 mb-1">Grupo: <?= htmlspecialchars($equipe['grupo']) ?></p>
                        <!-- Futuramente:
                        <div class="mt-2 flex justify-end gap-2">
                            <a href="#" class="text-blue-600 hover:underline text-sm">Ver Jogadores</a>
                            <a href="#" class="text-red-600 hover:underline text-sm">Remover</a>
                        </div>
                        -->
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="mt-6 flex justify-center">
            <a href="gerenciar_competicoes.php" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                Voltar ao Painel
            </a>
        </div>
    </div>
</body>
</html>
