<?php

namespace App\Repository;

use App\Entity\Personagem;
use PDO;

class PersonagemRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Buscar todos os personagens juntando os dados com as chaves estrangeiras
    public function listarTodos(): array {
        $sql = "SELECT p.*, t.nome as nome_time, pos.nome as nome_posicao, f.nome as nome_funcao 
                FROM personagem p
                INNER JOIN time t ON p.id_time = t.id
                INNER JOIN posicao pos ON p.id_posicao = pos.id
                INNER JOIN funcao f ON p.id_funcao = f.id
                ORDER BY p.nome ASC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Inserir um novo personagem no banco (CREATE do CRUD)
    public function salvar(Personagem $p): bool {
        $sql = "INSERT INTO personagem (nome, idade, altura, numero, descrição, id_time, id_funcao, id_posicao, imagem) 
                VALUES (:nome, :idade, :altura, :numero, :descricao, :id_time, :id_funcao, :id_posicao, :imagem)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nome' => $p->getNome(),
            'idade' => $p->getIdade(),
            'altura' => $p->getAltura(),
            'numero' => $p->getNumero(),
            'descricao' => $p->getDescricao(),
            'id_time' => $p->getIdTime(),
            'id_funcao' => $p->getIdFuncao(),
            'id_posicao' => $p->getIdPosicao(),
            'imagem' => $p->getImagem()
        ]);
    }

    // Deletar um personagem do banco (DELETE do CRUD)
    public function excluir(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM personagem WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}