<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Haikyuu!! - Criar Conta</title>
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
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 300px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: #f97316; /* Laranja Haikyuu */
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
            margin-bottom: 5px;
            color: #1e293b;
        }

        .links {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .links a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Criar Conta</h2>
    
    <?php if (isset($_GET['erro']) && $_GET['erro'] == 'email_existe'): ?>
        <p style="color: #ef4444; text-align: center; font-size: 14px;">Este email já está cadastrado!</p>
    <?php endif; ?>
    
    <?php if (isset($_GET['erro']) && $_GET['erro'] == 'campos_vazios'): ?>
        <p style="color: #ef4444; text-align: center; font-size: 14px;">Preencha todos os campos!</p>
    <?php endif; ?>

    <form action="salvar-usuario.php" method="POST">
        <input type="text" name="nome" placeholder="Nome Completo" required>
        <input type="email" name="email" placeholder="Seu Email" required>
        <input type="password" name="senha" placeholder="Sua Senha" required>
        <button type="submit">Cadastrar</button>
    </form>

    <div class="links">
        <a href="login.php">Já tem uma conta? Entrar</a>
    </div>
</div>

</body>
</html>