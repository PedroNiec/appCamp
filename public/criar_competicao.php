<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/CompeticaoController.php';

$controller = new CompeticaoController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->criarCompeticao($_POST, $_FILES);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Competição</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen px-4">

    <div class="bg-white w-full max-w-xl rounded-xl shadow-md p-8 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 text-center">Criar Nova Competição</h2>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">

            <!-- Nome -->
            <div class="flex flex-col">
                <label class="text-gray-700 mb-1">Nome</label>
                <input type="text" name="nome" required
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <!-- Modalidade -->
            <div class="flex flex-col">
                <label class="text-gray-700 mb-1">Modalidade</label>
                <select name="modalidade_id" required
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">Selecione</option>
                    <?php
                    $modalidades = $pdo->query("SELECT id, nome FROM modalidades ORDER BY nome")->fetchAll();
                    foreach ($modalidades as $mod) {
                        echo "<option value='{$mod['id']}'>{$mod['nome']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Formato -->
            <div class="flex flex-col">
                <label class="text-gray-700 mb-1">Formato da Competição</label>
                <select name="formato_competicao_id" required
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">Selecione</option>
                    <?php
                    $formatos = $pdo->query("SELECT id, nome FROM formatos_competicoes ORDER BY nome")->fetchAll();
                    foreach ($formatos as $value) {
                        echo "<option value='{$value['id']}'>{$value['nome']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Qtd Times e Grupos -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex flex-col flex-1">
                    <label class="text-gray-700 mb-1">Qtd. de Equipes</label>
                    <input type="number" name="qtd_times" required
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div class="flex flex-col flex-1">
                    <label class="text-gray-700 mb-1">Qtd. de Grupos</label>
                    <input type="number" name="qtd_grupos" required
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>

            <!-- Local -->
            <div class="flex flex-col">
                <label class="text-gray-700 mb-1">Local</label>
                <input type="text" name="local" required
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <!-- Data Início e Fim -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex flex-col flex-1">
                    <label class="text-gray-700 mb-1">Data de Início</label>
                    <input type="date" name="data_inicio" required
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div class="flex flex-col flex-1">
                    <label class="text-gray-700 mb-1">Data de Fim</label>
                    <input type="date" name="data_fim" required
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>

            <!-- Categoria -->
            <div class="flex flex-col">
                <label class="text-gray-700 mb-1">Categoria</label>
                <input type="text" name="categoria"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <!-- Regras -->
            <div class="flex flex-col">
                <label class="text-gray-700 mb-1">Regras</label>
                <textarea name="regras" rows="3"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"></textarea>
            </div>

            <!-- Regulamento -->
            <div class="flex flex-col">
                <label class="text-gray-700 mb-1">Upload de Regulamento (PDF)</label>
                <input type="file" name="regulamento" accept="application/pdf"
                    class="mt-1 text-gray-700">
            </div>

            <!-- Botão -->
            <div class="pt-4 text-center">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    Cadastrar Competição
                </button>
            </div>
        </form>
            
    </div>
</body>
</html>
