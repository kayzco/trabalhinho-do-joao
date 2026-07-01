<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
require_once "config.php";

// Recebe os dados que vieram do formulário de edição
$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? null;
$id_posicao = $_POST['id_posicao'] ?? null;

if ($id && $nome && $id_posicao) {
    try {
        // Atualiza o jogador com os dados novos usando o ID dele
        $stmt = $pdo->prepare("UPDATE personagem SET nome = ?, id_posicao = ? WHERE id = ?");
        $stmt->execute([$nome, $id_posicao, $id]);
        
        // Se deu tudo certo, volta para o painel principal
        header("Location: dashboard.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao atualizar no banco: " . $e->getMessage();
    }
} else {
    echo "Por favor, preencha todos os campos!";
}