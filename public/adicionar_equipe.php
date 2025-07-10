<?php
require_once __DIR__ . '/../config/database.php';

$competicao_id = $_GET['id'] ?? null;

if (!$competicao_id) {
    echo "Competição não encontrada.";
    exit;
}

// Se form enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipe_id = $_POST['equipe_id'] ?? null;
    $grupo = $_POST['grupo'] ?? null;

    if ($equipe_id && $grupo !== null) {
        try {
            // Verificar duplicidade
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM ranking WHERE competicao_id = :competicao_id AND equipe_id = :equipe_id");
            $stmt->execute([
                ':competicao_id' => $competicao_id,
                ':equipe_id' => $equipe_id
            ]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $error = "Esta equipe já está cadastrada nesta competição.";
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO ranking 
                    (competicao_id, equipe_id, grupo, pontos, saldo_gols, jogos, vitorias, derrotas, empates, cartoes_amarelos, gols_pro, gols_sofridos, cartoes_vermelho) 
                    VALUES 
                    (:competicao_id, :equipe_id, :grupo, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
                ");
                $stmt->execute([
                    ':competicao_id' => $competicao_id,
                    ':equipe_id' => $equipe_id,
                    ':grupo' => $grupo
                ]);
                header("Location: gerenciar_equipes.php?id=$competicao_id&success=1");
                exit;
            }
        } catch (PDOException $e) {
            $error = "Erro ao cadastrar equipe: " . $e->getMessage();
        }
    } else {
        $error = "Todos os campos são obrigatórios.";
    }
}

// Listar times para o select
$times = $pdo->query("SELECT id, nome FROM times ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Equipe na Competição</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white p-6 rounded-lg shadow w-full max-w-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Adicionar Equipe à Competição</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1">Selecione a Equipe</label>
                <select name="equipe_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Selecione</option>
                    <?php foreach ($times as $time): ?>
                        <option value="<?= $time['id'] ?>"><?= htmlspecialchars($time['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Número do Grupo</label>
                <input type="number" name="grupo" min="1" required class="w-full border rounded px-3 py-2" placeholder="Ex: 1">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Adicionar Equipe
            </button>
        </form>

        <div class="mt-4 flex justify-center">
            <a href="gerenciar_equipes.php?id=<?= $competicao_id ?>" class="text-blue-600 hover:underline">Voltar</a>
        </div>
    </div>
</body>
</html>
