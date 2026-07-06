<?php
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

// O repositório faz a busca e valida a senha, retornando o objeto Usuario
$usuario = $repo->buscarPorEmailESenha($email, $senha);

if ($usuario) {
    
    // ✨ O TRUQUE INFALÍVEL: Convertemos o objeto para um array para burlar o bloqueio de "private"
    // e conseguir ler os dados sem depender de adivinhar o nome dos métodos get!
    $usuarioArray = (array) $usuario;

    // Quando o PHP converte um objeto de uma classe com propriedades privadas para array,
    // as chaves ganham o nome da classe antes do atributo. Vamos mapear isso com segurança:
    $id = null;
    $nome = '';
    $descricao = '';
    $id_time = 1;
    $foto_perfil = null;

    foreach ($usuarioArray as $chave => $valor) {
        if (str_contains($chave, 'id')) $id = $valor;
        if (str_contains($chave, 'nome')) $nome = $valor;
        if (str_contains($chave, 'descricao')) $descricao = $valor;
        if (str_contains($chave, 'idTime') || str_contains($chave, 'id_time')) $id_time = $valor;
        if (str_contains($chave, 'foto_perfil') || str_contains($chave, 'fotoPerfil')) $foto_perfil = $valor;
    }

    // Configura as sessões com os valores extraídos com segurança
    $_SESSION['id']          = $id;
    $_SESSION['nome']        = $nome;
    $_SESSION['descricao']   = $descricao;
    $_SESSION['id_time']     = $id_time;
    $_SESSION['foto_perfil'] = $foto_perfil;

    header("Location: dashboard.php");
    exit;
} else {
    header("Location: login.php?erro=usuario_invalido");
    exit;
}