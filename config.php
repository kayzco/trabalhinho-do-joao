<?php
$host = "localhost";
$db = "haikyuu"; // nome do seu banco
$user = "root";
$pass = "";

try {
    // Criação da conexão via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Configura o PDO para disparar exceções em caso de erros no SQL (ajuda muito a debugar)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>