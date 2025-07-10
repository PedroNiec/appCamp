<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/CompeticaoController.php';

$competicao_id = $_GET['id'] ?? null;

if (!$competicao_id) {
    echo "Competição não encontrada.";
    exit;
}

$controller = new CompeticaoController($pdo);
$jogos = $controller->listarJogosDaCompeticao($competicao_id);

// Buscar rodadas disponíveis para esta competição
$stmt = $pdo->prepare("SELECT DISTINCT rodada FROM jogos WHERE competicao_id = :competicao_id ORDER BY rodada ASC");
$stmt->execute([':competicao_id' => $competicao_id]);
$rodadas_disponiveis = $stmt->fetchAll(PDO::FETCH_COLUMN);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Jogos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center p-6">
    <div class="w-full max-w-6xl bg-white p-6 rounded-lg shadow-md">

        <!-- Controle de rodadas -->
        <div class="flex items-center justify-center gap-4 mb-6">
            <button id="prevRodada" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded disabled:opacity-50">&#8592;</button>
            <h2 id="rodadaAtualLabel" class="text-2xl font-semibold text-gray-800">Rodada</h2>
            <button id="nextRodada" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded disabled:opacity-50">&#8594;</button>
        </div>
        <div class="flex justify-end mb-4">
            <a href="adicionar_jogo.php?id=<?= $competicao_id ?>"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4"/>
                </svg>
                Adicionar Novo Jogo
            </a>
        </div>


        <?php if (count($jogos) === 0): ?>
            <p class="text-center text-gray-600">Nenhum jogo cadastrado nesta competição.</p>
        <?php else: ?>
            <div id="cardsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($jogos as $jogo): ?>
                    <div 
                        class="bg-white border border-gray-200 rounded-lg shadow p-4 flex flex-col justify-between"
                        data-rodada="<?= htmlspecialchars($jogo['rodada']) ?>">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Rodada: <span class="font-medium"><?= htmlspecialchars($jogo['rodada'] ?? '-') ?></span>
                            </p>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                <?= htmlspecialchars($jogo['equipe_a_nome']) ?> vs <?= htmlspecialchars($jogo['equipe_b_nome']) ?>
                            </h3>
                            <p class="text-sm text-gray-600 mb-1">
                                <?php if (!empty($jogo['data'])): ?>
                                    <?= date('d/m/Y', strtotime($jogo['data'])) ?>
                                <?php else: ?>
                                    Data não definida
                                <?php endif; ?>
                                <?php if (!empty($jogo['horario'])): ?>
                                    às <?= substr($jogo['horario'], 0, 5) ?>
                                <?php endif; ?>
                            </p>
                            <p class="text-sm text-gray-600 mb-1">
                                Local: <?= htmlspecialchars($jogo['local'] ?? '-') ?>
                            </p>
                            <p class="text-sm text-gray-600 mb-1">
                                Status: <span class="font-medium"><?= htmlspecialchars($jogo['status'] ?? '-') ?></span>
                            </p>
                            <p class="text-sm text-gray-600">
                                Placar: <?= htmlspecialchars($jogo['placar_a'] ?? '-') ?> x <?= htmlspecialchars($jogo['placar_b'] ?? '-') ?>
                            </p>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <a href="editar_jogo.php?id=<?= $jogo['id'] ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                Editar Jogo
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="mt-8 flex justify-center">
            <a href="dashboard.php" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                Voltar ao Painel
            </a>
        </div>
    </div>

    <script>
    window.RODADAS_DISPONIVEIS = <?= json_encode(array_map('strval', $rodadas_disponiveis)) ?>;
    </script>
    <script src="../js/gerenciarJogos.js"></script>
</body>
</html>
