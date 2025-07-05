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
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Competição</title>
</head>
<body>
    <h2>Criar Competição</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Modalidade:</label><br>
        <select name="modalidade_id" required>
            <option value="">Selecione</option>
            <?php
            $modalidades = $pdo->query("SELECT id, nome FROM modalidades ORDER BY nome")->fetchAll();
            foreach ($modalidades as $mod) {
                echo "<option value='{$mod['id']}'>{$mod['nome']}</option>";
            }
            ?>
        </select><br><br>

         <label>Formato competição:</label><br>
         <select name="formato_competicao_id" required>
            <option value="">Selecione</option>
            <?php
            $formatos = $pdo->query("SELECT id, nome FROM formatos_competicoes ORDER BY nome")->fetchAll();
            foreach ($formatos as $value) {
                echo "<option value='{$value['id']}'>{$value['nome']}</option>";
            }
            ?>
        </select><br><br>
        

        <label>Local:</label><br>
        <input type="text" name="local" required><br><br>

        <label>Data de Início:</label><br>
        <input type="date" name="data_inicio" required><br><br>

        <label>Data de Fim:</label><br>
        <input type="date" name="data_fim" required><br><br>

        <label>Categoria:</label><br>
        <input type="text" name="categoria"><br><br>

        <label>Regras:</label><br>
        <textarea name="regras"></textarea><br><br>

        <label>Upload de Regulamento (PDF):</label><br>
        <input type="file" name="regulamento" accept="application/pdf"><br><br>

        <button type="submit">Cadastrar Competição</button>
    </form>
</body>
</html>
