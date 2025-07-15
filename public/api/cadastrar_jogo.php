<?php

require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura o JSON enviado pelo fetch
    $data = json_decode(file_get_contents('php://input'), true);


    $competicao_id = $data['competicao_id'] ?? null;
    $time_a_id = $data['time_a_id'] ?? null;
    $time_b_id = $data['time_b_id'] ?? null;
    $rodada = $data['rodada'] ?? null;

    if ($competicao_id !== null && $competicao_id !== '' &&
        $time_a_id !== null && $time_a_id !== '' &&
        $time_b_id !== null && $time_b_id !== '') {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO jogos (competicao_id, equipe_a_id, equipe_b_id, rodada, criado_em)
                VALUES (:competicao_id, :equipe_a_id, :equipe_b_id, :rodada, NOW())
            ");
            $stmt->execute([
                ':competicao_id' => $competicao_id,
                ':equipe_a_id' => $time_a_id,
                ':equipe_b_id' => $time_b_id,
                ':rodada' => $rodada
            ]);

            echo json_encode(['success' => true, 'message' => 'Jogo cadastrado com sucesso.']);
            exit;
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar jogo: ' . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Todos os campos obrigatórios devem ser preenchidos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
}
