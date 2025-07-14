<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

// Verifica se o parâmetro "id" foi enviado
if (!isset($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Parâmetro "id" é obrigatório.']);
    exit;
}

$jogoId = $_GET['id']; // UUID passado via GET

if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[45][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $jogoId)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Formato de ID inválido. Deve ser um UUID.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            j.id AS jogo_id,
            ea.nome AS equipe_a,
            eb.nome AS equipe_b
        FROM jogos j
        JOIN times ea ON j.equipe_a_id = ea.id
        JOIN times eb ON j.equipe_b_id = eb.id
        WHERE j.id = :id 
        AND j.equipe_a_id IS NOT NULL 
        AND j.equipe_b_id IS NOT NULL
    ");
    $stmt->execute(['id' => $jogoId]);
    $times = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($times);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Erro ao consultar o banco de dados.', 'details' => $e->getMessage()]);
}
