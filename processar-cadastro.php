<?php
require_once "config.php";
session_start();

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha']; 
$descricao = $_POST['descricao'];
$id_time = $_POST['id_time'];

try {
    $sql = "INSERT INTO usuario (nome, email, senha, descricao, id_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $senha, $descricao, $id_time]);

    // Busca o ID que acabou de ser criado para logar
    $id_novo = $pdo->lastInsertId();

    // Cria a sessão
    $_SESSION['id'] = $id_novo;
    $_SESSION['nome'] = $nome;
    $_SESSION['id_time'] = $id_time;

    header("Location: dashboard.php");
} catch (PDOException $e) {
    echo "Erro ao cadastrar: " . $e->getMessage();
}