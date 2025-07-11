<?php
require_once __DIR__ . '/../config/database.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Eventos dos Jogadores</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Eventos dos Jogadores -->
    <h3 class="text-lg font-semibold text-center mt-6 mb-2">Eventos dos Jogadores</h3>
    <div>
        <label class="block text-gray-700 mb-1">Selecione o Time</label>
        <select id="timeSelect" class="w-full border rounded px-3 py-2">
            <option value="">Selecione</option>
        </select>
    </div>

    <div>
        <label class="block text-gray-700 mb-1">Selecione um Jogador</label>
        <select id="jogadorSelect" class="w-full border rounded px-3 py-2">
            <option value="">Selecione um jogador</option>
        </select>
    </div>

    <script src="/appCamp/js/editar_jogo.js"></script>

    <div id="jogadoresContainer" class="space-y-2 mt-4"></div>

    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Salvar Alterações</button>
</body>
</html>