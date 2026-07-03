<?php
include "config.php";

$nome      = $_POST['nome'] ?? '';
$email     = $_POST['email'] ?? '';
$senha     = $_POST['senha'] ?? '';
$descricao = $_POST['descricao'] ?? '';

$id_time   = isset($_POST['id_time']) ? (int)$_POST['id_time'] : 1;

if (empty($nome) || empty($email) || empty($senha)) {
    header("Location: cadastro.php?erro=campos_vazios");
    exit;
}

try {
    $sqlCheck = "SELECT id FROM usuario WHERE email = :email";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute(['email' => $email]);

    if ($stmtCheck->rowCount() > 0) {
        header("Location: cadastro.php?erro=email_existe");
        exit;
    }

    $sqlInsert = "INSERT INTO usuario (nome, email, senha, descricao, id_time) VALUES (:nome, :email, :senha, :descricao, :id_time)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    
    $stmtInsert->execute([
        'nome'      => $nome,
        'email'     => $email,
        'senha'     => $senha, 
        'descricao' => $descricao,
        'id_time'   => $id_time 
    ]);

    header("Location: login.php?sucesso=cadastrado");
    exit;
} catch (PDOException $e) {
    die("Erro ao salvar no banco de dados: " . $e->getMessage());
}