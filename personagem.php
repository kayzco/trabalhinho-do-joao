<?php

namespace App\Entity;

class Personagem {
    private ?int $id;
    private string $nome;
    private int $idade;
    private float $altura;
    private int $numero;
    private string $descricao;
    private int $idTime;
    private int $idFuncao;
    private int $idPosicao;
    private ?string $imagem;
    private ?int $idUsuario; // ✨ Adicionado o atributo para o Usuário Dono

    public function __construct(
        ?int $id, string $nome, int $idade, float $altura, int $numero, 
        string $descricao, int $idTime, int $idFuncao, int $idPosicao, ?string $imagem = null
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->idade = $idade;
        $this->altura = $altura;
        $this->numero = $numero;
        $this->descricao = $descricao;
        $this->idTime = $idTime;
        $this->idFuncao = $idFuncao;
        $this->idPosicao = $idPosicao;
        $this->imagem = $imagem;
        $this->idUsuario = null; // Inicializa vazio por padrão
    }

    // Getters e Setters para o PHP conseguir ler e gravar os dados protegidos
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
    
    // ✨ Métodos novos para o id_usuario
    public function getIdUsuario(): ?int { return $this->idUsuario; }
    public function setIdUsuario(?int $idUsuario): void { $this->idUsuario = $idUsuario; }
}