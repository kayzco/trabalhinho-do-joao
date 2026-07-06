<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

require_once "config.php";

$id_usuario = $_SESSION['id'];

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$id_time = filter_input(INPUT_POST, 'id_time', FILTER_VALIDATE_INT);
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

if (!$nome || !$id_time) {
    header("Location: dashboard.php?aba=perfil&erro=campos_vazios");
    exit;
}

// Resgata a foto atual caso ele não queira mudar de foto agora
$nomeImagem = $_SESSION['foto_perfil'] ?? null;

// Lógica de upload idêntica ao salvar-personagem.php (Reaproveitamento de código de cria!)
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
    $pastaUpload = "uploads/";

    if (!is_dir($pastaUpload)) {
        mkdir($pastaUpload, 0755, true);
    }

    $extensao = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extensao, $extensoesPermitidas)) {
        header("Location: dashboard.php?aba=perfil&erro=extensao");
        exit;
    }

    if ($_FILES['foto_perfil']['size'] > 2 * 1024 * 1024) {
        header("Location: dashboard.php?aba=perfil&erro=tamanho");
        exit;
    }

    // Nome totalmente aleatório e único para a foto do perfil
    $nomeImagem = "avatar_" . md5(uniqid(rand(), true)) . "." . $extensao;
    $caminhoCompleto = $pastaUpload . $nomeImagem;

    if (!move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminhoCompleto)) {
        header("Location: dashboard.php?aba=perfil&erro=upload");
        exit;
    }
}

try {
    // Dá o UPDATE incluindo o campo da foto de perfil
    $sql = "UPDATE usuario SET nome = :nome, id_time = :id_time, descricao = :descricao, foto_perfil = :foto_perfil WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nome' => $nome,
        'id_time' => $id_time,
        'descricao' => $descricao,
        'foto_perfil' => $nomeImagem,
        'id' => $id_usuario
    ]);

    // Atualiza a sessão para mudar na tela imediatamente após o salvamento
    $_SESSION['nome'] = $nome;
    $_SESSION['id_time'] = $id_time;
    $_SESSION['descricao'] = $descricao;
    $_SESSION['foto_perfil'] = $nomeImagem; // Grava o nome da foto nova na sessão

    header("Location: dashboard.php?aba=perfil&sucesso=atualizado");
    exit;

} catch (PDOException $e) {
    die("Erro ao atualizar o perfil: " . $e->getMessage());
}