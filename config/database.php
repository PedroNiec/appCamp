<?php

$host = 'aws-0-sa-east-1.pooler.supabase.com';
$dbname = 'postgres';
$user = 'postgres.yifvsuhwildukaxmkxyj';
$password = 'appCamp123';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
        PDO::ATTR_EMULATE_PREPARES => false,              
    ]);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>

