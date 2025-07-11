<?php

require_once __DIR__ . '/../../config/database.php';

$time_id = isset($_GET['time_id']) ? intval($_GET['time_id']) : 0;

if ($time_id > 0) {
    $stmt = $pdo->prepare("SELECT id, nome FROM jogadores WHERE time_id = ?");
    $stmt->execute([$time_id]);
    $jogadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($jogadores);
} else {
    echo json_encode([]);
}
