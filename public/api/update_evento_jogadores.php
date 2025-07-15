<?php

require_once __DIR__ . '/../../config/database.php';

$id_jogo = $_POST['id_jogo'];
$nome_jogador = $_POST['nome_jogador'];
$gols = $_POST['gols'];
$gols_sofrifos = $_POST['gols_sofridos'];
$cartao_amarelo = $_POST['cartao_amarelo'];
$cartao_vermelho = $_POST['cartao_vermelho'];

// Aqui você faz o UPDATE no banco
$sql = "UPDATE ranking
        SET nome_jogador = ?, gols = ?, cartao_amarelo = ?, cartao_vermelho = ?
        WHERE id_jogo = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siiii", $nome_jogador, $gols, $cartao_amarelo, $cartao_vermelho, $id_jogo);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}