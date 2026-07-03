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

$id         = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome       = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$idade      = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT);
$numero     = filter_input(INPUT_POST, 'numero', FILTER_VALIDATE_INT);
$altura     = filter_input(INPUT_POST, 'altura', FILTER_VALIDATE_FLOAT);
$id_time    = filter_input(INPUT_POST, 'id_time', FILTER_VALIDATE_INT);
$id_posicao = filter_input(INPUT_POST, 'id_posicao', FILTER_VALIDATE_INT);
$id_funcao  = filter_input(INPUT_POST, 'id_funcao', FILTER_VALIDATE_INT);
$descricao  = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
$tags       = $_POST['tags'] ?? [];

if (!$id || !$nome || !$idade || !$numero || !$altura || !$id_time || !$id_posicao || !$id_funcao) {
    header("Location: editar-personagem.php?id=$id&erro=campos_vazios");
    exit;
}

try {
    $repo = new PersonagemRepository($pdo);
    $nomeImagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $pastaUpload = "uploads/";
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $nomeImagem = md5(uniqid(rand(), true)) . "." . $extensao;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $pastaUpload . $nomeImagem);
    }
    $personagem = new Personagem(
        $id, 
        $nome, 
        $idade, 
        $altura, 
        $numero, 
        $descricao, 
        $id_time, 
        $id_funcao, 
        $id_posicao, 
        $nomeImagem
    );
    
    $personagem->setIdUsuario($_SESSION['id']);

    if (method_exists($personagem, 'setTags')) {
        $personagem->setTags($tags);
    }
    
    $personagem->setIdUsuario($_SESSION['id']);

    if ($repo->atualizar($personagem)) {
        header("Location: dashboard.php?sucesso=atualizado");
        exit;
    } else {
        header("Location: editar-personagem.php?id=$id&erro=banco");
        exit;
    }

} catch (Exception $e) {
    die("Erro grave ao atualizar sistema: " . $e->getMessage());
}