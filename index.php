<?php
session_start();
// Verifica se o utilizador já está logado para mudar os botões na página inicial
$logado = isset($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Haikyuu!! - Banco de Dados de Personagens</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #f8fafc;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            text-align: center;
            background: #1e293b;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
        }

        h1 {
            color: #f97316; /* Laranja característico de Haikyuu!! */
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        p {
            color: #94a3b8;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .btn-dashboard {
            background: #f97316;
        }

        .btn-dashboard:hover {
            background: #ea580c;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🏐 Haikyuu!!</h1>
    <p>Bem-vinda ao sistema de gestão de personagens, equipas e estatísticas do universo de Haikyuu!!</p>

    <?php if ($logado): ?>
        <p style="color: #10b981;">Já iniciou sessão como <strong><?php echo htmlspecialchars($_SESSION['nome']); ?></strong>.</p>
        <a href="dashboard.php" class="btn btn-dashboard">Ir para o Painel</a>
    <?php else: ?>
        <a href="login.php" class="btn">Entrar no Sistema</a>
    <?php endif; ?>
</div>

</body>
</html>