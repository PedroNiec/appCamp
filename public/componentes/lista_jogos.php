<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/database.php';
}

if (!isset($competicao_id)) {
    echo "Competição inválida.";
    return;
}

// Buscar jogos da competição
$stmt = $pdo->prepare("
    SELECT 
        j.rodada,
        j.placar_a,
        j.placar_b,
        j.status,
        a.nome AS equipe_a,
        b.nome AS equipe_b
    FROM jogos j
    JOIN times a ON j.equipe_a_id = a.id
    JOIN times b ON j.equipe_b_id = b.id
    WHERE j.competicao_id = :competicao_id
    ORDER BY j.rodada ASC
");
$stmt->execute([':competicao_id' => $competicao_id]);
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$jogos) {
    echo "<p class='text-gray-500'>Nenhum jogo cadastrado ainda.</p>";
    return;
}
?>

<ul class="space-y-2">
    <?php foreach ($jogos as $jogo): ?>
        <li class="border rounded p-3 bg-gray-50 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-medium"><?= htmlspecialchars($jogo['equipe_a']) ?> vs <?= htmlspecialchars($jogo['equipe_b']) ?></p>
                    <p class="text-sm text-gray-500">Rodada <?= $jogo['rodada'] ?></p>
                </div>
                <div class="text-right">
                    <?php if ($jogo['status'] === 'finalizado'): ?>
                        <span class="font-semibold text-lg"><?= $jogo['placar_a'] ?> x <?= $jogo['placar_b'] ?></span>
                        <p class="text-green-600 text-xs">Finalizado</p>
                    <?php else: ?>
                        <p class="text-yellow-600 text-sm">Aguardando</p>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>