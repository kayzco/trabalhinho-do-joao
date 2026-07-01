<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
require_once "config.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: dashboard.php");
    exit;
}

// Busca os dados atuais do jogador
$stmt = $pdo->prepare("SELECT * FROM personagem WHERE id = ?");
$stmt->execute([$id]);
$personagem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$personagem) {
    echo "Jogador não encontrado!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Jogador - Haikyuu!!</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #0f172a; color: #f8fafc; padding: 40px; }
        .box { background: #1e293b; padding: 30px; border-radius: 12px; max-width: 500px; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        h2 { color: #f97316; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #94a3b8; }
        input, select { width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: white; box-sizing: border-box; }
        .btn-submit { background: #2563eb; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; }
        .btn-submit:hover { background: #1d4ed8; }
        .voltar { display: block; text-align: center; margin-top: 15px; color: #94a3b8; text-decoration: none; }
    </style>
</head>
<body>

<div class="box">
    <h2>✏️ Editar Jogador</h2>
    
    <form action="atualizar-personagem.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $personagem['id']; ?>">

        <div class="form-group">
            <label>Nome do Jogador:</label>
            <input type="text" name="nome" value="<?php echo $personagem['nome']; ?>" required>
        </div>

        <div class="form-group">
            <label>Posição:</label>
            <select name="id_posicao">
                <option value="1" <?php if($personagem['id_posicao'] == 1) echo 'selected'; ?>>Líbero</option>
                <option value="2" <?php if($personagem['id_posicao'] == 2) echo 'selected'; ?>>Ponteiro</option>
                <option value="3" <?php if($personagem['id_posicao'] == 3) echo 'selected'; ?>>Levantador</option>
                <option value="4" <?php if($personagem['id_posicao'] == 4) echo 'selected'; ?>>Central</option>
                <option value="5" <?php if($personagem['id_posicao'] == 5) echo 'selected'; ?>>Oposto</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">Salvar Alterações</button>
    </form>
    
    <a href="dashboard.php" class="voltar">Voltar para o Painel</a>
</div>

</body>
</html>