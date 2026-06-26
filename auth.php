<?php
session_start();
require_once "config.php";
require_once "usuario.php";           // Caminho para a sua Entity Usuario
require_once "usuariorepository.php"; // Caminho para o seu Repository

use App\Repository\UsuarioRepository;

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = $_POST['senha'] ?? '';

if (!$email || empty($senha)) {
    header("Location: login.php?erro=1");
    exit;
}

// Passa a conexão PDO ($pdo) criada no config.php para o Repository
$usuarioRepo = new UsuarioRepository($pdo);
$usuario = $usuarioRepo->buscarPorEmailESenha($email, $senha);

if ($usuario) {
    // Se achou o usuário, salva os dados vindos da Entity na Sessão
    $_SESSION['id'] = $usuario->getId();
    $_SESSION['nome'] = $usuario->getNome();

    header("Location: dashboard.php");
    exit;
} else {
    // Se falhar, volta com erro
    header("Location: login.php?erro=1");
    exit;
}



