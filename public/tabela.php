<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

$competicao_id = $_GET['id'] ?? null;
if (!$competicao_id) {
    echo "Competição não encontrada.";
    exit;
}

// Função para buscar ranking de um grupo
function buscarRankingGrupo($pdo, $competicao_id, $grupo) {
    $stmt = $pdo->prepare("
        SELECT 
            r.*,
            t.nome AS nome_equipe,
            t.logo
        FROM ranking r
        JOIN times t ON t.id = r.equipe_id
        WHERE r.competicao_id = :competicao_id AND r.grupo = :grupo
        ORDER BY 
            r.pontos DESC,
            r.vitorias DESC,
            r.saldo_gols DESC,
            r.gols_pro DESC,
            r.cartoes_vermelho ASC,
            r.cartoes_amarelos ASC
    ");
    $stmt->execute([
        ':competicao_id' => $competicao_id,
        ':grupo' => $grupo
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$ranking_grupo_1 = buscarRankingGrupo($pdo, $competicao_id, 1);
$ranking_grupo_2 = buscarRankingGrupo($pdo, $competicao_id, 2);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tabela de Classificação</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center p-6">
    <div class="w-full max-w-5xl bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Tabela de Classificação</h2>

        <?php foreach ([1 => $ranking_grupo_1, 2 => $ranking_grupo_2] as $grupo_numero => $ranking): ?>
            <h3 class="text-xl font-semibold text-gray-700 mt-8 mb-4 text-center">Grupo <?= $grupo_numero ?></h3>

            <?php if (count($ranking) === 0): ?>
                <p class="text-center text-gray-600 mb-4">Nenhuma equipe cadastrada neste grupo.</p>
            <?php else: ?>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full bg-white text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium">Posição</th>
                                <th class="px-4 py-3 text-left font-medium">Equipe</th>
                                <th class="px-4 py-3 text-center font-medium">Pts</th>
                                <th class="px-4 py-3 text-center font-medium">J</th>
                                <th class="px-4 py-3 text-center font-medium">V</th>
                                <th class="px-4 py-3 text-center font-medium">E</th>
                                <th class="px-4 py-3 text-center font-medium">D</th>
                                <th class="px-4 py-3 text-center font-medium">GP</th>
                                <th class="px-4 py-3 text-center font-medium">GC</th>
                                <th class="px-4 py-3 text-center font-medium">SG</th>
                                <th class="px-4 py-3 text-center font-medium">CV</th>
                                <th class="px-4 py-3 text-center font-medium">CA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $pos = 1; ?>
                            <?php foreach ($ranking as $equipe): ?>
                                <tr class="border-t hover:bg-gray-50 transition">
                                    <td class="px-4 py-2"><?= $pos++ ?></td>
                                    <td class="px-4 py-2 flex items-center gap-2">
                                        <?php
                                        if (!empty($equipe['logo'])): ?>
                                            <img src="<?= htmlspecialchars($equipe['logo']) ?>" alt="Logo <?= htmlspecialchars($equipe['nome_equipe']) ?>" class="h-8 w-8 object-contain rounded">
                                        <?php else: ?>
                                            <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center text-gray-500 text-xs">?</div>
                                        <?php endif; ?>
                                        <span><?= htmlspecialchars($equipe['nome_equipe']) ?></span>
                                    </td>
                                    <td class="px-4 py-2 text-center"><?= $equipe['pontos'] ?></td>
                                    <td class="px-4 py-2 text-center"><?= $equipe['jogos'] ?></td>
                                    <td class="px-4 py-2 text-center"><?= $equipe['vitorias'] ?></td>
                                    <td class="px-4 py-2 text-center"><?= $equipe['empates'] ?></td>
                                    <td class="px-4 py-2 text-center"><?= $equipe['derrotas'] ?></td>
                                    <td class="px-4 py-2 text-center"><?= $equipe['gols_pro'] ?></td>
                                    <td class="px-4 py-2 text-center"><?= $equipe['gols_sofridos'] ?></td>
                                    <td class="px-4 py-2 text-center"><?= $equipe['saldo_gols'] ?></td>
                                    <td class="px-4 py-2 text-center"><?= $equipe['cartoes_vermelho'] ?? 0 ?></td>
                                    <td class="px-4 py-2 text-center"><?= $equipe['cartoes_amarelos'] ?? 0 ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="mt-8 flex justify-center">
            <a href="gerenciar_competicoes.php" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                Voltar para Gerenciar Competições
            </a>
        </div>
    </div>
</body>
</html>
