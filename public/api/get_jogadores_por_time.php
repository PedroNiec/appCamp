<?php

require_once __DIR__ . '/../../config/database.php';

$nome_do_time = isset($_GET['time']) ? $_GET['time'] : '';

if ($nome_do_time !== '') {
    $stmt = $pdo->prepare("
        SELECT j.id, j.nome
        FROM jogadores j
        JOIN times t ON j.time_id = t.id
        WHERE t.nome = ?
    ");
    $stmt->execute([$nome_do_time]);
    $jogadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($jogadores);
} else {
    echo json_encode([]);
}