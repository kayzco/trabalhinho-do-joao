<?php
session_start();
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if ($email && !empty($senha)) {
        try {
            // ✨ IMPORTANTE: O SELECT garante que estamos pegando o 'id_time' e a 'descricao'
            $sql = "SELECT id, nome, email, senha, descricao, id_time FROM usuario WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Se o usuário existir e a senha bater (ajuste aqui caso use password_verify)
            if ($usuario && $senha === $usuario['senha']) {
                
                // Grava tudo na sessão
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['descricao'] = $usuario['descricao'];
                
                // ✨ AQUI ESTÁ A MÁGICA DAS CORES: Salvando o time ao logar!
                $_SESSION['id_time'] = $usuario['id_time']; 

                // Manda direto para o dashboard com tudo funcionando
                header("Location: dashboard.php");
                exit;
            } else {
                // Senha errada ou usuário não existe
                header("Location: login.php?erro=1");
                exit;
            }
        } catch (PDOException $e) {
            die("Erro no sistema: " . $e->getMessage());
        }
    } else {
        header("Location: login.php?erro=1");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}



