<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/JogadorController.php';

$equipe_id = $_GET['equipe_id'] ?? null;

if (!$equipe_id) {
    echo "Equipe não encontrada.";
    exit;
}

$jogadorController = new JogadorController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $posicao = $_POST['posicao'] ?? '';

    if (!empty($nome)) {
        try {
            $jogadorController->criarJogador([
                'nome' => $nome,
                'posicao' => $posicao,
                'equipe_id' => $equipe_id
            ]);
            header("Location: gerenciar_jogadores.php?id=$equipe_id&success=1");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao adicionar jogador: " . $e->getMessage();
            exit;
        }
    } else {
        echo "O nome do jogador é obrigatório.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Jogador</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white p-6 rounded-lg shadow w-full max-w-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Adicionar Jogador</h2>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1">Nome do Jogador</label>
                <input type="text" name="nome" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Posição</label>
                <input type="text" name="posicao" class="w-full border rounded px-3 py-2" placeholder="Ex: Atacante">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Cadastrar Jogador
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="gerenciar_jogadores.php?id=<?= htmlspecialchars($equipe_id) ?>" class="text-blue-600 hover:underline">
                Voltar
            </a>
        </div>
    </div>
</body>
</html>
