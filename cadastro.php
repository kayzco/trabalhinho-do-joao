<?php
// Deixei apenas o início da sessão simples se precisar
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta - Haikyuu</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #0f172a; color: white; padding: 50px; }
        .form-box { background: #1e293b; max-width: 400px; margin: 0 auto; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        h2 { color: #f97316; text-align: center; margin-top: 0; }
        .field { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #94a3b8; }
        input, select, textarea { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: white; box-sizing: border-box; }
        .btn-salvar { width: 100%; padding: 12px; background: #f97316; border: none; color: white; font-weight: bold; cursor: pointer; border-radius: 6px; margin-top: 10px; font-size: 1rem; }
        .btn-salvar:hover { background: #ea580c; }
        .alert-erro { background: #ef4444; color: white; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem; text-align: center; }
        .voltar { display: block; text-align: center; margin-top: 15px; color: #94a3b8; text-decoration: none; font-size: 0.9rem; }
        .voltar:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>📝 Criar sua Conta</h2>

        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'email_existe'): ?>
            <div class="alert-erro">Esse e-mail já está cadastrado!</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'campos_vazios'): ?>
            <div class="alert-erro">Preencha todos os campos!</div>
        <?php endif; ?>

        <form action="salvarusuario.php" method="POST">
            <div class="field">
                <label>Nome:</label>
                <input type="text" name="nome" required placeholder="Seu nome completo">
            </div>
            <div class="field">
                <label>E-mail:</label>
                <input type="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="field">
                <label>Senha:</label>
                <input type="password" name="senha" required placeholder="Crie uma senha">
            </div>
            <div class="field">
                <label>Sua Descrição:</label>
                <textarea name="descricao" rows="3" placeholder="Fale um pouco sobre você..."></textarea>
            </div>
            <div class="field">
                <label>Escolha seu Time:</label>
                <select name="id_time">
                    <option value="1">🦅 Karasuno</option>
                    <option value="2">🐈 Nekoma</option>
                    <option value="3">🌱 Aoba Johsai</option>
                    <option value="6">👑 Shiratorizawa</option>
                </select>
            </div>
            <button type="submit" class="btn-salvar">Cadastrar Conta</button>
        </form>

        <a href="index.php" class="voltar">Voltar para o Início</a>
    </div>
</body>
</html>