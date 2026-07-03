<?php

namespace App\Repository;

require_once "personagem.php";

use App\Entity\Personagem;
use PDO;

class PersonagemRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // 🔥 ESSE É O MÉTODO QUE ESTÁ FALTANDO
    public function listarTodosPorUsuario(int $idUsuario, $timeFiltrado = 'todos'): array {

        $sql = "SELECT 
                    p.*,
                    t.nome AS nome_time,
                    pos.nome AS nome_posicao,
                    f.nome AS nome_funcao
                FROM personagem p
                INNER JOIN time t ON p.id_time = t.id
                INNER JOIN posicao pos ON p.id_posicao = pos.id
                INNER JOIN funcao f ON p.id_funcao = f.id
                WHERE p.id_usuario = :id_usuario";

        if ($timeFiltrado !== 'todos') {
            $sql .= " AND p.id_time = :id_time";
        }

        $sql .= " ORDER BY p.nome ASC";

        $stmt = $this->pdo->prepare($sql);

        if ($timeFiltrado !== 'todos') {
            $stmt->execute([
                'id_usuario' => $idUsuario,
                'id_time' => $timeFiltrado
            ]);
        } else {
            $stmt->execute([
                'id_usuario' => $idUsuario
            ]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar(Personagem $p): bool {
        $sql = "INSERT INTO personagem
            (nome, idade, altura, numero, descricao, id_time, id_funcao, id_posicao, imagem, id_usuario)
            VALUES
            (:nome, :idade, :altura, :numero, :descricao, :id_time, :id_funcao, :id_posicao, :imagem, :id_usuario)";

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

    public function buscarPorId(int $id, int $idUsuario): ?array {
        $sql = "SELECT * FROM personagem WHERE id = :id AND id_usuario = :id_usuario";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'id_usuario' => $idUsuario
        ]);

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ?: null;
    }

    public function atualizar(int $id, int $idUsuario, string $nome, int $idPosicao): bool {
        $sql = "UPDATE personagem 
                SET nome = :nome, id_posicao = :id_posicao
                WHERE id = :id AND id_usuario = :id_usuario";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nome' => $nome,
            'id_posicao' => $idPosicao,
            'id' => $id,
            'id_usuario' => $idUsuario
        ]);
    }

    public function excluir(int $id, int $idUsuario): bool {
        $sql = "DELETE FROM personagem WHERE id = :id AND id_usuario = :id_usuario";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'id_usuario' => $idUsuario
        ]);
    }
}