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

        <form id="editarJogoForm" method="POST" class="space-y-4">
<input
    type="text"
    name="jogo_id"
    id = "jogoIdInput"
    value="<?= htmlspecialchars($jogo['id']) ?>"
    readonly
    class="w-full border rounded px-3 py-2 bg-gray-100"
/>
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

            <div class="text-center mt-6">
           <a href="evento_jogadores_jogo.php?id=<?php echo urlencode(htmlspecialchars($jogo['id'])); ?>" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                 Gerenciar Eventos dos Jogadores
            </a>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Salvar Alterações</button>
        </form>

        <div class="mt-4 text-center">
            <a href="gerenciar_jogos.php?id=<?= $jogo['competicao_id'] ?>" class="text-blue-600 hover:underline">Voltar</a>
        </div>
    </div>


</body>
</html>
