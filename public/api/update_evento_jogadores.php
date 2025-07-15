<?php

require_once __DIR__ . '/../../config/database.php';

$id_jogo = $_POST['id_jogo'];
$time = $_POST['nome_time'];
$nome_jogador = $_POST['nome_jogador'];
$gols = $_POST['gols'];
$gols_sofridos = $_POST['gols_sofridos'];
$cartao_amarelo = $_POST['cartao_amarelo'];
$cartao_vermelho = $_POST['cartao_vermelho'];

var_dump('TESTEEEE');
// 1. Buscar o ID da competição baseado no id_jogo
$query = "SELECT competicao_id FROM jogos WHERE id = ?";
$stmt_comp = $conn->prepare($query);
$stmt_comp->bind_param("i", $id_jogo);
$stmt_comp->execute();
$stmt_comp->bind_result($competicao_id);
$stmt_comp->fetch();
$stmt_comp->close();

// Verifica se encontrou a competição
if (!$competicao_id) {
    echo json_encode(["success" => false, "message" => "Competição não encontrada para o jogo."]);
    exit;
}

// 2. Inserir na tabela ranking_jogador
$sql = "INSERT INTO ranking_jogador 
        (id_jogo, nome_time, nome_jogador, gols, gols_sofridos, cartao_amarelo, cartao_vermelho, competicao_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issiiiii", 
    $id_jogo, 
    $time, 
    $nome_jogador, 
    $gols, 
    $gols_sofridos, 
    $cartao_amarelo, 
    $cartao_vermelho, 
    $competicao_id
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>