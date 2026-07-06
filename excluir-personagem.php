<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
require_once "config.php";
require_once "personagemrepository.php";

use App\Repository\PersonagemRepository;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $repo = new PersonagemRepository($pdo);
    if ($repo->excluir($id, $_SESSION['id'])) {
        header("Location: dashboard.php?sucesso=excluido");
        exit;
    } else {
        echo "Erro ao excluir o jogador através do Repositório.";
        exit;
    }
}
header("Location: dashboard.php");
exit;