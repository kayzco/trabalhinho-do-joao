<?php

namespace App\Repository;

use App\Entity\Personagem;
use PDO;

class PersonagemRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodosPorUsuario(int $idUsuario, $time = 'todos'): array {
        $sql = "SELECT p.*, t.nome as nome_time, pos.nome as nome_posicao, f.nome as nome_funcao
                FROM personagem p
                INNER JOIN time t ON p.id_time = t.id
                INNER JOIN posicao pos ON p.id_posicao = pos.id
                INNER JOIN funcao f ON p.id_funcao = f.id
                WHERE p.id_usuario = :id_usuario";
        
        if ($time !== 'todos') {
            $sql .= " AND p.id_time = :id_time";
        }
        $sql .= " ORDER BY p.nome ASC";

        $stmt = $this->pdo->prepare($sql);
        $params = ['id_usuario' => $idUsuario];
        if ($time !== 'todos') {
            $params['id_time'] = $time;
        }
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar(Personagem $p): bool {
        $this->pdo->beginTransaction();
        try {
            $sql = "INSERT INTO personagem 
                (nome, idade, altura, numero, descricao, id_time, id_funcao, id_posicao, imagem, id_usuario) 
                VALUES (:nome, :idade, :altura, :numero, :descricao, :id_time, :id_funcao, :id_posicao, :imagem, :id_usuario)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
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

            $idPersonagem = $this->pdo->lastInsertId();

            // Salva o Relacionamento N:N com as Tags selecionadas
            if (!empty($p->getTags())) {
                $sqlTag = "INSERT INTO personagem_tag (id_personagem, id_tag) VALUES (:id_personagem, :id_tag)";
                $stmtTag = $this->pdo->prepare($sqlTag);
                foreach ($p->getTags() as $idTag) {
                    $stmtTag->execute(['id_personagem' => $idPersonagem, 'id_tag' => $idTag]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function buscarPorId(int $id, int $idUsuario): ?array {
        $sql = "SELECT * FROM personagem WHERE id = :id AND id_usuario = :id_usuario";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'id_usuario' => $idUsuario]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            // Busca as tags associadas a esse personagem (N:N)
            $sqlTags = "SELECT id_tag FROM personagem_tag WHERE id_personagem = :id";
            $stmtTags = $this->pdo->prepare($sqlTags);
            $stmtTags->execute(['id' => $id]);
            $dados['tags'] = $stmtTags->fetchAll(PDO::FETCH_COLUMN);
            return $dados;
        }
        return null;
    }

    public function atualizar(Personagem $p): bool {
        $this->pdo->beginTransaction();
        try {
            $sql = "UPDATE personagem SET 
                    nome = :nome, idade = :idade, altura = :altura, numero = :numero, 
                    descricao = :descricao, id_time = :id_time, id_funcao = :id_funcao, 
                    id_posicao = :id_posicao, imagem = :imagem
                    WHERE id = :id AND id_usuario = :id_usuario";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nome' => $p->getNome(),
                'idade' => $p->getIdade(),
                'altura' => $p->getAltura(),
                'numero' => $p->getNumero(),
                'descricao' => $p->getDescricao(),
                'id_time' => $p->getIdTime(),
                'id_funcao' => $p->getIdFuncao(),
                'id_posicao' => $p->getIdPosicao(),
                'imagem' => $p->getImagem(),
                'id' => $p->getId(),
                'id_usuario' => $p->getIdUsuario()
            ]);

            // Atualiza o Relacionamento N:N (Limpa os antigos e insere os novos)
            $stmtClean = $this->pdo->prepare("DELETE FROM personagem_tag WHERE id_personagem = ?");
            $stmtClean->execute([$p->getId()]);

            if (!empty($p->getTags())) {
                $sqlTag = "INSERT INTO personagem_tag (id_personagem, id_tag) VALUES (:id_personagem, :id_tag)";
                $stmtTag = $this->pdo->prepare($sqlTag);
                foreach ($p->getTags() as $idTag) {
                    $stmtTag->execute(['id_personagem' => $p->getId(), 'id_tag' => $idTag]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function excluir(int $id, int $idUsuario): bool {
        // Devido à restrição de chave estrangeira, removemos os vínculos na N:N antes
        $stmtClean = $this->pdo->prepare("DELETE FROM personagem_tag WHERE id_personagem = ?");
        $stmtClean->execute([$id]);

        $sql = "DELETE FROM personagem WHERE id = :id AND id_usuario = :id_usuario";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'id_usuario' => $idUsuario]);
    }
}