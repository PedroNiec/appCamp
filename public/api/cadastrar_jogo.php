<?php



require_once __DIR__ . '/../../config/database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $competicao_id = $_POST['competicao_id'] ?? null;
    $time_a_id = $_POST['time_a_id'] ?? null;
    $time_b_id = $_POST['time_b_id'] ?? null;
    $rodada = $_POST['rodada'] ?? null;

    var_dump($competicao_id);
    var_dump($time_a_id);
    var_dump($time_b_id);


    if ($competicao_id !== null && $competicao_id !== '' &&
        $time_a_id !== null && $time_a_id !== '' &&
        $time_b_id !== null && $time_b_id !== '') {
        try {
            $stmt = $pdo->prepare("INSERT INTO jogos (competicao_id, time_a_id, time_b_id, categoria, created_at) VALUES (:competicao_id, :time_a_id, :time_b_id, :rodada, NOW())");
            $stmt->execute([
                ':competicao_id' => $competicao_id,
                ':time_a_id' => $time_a_id,
                ':time_b_id' => $time_b_id,
                ':rodada' => $rodada
            ]);

            header("Location: gerenciar_jogos.php?success=1");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao cadastrar jogo: " . $e->getMessage();
            exit;
        }
    } else {
        echo "Todos os campos obrigatórios devem ser preenchidos.";
    }
} else {
    echo "Método inválido.";
}
