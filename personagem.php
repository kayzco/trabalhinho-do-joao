<?php

namespace App\Entity;

class Personagem {
    private ?int $id = null;
    private string $nome;
    private int $idade;
    private float $altura;
    private int $numero;
    private string $descricao;
    private int $idTime;
    private int $idFuncao;
    private int $idPosicao;
    private ?string $imagem;
    private int $idUsuario;
    private array $tags = []; 

    public function __construct(
        ?int $id = null,
        string $nome = '',
        int $idade = 0,
        float $altura = 0.0,
        int $numero = 0,
        string $descricao = '',
        int $idTime = 0,
        int $idFuncao = 0,
        int $idPosicao = 0,
        ?string $imagem = null,
        int $idUsuario = 0,
        array $tags = []
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->setIdade($idade);    
        $this->setAltura($altura); 
        $this->numero = $numero;
        $this->descricao = $descricao;
        $this->idTime = $idTime;
        $this->idFuncao = $idFuncao;
        $this->idPosicao = $idPosicao;
        $this->imagem = $imagem;
        $this->idUsuario = $idUsuario;
        $this->tags = $tags;
    }

    
    public function setIdade(int $idade): void {
        if ($idade < 12 || $idade > 30) {
            throw new \Exception("Idade inválida para um estudante/atleta escolar (permitido entre 12 e 30 anos).");
        }
        $this->idade = $idade;
    }

    public function setAltura(float $altura): void {
        if ($altura < 1.30 || $altura > 2.50) {
            throw new \Exception("Altura fora dos padrões físicos permitidos para a liga.");
        }
        $this->altura = $altura;
    }

    public function setIdUsuario(int $idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    public function getId(): ?int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getIdade(): int { return $this->idade; }
    public function getAltura(): float { return $this->altura; }
    public function getNumero(): int { return $this->numero; }
    public function getDescricao(): string { return $this->descricao; }
    public function getIdTime(): int { return $this->idTime; }
    public function getIdFuncao(): int { return $this->idFuncao; }
    public function getIdPosicao(): int { return $this->idPosicao; }
    public function getImagem(): ?string { return $this->imagem; }
    public function getIdUsuario(): int { return $this->idUsuario; }
    public function getTags(): array { return $this->tags; }
}