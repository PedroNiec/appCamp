<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../../config/database.php';
}

if (!isset($competicao_id)) {
    echo "Competição inválida.";
    return;
}

// Função para exibir a tabela de um grupo
function renderTabelaGrupo($pdo, $competicao_id, $grupo_numero) {
    $stmt = $pdo->prepare("
        SELECT r.*, t.nome AS nome_equipe, t.logo
        FROM ranking r
        JOIN times t ON r.equipe_id = t.id
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
        ':grupo' => $grupo_numero
    ]);
    $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$ranking) {
        echo "<p class='text-gray-500 mb-4'>Grupo $grupo_numero ainda não possui dados.</p>";
        return;
    }

    echo "<h3 class='text-lg font-semibold text-gray-700 mb-2 mt-6 text-center'>Grupo $grupo_numero</h3>";
    echo "<div class='overflow-x-auto mb-6'>
        <table class='min-w-full bg-white border rounded-lg text-sm shadow-sm'>
            <thead class='bg-gray-100'>
                <tr>
                    <th class='px-3 py-2 text-left'>#</th>
                    <th class='px-3 py-2 text-left'>Equipe</th>
                    <th class='px-2 py-2 text-center'>Pts</th>
                    <th class='px-2 py-2 text-center'>J</th>
                    <th class='px-2 py-2 text-center'>V</th>
                    <th class='px-2 py-2 text-center'>E</th>
                    <th class='px-2 py-2 text-center'>D</th>
                    <th class='px-2 py-2 text-center'>GP</th>
                    <th class='px-2 py-2 text-center'>GC</th>
                    <th class='px-2 py-2 text-center'>SG</th>
                    <th class='px-2 py-2 text-center'>CV</th>
                    <th class='px-2 py-2 text-center'>CA</th>
                </tr>
            </thead>
            <tbody class='text-gray-700'>";
    
            foreach ($ranking as $i => $row) {
                echo "<tr class='border-t hover:bg-gray-50'>
                        <td class='px-3 py-2'>" . ($i + 1) . "</td>
                        <td class='px-3 py-2 flex items-center gap-2'>
                            " . (!empty($row['logo']) ? "<img src='" . htmlspecialchars($row['logo']) . "' alt='Logo " . htmlspecialchars($row['nome_equipe']) . "' class='h-6 w-6 object-contain rounded'>" : "<div class='h-6 w-6 bg-gray-200 rounded flex items-center justify-center text-gray-500 text-xs'>?</div>") . "
                            <span>" . htmlspecialchars($row['nome_equipe']) . "</span>
                        </td>
                        <td class='px-2 py-2 text-center'>" . (isset($row['pontos']) ? htmlspecialchars($row['pontos']) : 0) . "</td>
                        <td class='px-2 py-2 text-center'>" . (isset($row['jogos']) ? htmlspecialchars($row['jogos']) : 0) . "</td>
                        <td class='px-2 py-2 text-center'>" . (isset($row['vitorias']) ? htmlspecialchars($row['vitorias']) : 0) . "</td>
                        <td class='px-2 py-2 text-center'>" . (isset($row['empates']) ? htmlspecialchars($row['empates']) : 0) . "</td>
                        <td class='px-2 py-2 text-center'>" . (isset($row['derrotas']) ? htmlspecialchars($row['derrotas']) : 0) . "</td>
                        <td class='px-2 py-2 text-center'>" . (isset($row['gols_pro']) ? htmlspecialchars($row['gols_pro']) : 0) . "</td>
                        <td class='px-2 py-2 text-center'>" . (isset($row['gols_sofridos']) ? htmlspecialchars($row['gols_sofridos']) : 0) . "</td>
                        <td class='px-2 py-2 text-center'>" . (isset($row['saldo_gols']) ? htmlspecialchars($row['saldo_gols']) : 0) . "</td>
                        <td class='px-2 py-2 text-center'>" . (isset($row['cartoes_vermelho']) ? htmlspecialchars($row['cartoes_vermelho']) : 0) . "</td>
                        <td class='px-2 py-2 text-center'>" . (isset($row['cartoes_amarelos']) ? htmlspecialchars($row['cartoes_amarelos']) : 0) . "</td>
                    </tr>";
            }

    echo "</tbody></table></div>";
}

// Renderizar os dois grupos
renderTabelaGrupo($pdo, $competicao_id, 1);
renderTabelaGrupo($pdo, $competicao_id, 2);
?>