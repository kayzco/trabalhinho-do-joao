<?php
session_start();
if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Haikyuu!!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .box {
            background: #1e293b; /* Mudado para combinar com o tema escuro do dashboard */
            padding: 30px;
            border-radius: 12px;
            width: 300px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            border: 1px solid #334155;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            box-sizing: border-box;
            border: 1px solid #334155;
            background: #0f172a;
            color: white;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background: #f97316; /* Laranja padrão inicial */
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #ea580c;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #f97316;
        }

        .links {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .links a {
            color: #94a3b8;
            text-decoration: none;
        }

        .links a:hover {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Login</h2>

    <?php if (isset($_GET['erro'])): ?>
        <p style="color: #ef4444; text-align: center; font-size: 14px; margin-bottom: 10px;">
            Email ou senha inválidos.
        </p>
    <?php endif; ?>

    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'cadastrado'): ?>
        <p style="color: #10b981; text-align: center; font-size: 14px; margin-bottom: 10px;">
            Conta criada com sucesso! Faça seu login.
        </p>
    <?php endif; ?>

    <form action="auth.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>

    <div class="links">
        <a href="cadastro.php">Não tem conta? Cadastre-se</a> 
    </div>

</div> 
</body>
</html>