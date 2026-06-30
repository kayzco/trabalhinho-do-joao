<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

require_once "config.php";
require_once "personagem.php";
require_once "personagemrepository.php";

use App\Entity\Personagem;
use App\Repository\PersonagemRepository;

// 1. Pegar dados e higienizar
$nome       = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$idade      = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT);
$numero     = filter_input(INPUT_POST, 'numero', FILTER_VALIDATE_INT);
$altura     = filter_input(INPUT_POST, 'altura', FILTER_VALIDATE_FLOAT);
$id_time    = filter_input(INPUT_POST, 'id_time', FILTER_VALIDATE_INT);
$id_posicao = filter_input(INPUT_POST, 'id_posicao', FILTER_VALIDATE_INT);
$id_funcao  = filter_input(INPUT_POST, 'id_funcao', FILTER_VALIDATE_INT);
$descricao  = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

// Verificar se campos obrigatórios estão preenchidos
if (!$nome || !$idade || !$numero || !$altura || !$id_time || !$id_posicao || !$id_funcao) {
    header("Location: form-personagem.php?erro=campos_vazios");
    exit;
}

$nomeImagem = null;

// 2. Sistema de Processamento de Upload da Imagem
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
    $pastaUpload = "uploads/";
    
    // Se a pasta uploads não existir, cria ela automaticamente
    if (!is_dir($pastaUpload)) {
        mkdir($pastaUpload, 0755, true);
    }

    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $extesoesPermitidas = ['jpg', 'jpeg', 'png'];

    if (!in_array($extensao, $extesoesPermitidas)) {
        header("Location: form-personagem.php?erro=extensao");
        exit;
    }

    // Gera um nome criptografado único para o arquivo não duplicar
    $nomeImagem = md5(uniqid(rand(), true)) . "." . $extensao;
    $caminhoCompleto = $pastaUpload . $nomeImagem;

    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
        header("Location: form-personagem.php?erro=upload");
        exit;
    }
}

try {
    // 3. Instanciar a Entity Personagem com a nova estrutura de dados
    $personagem = new Personagem(
        null, $nome, $idade, $altura, $numero, $descricao, $id_time, $id_funcao, $id_posicao, $nomeImagem
    );

    // 4. Salvar usando o Repository
    $personagemRepo = new PersonagemRepository($pdo);
    
    if ($personagemRepo->salvar($personagem)) {
        header("Location: dashboard.php?sucesso=cadastrado");
        exit;
    } else {
        header("Location: form-personagem.php?erro=banco");
        exit;
    }

} catch (Exception $e) {
    die("Erro grave do sistema: " . $e->getMessage());
}