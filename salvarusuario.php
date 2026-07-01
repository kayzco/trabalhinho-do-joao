<?php
include "config.php";

// Pegando os dados do formulário
$nome      = $_POST['nome'] ?? '';
$email     = $_POST['email'] ?? '';
$senha     = $_POST['senha'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$id_time   = $_POST['id_time'] ?? 1;

// Validação simples nível nota 7
if (empty($nome) || empty($email) || empty($senha)) {
    header("Location: cadastro.php?erro=campos_vazios");
    exit;
}

try {
    // Verificar se o e-mail já existe
    $sqlCheck = "SELECT id FROM usuario WHERE email = :email";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute(['email' => $email]);

    if ($stmtCheck->rowCount() > 0) {
        header("Location: cadastro.php?erro=email_existe");
        exit;
    }

    // Salvando tudo no banco (incluindo descrição e time para a mágica das cores funcionar)
    $sqlInsert = "INSERT INTO usuario (nome, email, senha, descricao, id_time) VALUES (:nome, :email, :senha, :descricao, :id_time)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    
    $stmtInsert->execute([
        'nome'      => $nome,
        'email'     => $email,
        'senha'     => $senha,
        'descricao' => $descricao,
        'id_time'   => $id_time
    ]);

    // Redireciona para o login com sucesso
    header("Location: login.php?sucesso=cadastrado");
    exit;

} catch (PDOException $e) {
    die("Erro ao salvar no banco: " . $e->getMessage());
}
?>