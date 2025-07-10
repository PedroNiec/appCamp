<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../config/database.php';

$jogo_id = $_GET['id'] ?? null;
if (!$jogo_id) {
    echo "Jogo não encontrado."; exit;
}

// Buscar dados do jogo
$stmt = $pdo->prepare("SELECT * FROM jogos WHERE id = :id");
$stmt->execute([':id' => $jogo_id]);
$jogo = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$jogo) {
    echo "Jogo não encontrado."; exit;
}

// Buscar times
$time_a_id = $jogo['equipe_a_id'];
$time_b_id = $jogo['equipe_b_id'];

$time_a = $pdo->prepare("SELECT nome FROM times WHERE id = :id");
$time_a->execute([':id' => $time_a_id]);
$time_a_nome = $time_a->fetchColumn();

$time_b = $pdo->prepare("SELECT nome FROM times WHERE id = :id");
$time_b->execute([':id' => $time_b_id]);
$time_b_nome = $time_b->fetchColumn();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Jogo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="bg-white p-6 rounded-lg shadow w-full max-w-2xl">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Editar Jogo</h2>

        <form id="editarJogoForm" method="POST" class="space-y-4" action="salvar_editar_jogo.php">
            <input type="hidden" name="jogo_id" value="<?= htmlspecialchars($jogo_id) ?>">

            <div>
                <label class="block text-gray-700 mb-1">Rodada</label>
                <input type="number" value="<?= htmlspecialchars($jogo['rodada']) ?>" readonly class="w-full border rounded px-3 py-2 bg-gray-100">
            </div>

            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-gray-700 mb-1">Placar <?= htmlspecialchars($time_a_nome) ?></label>
                    <input type="number" name="placar_a" value="<?= htmlspecialchars($jogo['placar_a']) ?>" class="w-full border rounded px-3 py-2">
                </div>
                <div class="flex-1">
                    <label class="block text-gray-700 mb-1">Placar <?= htmlspecialchars($time_b_nome) ?></label>
                    <input type="number" name="placar_b" value="<?= htmlspecialchars($jogo['placar_b']) ?>" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="agendado" <?= $jogo['status'] == 'agendado' ? 'selected' : '' ?>>Agendado</option>
                    <option value="em_andamento" <?= $jogo['status'] == 'em_andamento' ? 'selected' : '' ?>>Em Andamento</option>
                    <option value="finalizado" <?= $jogo['status'] == 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
                </select>
            </div>

            <!-- Eventos dos Jogadores -->
            <h3 class="text-lg font-semibold text-center mt-6 mb-2">Eventos dos Jogadores</h3>
            <div>
                <label class="block text-gray-700 mb-1">Selecione o Time</label>
                <select id="timeSelect" class="w-full border rounded px-3 py-2">
                    <option value="">Selecione</option>
                    <option value="<?= $time_a_id ?>"><?= htmlspecialchars($time_a_nome) ?></option>
                    <option value="<?= $time_b_id ?>"><?= htmlspecialchars($time_b_nome) ?></option>
                </select>
            </div>

            <div id="jogadoresContainer" class="space-y-2 mt-4"></div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Salvar Alterações</button>
        </form>

        <div class="mt-4 text-center">
            <a href="gerenciar_jogos.php?id=<?= $jogo['competicao_id'] ?>" class="text-blue-600 hover:underline">Voltar</a>
        </div>
    </div>

<script>
document.getElementById('timeSelect').addEventListener('change', function() {
    const timeId = this.value;
    const container = document.getElementById('jogadoresContainer');
    container.innerHTML = 'Carregando jogadores...';

    if (!timeId) {
        container.innerHTML = '';
        return;
    }

    fetch(`../api/get_jogadores_por_time.php?id=${timeId}`)
    .then(response => response.json())
    .then(jogadores => {
        container.innerHTML = '';

        if (jogadores.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500">Nenhum jogador encontrado para este time.</p>';
            return;
        }

        jogadores.forEach(jogador => {
            const div = document.createElement('div');
            div.className = "border p-2 rounded flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-gray-50";

            div.innerHTML = `
                <span class="font-medium">${jogador.nome}</span>
                <div class="flex flex-wrap gap-2">
                    <input type="hidden" name="jogadores[${jogador.id}][id]" value="${jogador.id}">
                    <input type="number" name="jogadores[${jogador.id}][gols]" placeholder="Gols" class="w-20 border rounded px-2 py-1" min="0">
                    <input type="number" name="jogadores[${jogador.id}][assist]" placeholder="Assists" class="w-20 border rounded px-2 py-1" min="0">
                    <input type="number" name="jogadores[${jogador.id}][amarelos]" placeholder="Amarelos" class="w-24 border rounded px-2 py-1" min="0">
                    <input type="number" name="jogadores[${jogador.id}][vermelhos]" placeholder="Vermelhos" class="w-24 border rounded px-2 py-1" min="0">
                </div>
            `;
            container.appendChild(div);
        });
    })
    .catch(error => {
        console.error('Erro ao carregar jogadores:', error);
        container.innerHTML = '<p class="text-center text-red-500">Erro ao carregar jogadores.</p>';
    });
});
</script>
</body>
</html>
