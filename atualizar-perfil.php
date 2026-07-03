<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

require_once "config.php";

$id_usuario = $_SESSION['id'];

// Captura e sanitiza as entradas
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$id_time = filter_input(INPUT_POST, 'id_time', FILTER_VALIDATE_INT);
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

if (!$nome || !$id_time) {
    header("Location: dashboard.php?aba=perfil&erro=campos_vazios");
    exit;
}

try {
    // Faz o UPDATE diretamente na tabela de utilizadores
    $sql = "UPDATE usuario SET nome = :nome, id_time = :id_time, descricao = :descricao WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nome' => $nome,
        'id_time' => $id_time,
        'descricao' => $descricao,
        'id' => $id_usuario
    ]);

    // ✨ TRUQUE MÁGICO: Atualiza as variáveis de sessão para refletir as mudanças na hora!
    $_SESSION['nome'] = $nome;
    $_SESSION['id_time'] = $id_time;
    $_SESSION['descricao'] = $descricao;

    // Redireciona de volta para a aba perfil com mensagem de sucesso
    header("Location: dashboard.php?aba=perfil&sucesso=atualizado");
    exit;

} catch (PDOException $e) {
    die("Erro ao atualizar o perfil: " . $e->getMessage());
}