<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Topbar -->
    <header class="bg-white shadow-md flex items-center justify-between px-6 py-4">
        <!-- Logo -->
        <div class="text-lg font-bold text-blue-700">
            LOGO APP CAMP
        </div>

        <!-- Menu -->
        <nav class="flex space-x-4">
            <a href="gerenciar_competicoes.php" class="text-gray-700 hover:text-green-700 font-bold">Gerenciar Competições</a>
            <a href="times.php" class="text-gray-700 hover:text-green-700 font-bold">Gerenciar Times</a>
            <a href="logout.php" class="text-gray-700 hover:text-red-700 font-bold">Sair</a>
        </nav>
    </header>

    <!-- Conteúdo central -->
    <main class="flex items-center justify-center h-[calc(100vh-72px)]">
        <div class="text-center">
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
            <p class="text-gray-600">Utilize o menu acima para gerenciar suas competições.</p>
            <a href="gerenciar_competicoes.php" class="text-green-700 hover:text-green-700 font-bold">Tabela</a> <br>
            <a href="gerenciar_competicoes.php" class="text-gray-700 hover:text-green-700 font-bold">Gerenciar Competições</a>

        </div>
    </main>

</body>
</html>
