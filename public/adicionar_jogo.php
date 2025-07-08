<?php
require_once __DIR__ . '/../config/database.php';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Adicionar Jogo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded-lg shadow w-full max-w-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Adicionar Jogo</h2>

        <form id="formEquipe" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1">Competição</label>
                <select name="competicao_id" id="competicaoSelect" required class="w-full border rounded px-3 py-2">
                    <option value="">Carregando...</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Time A</label>
                <select name="time_a_id" id="timeASelect" required class="w-full border rounded px-3 py-2">
                    <option value="">Carregando...</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Time B</label>
                <select name="time_b_id" id="timeBSelect" required class="w-full border rounded px-3 py-2">
                    <option value="">Carregando...</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Rodada</label>
                <input type="number" name="rodada" class="w-full border rounded px-3 py-2" />
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Cadastrar Jogo
            </button>
        </form>
    </div>

    <script src="../js/adicionarJogo.js"></script>
</body>
</html>

