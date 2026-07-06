<?php
include "config.php";

$nome  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = $_POST['senha'] ?? '';

if (!$nome || !$email || empty($senha)) {
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

    $sqlInsert = "INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    
    $stmtInsert->execute([
        'nome'  => $nome,
        'email' => $email,
        'senha' => $senha 
    ]);

    header("Location: login.php?sucesso=cadastrado");
    exit;

} catch (PDOException $e) {
    die("Erro ao salvar no banco de dados: " . $e->getMessage());
}
?>