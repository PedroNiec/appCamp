<?php
require_once __DIR__ . '/../../config/database.php';

$stmt = $pdo->query("SELECT id, nome FROM times ORDER BY nome");
$times = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($times);

