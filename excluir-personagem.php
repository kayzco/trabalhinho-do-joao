<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
require_once "config.php";

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM personagem WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        echo "Erro ao excluir: " . $e->getMessage();
        exit;
    }
}

header("Location: dashboard.php");
exit; No newline at end of file