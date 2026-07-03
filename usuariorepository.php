<?php

namespace App\Repository;

require_once "usuario.php"; 

use App\Entity\Usuario;
use PDO;

class UsuarioRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function buscarPorEmailESenha(string $email, string $senha): ?Usuario {
        // Comparação direta de email e senha em texto limpo
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE email = :email AND senha = :senha");
        $stmt->execute(['email' => $email, 'senha' => $senha]);
        
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return null;
        }

        return new Usuario($dados['id'], $dados['nome'], $dados['email']);
    }
}