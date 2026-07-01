<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Haikyuu!! Manager</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #0f172a; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; text-align: center; }
        .welcome-box { background: #1e293b; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border-bottom: 5px solid #f97316; }
        h1 { color: #f97316; font-size: 2.5rem; margin-bottom: 10px; }
        p { color: #94a3b8; margin-bottom: 30px; }
        .btn { display: inline-block; padding: 12px 30px; margin: 10px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .btn-login { background: #f97316; color: white; }
        .btn-cadastro { border: 2px solid #f97316; color: #f97316; }
        .btn:hover { transform: scale(1.05); opacity: 0.9; }
    </style>
</head>
<body>
    <div class="welcome-box">
        <h1>🏐 Haikyuu System</h1>
        <p>Gerencie seus jogadores e times favoritos!</p>
        <a href="login.php" class="btn btn-login">Fazer Login</a>
        <a href="cadastro.php" class="btn btn-cadastro">Criar Conta</a>
    </div>
</body>
</html>