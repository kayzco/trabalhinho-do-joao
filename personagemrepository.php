<?php

namespace App\Repository;

use App\Entity\Personagem;
use PDO;

class PersonagemRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Mantivemos a antiga caso algum outro arquivo seu use ela
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

    // ✨ A FUNÇÃO QUE ESTAVA FALTANDO LOGO AQUI:
    public function listarTodosPorUsuario(int $idUsuarioLogado, string $timeFiltrado = 'todos'): array {
        $sql = "SELECT p.*, t.nome as nome_time, pos.nome as nome_posicao, f.nome as nome_funcao 
                FROM personagem p
                INNER JOIN time t ON p.id_time = t.id
                INNER JOIN posicao pos ON p.id_posicao = pos.id
                INNER JOIN funcao f ON p.id_funcao = f.id
                WHERE (p.id_usuario IS NULL OR p.id_usuario = :id_usuario)";
        
        if ($timeFiltrado !== 'todos') {
            $sql .= " AND p.id_time = :id_time";
        }

        $sql .= " ORDER BY p.id DESC"; // Mostra os mais novos primeiro
        
        $stmt = $this->pdo->prepare($sql);
        
        $parametros = ['id_usuario' => $idUsuarioLogado];
        if ($timeFiltrado !== 'todos') {
            $parametros['id_time'] = $timeFiltrado;
        }

        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Inserir um novo personagem no banco (CREATE do CRUD)
    public function salvar(Personagem $p): bool {
        $sql = "INSERT INTO personagem (nome, idade, altura, numero, descricao, id_time, id_funcao, id_posicao, imagem, id_usuario) 
                VALUES (:nome, :idade, :altura, :numero, :descricao, :id_time, :id_funcao, :id_posicao, :imagem, :id_usuario)";
        
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
            'imagem' => $p->getImagem(),
            'id_usuario' => $p->getIdUsuario()
        ]);
    }

    // Deletar um personagem do banco (DELETE do CRUD)
    public function excluir(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM personagem WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}