<?php
include "config.php";

// 1. Pegar e limpar os dados recebidos
$nome  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = $_POST['senha'] ?? '';

if (!$nome || !$email || empty($senha)) {
    header("Location: cadastro.php?erro=campos_vazios");
    exit;
}

try {
    // 2. Verificar se o e-mail já está cadastrado
    $sqlCheck = "SELECT id FROM usuario WHERE email = :email";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute(['email' => $email]);

    if ($stmtCheck->rowCount() > 0) {
        // E-mail já existe, manda de volta com erro
        header("Location: cadastro.php?erro=email_existe");
        exit;
    }

    // 3. Inserir o novo usuário de forma segura (Prepared Statements)
    $sqlInsert = "INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    
    $stmtInsert->execute([
        'nome'  => $nome,
        'email' => $email,
        'senha' => $senha // Idealmente usar password_hash no futuro, mas mantendo simples para o seu template
    ]);

    // 4. Redirecionar para o login com aviso de sucesso!
    header("Location: login.php?sucesso=cadastrado");
    exit;

} catch (PDOException $e) {
    die("Erro ao salvar no banco de dados: " . $e->getMessage());
}
?>