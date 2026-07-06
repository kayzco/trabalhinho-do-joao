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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Haikyuu!! Manager</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#0f172a,#1e293b,#312e81);
    overflow:hidden;
}

body::before{
    content:"";
    position:absolute;
    width:400px;
    height:400px;
    background:#f97316;
    filter:blur(160px);
    opacity:.15;
    top:-120px;
    left:-120px;
}

body::after{
    content:"";
    position:absolute;
    width:400px;
    height:400px;
    background:#2563eb;
    filter:blur(160px);
    opacity:.15;
    bottom:-120px;
    right:-120px;
}

.box{

    position:relative;
    z-index:2;

    width:380px;

    padding:40px;

    border-radius:20px;

    background:rgba(30,41,59,.75);

    backdrop-filter:blur(15px);

    border:1px solid rgba(255,255,255,.08);

    box-shadow:0 20px 45px rgba(0,0,0,.35);

    animation:aparecer .7s ease;

}

.logo{

    font-size:60px;
    text-align:center;
    margin-bottom:10px;

}

h2{

    text-align:center;
    color:#f97316;
    margin-bottom:8px;

}

.subtitulo{

    text-align:center;
    color:#94a3b8;
    margin-bottom:25px;
    font-size:14px;

}

input{

    width:100%;
    padding:14px;

    margin-top:15px;

    border-radius:8px;

    border:1px solid #475569;

    background:#0f172a;

    color:white;

    transition:.3s;

}

input::placeholder{

    color:#94a3b8;

}

input:focus{

    outline:none;

    border-color:#f97316;

    box-shadow:0 0 12px rgba(249,115,22,.35);

}

button{

    width:100%;

    padding:14px;

    margin-top:22px;

    border:none;

    border-radius:8px;

    background:#f97316;

    color:white;

    font-size:15px;

    font-weight:600;

    cursor:pointer;

    transition:.3s;

}

button:hover{

    transform:translateY(-2px);

    background:#ea580c;

    box-shadow:0 10px 20px rgba(249,115,22,.35);

}

.links{

    text-align:center;

    margin-top:25px;

}

.links a{

    color:#f97316;

    text-decoration:none;

    transition:.3s;

}

.links a:hover{

    color:white;

}

.erro{

    color:#ef4444;

    text-align:center;

    margin-bottom:15px;

    font-size:14px;

}

.sucesso{

    color:#10b981;

    text-align:center;

    margin-bottom:15px;

    font-size:14px;

}

@keyframes aparecer{

    from{

        opacity:0;
        transform:translateY(25px);

    }

    to{

        opacity:1;
        transform:translateY(0);

    }

}

</style>

</head>

<body>

<div class="box">

    <div class="logo">🏐</div>

    <h2>Haikyuu!!</h2>

    <p class="subtitulo">
        Faça login para acessar o sistema.
    </p>

    <?php if (isset($_GET['erro'])): ?>
        <div class="erro">
            Email ou senha inválidos.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'cadastrado'): ?>
        <div class="sucesso">
            Conta criada com sucesso! Faça seu login.
        </div>
    <?php endif; ?>

    <form action="auth.php" method="POST">

        <input
            type="email"
            name="email"
            placeholder="Digite seu e-mail"
            required>

        <input
            type="password"
            name="senha"
            placeholder="Digite sua senha"
            required>

        <button type="submit">
            Entrar
        </button>

    </form>

    <div class="links">
        <a href="cadastro.php">
            Não possui uma conta? Cadastre-se
        </a>
    </div>

</div>

</body>
</html>