<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/CompeticaoController.php';

$competicao_id = $_GET['id'] ?? null;

if (!$competicao_id) {
    echo "ID da competição não informado.";
    exit;
}

// Buscar dados da competição
$stmt = $pdo->prepare("SELECT * FROM competicoes WHERE id = :id");
$stmt->execute([':id' => $competicao_id]);
$competicao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$competicao) {
    echo "Competição não encontrada.";
    exit;
}


// Buscar jogos da competição
$controller = new CompeticaoController($pdo);

$jogos = $controller->listarJogosDaCompeticao($competicao_id);

// Buscar rodadas distintas
$stmt = $pdo->prepare("SELECT DISTINCT rodada FROM jogos WHERE competicao_id = :competicao_id ORDER BY rodada ASC");
$stmt->execute([':competicao_id' => $competicao_id]);
$rodadas_disponiveis = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($competicao['nome']) ?> - Competição</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<header class="bg-blue-700 text-white p-4 shadow">
    <h1 class="text-xl font-bold text-center"><?= htmlspecialchars($competicao['nome']) ?></h1>
</header>
<main class="p-4 sm:p-6 max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        
        <!-- Coluna da esquerda: Jogos -->
        <section class="lg:col-span-3">
            <div class="flex items-center justify-center gap-4 mb-6">
                <button id="prevRodada" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded disabled:opacity-50">&#8592;</button>
                <h2 id="rodadaAtualLabel" class="text-lg sm:text-xl font-semibold text-gray-800">Rodada</h2>
                <button id="nextRodada" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded disabled:opacity-50">&#8594;</button>
            </div>

            <?php if (count($jogos) === 0): ?>
                <p class="text-center text-gray-600">Nenhum jogo cadastrado nesta competição.</p>
            <?php else: ?>
                <div id="cardsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($jogos as $jogo): ?>
                        <?php $logoA = $jogo['logo_a']; $logoB = $jogo['logo_b']; ?>
                        <div class="bg-white border border-gray-200 rounded-lg shadow-md p-4 text-base sm:text-sm" data-rodada="<?= htmlspecialchars($jogo['rodada']) ?>">
                            <p class="text-gray-500 text-xs mb-2">Rodada <?= htmlspecialchars($jogo['rodada']) ?></p>
                            
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-2">
                                    
                                    <img src="<?= $logoA ?>" alt="<?= $jogo['equipe_a_nome'] ?>" class="w-10 h-10 object-contain" />
                                    <span class="font-semibold"><?= htmlspecialchars($jogo['equipe_a_nome']) ?></span>
                                </div>

                                <div class="text-center">
                                    <p class="text-2xl font-bold"><?= htmlspecialchars($jogo['placar_a'] ?? '-') ?> x <?= htmlspecialchars($jogo['placar_b'] ?? '-') ?></p>
                                    <?php if (!empty($jogo['status'])): ?>
                                        <p class="text-xs font-semibold 
                                            <?= $jogo['status'] === 'finalizado' ? 'text-green-600' : ($jogo['status'] === 'em andamento' ? 'text-yellow-600' : 'text-blue-600') ?>">
                                            <?= ucfirst($jogo['status']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center gap-2 justify-end sm:justify-start">
                                    <span class="font-semibold"><?= htmlspecialchars($jogo['equipe_b_nome']) ?></span>
                                    <img src="<?= $logoB ?>" alt="<?= $jogo['equipe_b_nome'] ?>" class="w-10 h-10 object-contain" />
                                </div>
                            </div>

                            <div class="text-gray-500 text-xs mt-3 space-y-1">
                                <?php if (!empty($jogo['data'])): ?>
                                    <p>Data: <?= date('d/m/Y', strtotime($jogo['data'])) ?><?php if (!empty($jogo['horario'])): ?> às <?= substr($jogo['horario'], 0, 5) ?><?php endif; ?></p>
                                <?php endif; ?>
                                <?php if (!empty($jogo['local'])): ?>
                                    <p>Local: <?= htmlspecialchars($jogo['local']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Coluna da direita: Classificação -->
        <section class="lg:col-span-2 bg-white p-6 rounded shadow h-fit mt-6 lg:mt-0">
            <h2 class="text-xl font-semibold mb-4 text-center">Tabela de Classificação</h2>
            <?php include 'componentes/tabela_classificacao.php'; ?>
        </section>
    </div>
</main>





<script>
    window.RODADAS_DISPONIVEIS = <?= json_encode(array_map('strval', $rodadas_disponiveis)) ?>;
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rodadas = window.RODADAS_DISPONIVEIS;
    let currentIndex = 0;

    const rodadaLabel = document.getElementById('rodadaAtualLabel');
    const prevButton = document.getElementById('prevRodada');
    const nextButton = document.getElementById('nextRodada');
    const cardsContainer = document.getElementById('cardsContainer');
    const cards = Array.from(cardsContainer ? cardsContainer.children : []);

    function updateView() {
        if (!rodadas || rodadas.length === 0) {
            rodadaLabel.textContent = "Nenhuma rodada cadastrada";
            prevButton.disabled = true;
            nextButton.disabled = true;
            cards.forEach(card => card.classList.remove('hidden'));
            return;
        }

        const currentRodada = rodadas[currentIndex];
        rodadaLabel.textContent = `Rodada ${currentRodada}`;

        cards.forEach(card => {
            if (card.dataset.rodada === currentRodada) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex === rodadas.length - 1;
    }

    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateView();
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentIndex < rodadas.length - 1) {
            currentIndex++;
            updateView();
        }
    });

    updateView();
});
</script>
</body>
</html>