<?php
// auth.php
session_start();
require_once "config.php";
require_once "usuario.php";
require_once "usuariorepository.php";

use App\Repository\UsuarioRepository;

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    header("Location: login.php?erro=1");
    exit;
}

$repo = new UsuarioRepository($pdo);

$usuario = $repo->buscarPorEmailESenha($email, $senha);

if ($usuario) {
    $_SESSION['id'] = $usuario->getId();
    $_SESSION['nome'] = $usuario->getNome();
    
    $_SESSION['id_time'] = $dados['id_time'] ?? 1; // Salva o ID correto do time na sessão
    $_SESSION['descricao'] = $dados['descricao'] ?? ''; 
    
    header("Location: dashboard.php");
    exit;
} else {
    header("Location: login.php?erro=1");
    exit;
}


