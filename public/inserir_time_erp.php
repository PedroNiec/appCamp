<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');

    if ($nome === '') {
        $error = "O nome do time é obrigatório.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO times (nome, categoria) VALUES (:nome, :categoria)");
            $stmt->execute([
                ':nome' => $nome,
                ':categoria' => $categoria !== '' ? $categoria : null
            ]);
            header("Location: times.php?success=1");
            exit;
        } catch (PDOException $e) {
            $error = "Erro ao cadastrar time: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Novo Time</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white p-6 rounded-lg shadow w-full max-w-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Adicionar Novo Time</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1">Nome do Time <span class="text-red-500">*</span></label>
                <input type="text" name="nome" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Digite o nome do time">
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Categoria (opcional)</label>
                <input type="text" name="categoria" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Ex: Sub-15, Feminino, Livre...">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Cadastrar Time
            </button>
        </form>

        <div class="mt-4 flex justify-center">
            <a href="times.php" class="text-blue-600 hover:underline">Voltar</a>
        </div>
    </div>
</body>
</html>
