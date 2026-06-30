<?php

namespace App\Repository;

// Precisamos puxar a classe do usuário aqui para o repositório conhecê-la!
require_once "usuario.php"; 

use App\Entity\Usuario;
use PDO;

class UsuarioRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function buscarPorEmailESenha(string $email, string $senha): ?Usuario {
        // Usando prepared statements para evitar SQL Injection
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE email = :email AND senha = :senha");
        $stmt->execute(['email' => $email, 'senha' => $senha]);
        
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return null;
        }

        return new Usuario($dados['id'], $dados['nome'], $dados['email']);
    }
}