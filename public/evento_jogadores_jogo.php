<?php
require_once __DIR__ . '/../config/database.php';

$jogo_id = $_GET['id'] ?? null;
if (!$jogo_id) {
    echo "Jogo não encontrado."; exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Eventos dos Jogadores</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="bg-white p-6 rounded-lg shadow w-full max-w-2xl">
        <h1 class="text-xl font-semibold text-gray-800 mb-4 text-center">Evento jogadores</h1>


    <input type="hidden" id="jogoIdInput" value="<?php echo htmlspecialchars($jogo_id); ?>">

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

    <div>
        <label class="block text-gray-700 mb-1">Gols feito</label>
        <input type="number" name="gols" value="" min= "0" class="w-full border rounded px-3 py-2">
    </div>

     <div>
        <label class="block text-gray-700 mb-1">Gols Sofridos</label>
        <input type="number" name="gols_sofridos" value="" min="0" class="w-full border rounded px-3 py-2">
    </div>

    
     <div>
        <label class="block text-gray-700 mb-1">Cartões Amarelos</label>
        <input type="number" name="placar_b" value="" min="0" class="w-full border rounded px-3 py-2">
    </div>

    
     <div>
        <label class="block text-gray-700 mb-1">Cartão Vermelho</label>
        <input type="number" name="placar_b" value="" min="0" class="w-full border rounded px-3 py-2">
    </div>

    

    <div id="jogadoresContainer" class="space-y-2 mt-4"></div>

    <button id="btnSalvar" type="button" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Salvar Alterações</button>

     <script src="/js/evento_jogadores.js"></script>

</body>
</html>