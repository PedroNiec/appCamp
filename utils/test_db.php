<?php

require_once __DIR__ . '/../config/database.php';

echo "<h2>Teste de Conexão com o Supabase (PostgreSQL)</h2>";

try {
    $stmt = $pdo->query("SELECT NOW() as data_atual;");
    $row = $stmt->fetch();

    echo "<p><strong>Conexão bem-sucedida!</strong></p>";
    echo "<p>Data e hora do servidor PostgreSQL: " . $row['data_atual'] . "</p>";
} catch (PDOException $e) {
    echo "<p><strong>Erro na conexão:</strong> " . $e->getMessage() . "</p>";
}
?>
