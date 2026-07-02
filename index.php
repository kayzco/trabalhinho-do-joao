<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Haikyuu!! Manager</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

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
    width:500px;
    height:500px;
    background:#f97316;
    filter:blur(180px);
    opacity:.15;
    top:-150px;
    left:-100px;
}

body::after{
    content:"";
    position:absolute;
    width:450px;
    height:450px;
    background:#2563eb;
    filter:blur(180px);
    opacity:.18;
    bottom:-150px;
    right:-100px;
}

.welcome-box{

    position:relative;
    z-index:10;

    width:420px;
    padding:45px;

    text-align:center;

    border-radius:20px;

    background:rgba(30,41,59,.65);
    backdrop-filter:blur(15px);

    border:1px solid rgba(255,255,255,.1);

    box-shadow:0 20px 40px rgba(0,0,0,.35);

    animation:fade .8s ease;
}

.logo{

    font-size:70px;
    margin-bottom:15px;

}

h1{

    color:#f97316;
    margin-bottom:10px;
    font-size:2.3rem;

}

p{

    color:#cbd5e1;
    margin-bottom:35px;
    line-height:1.6;

}

.btn{

    display:block;

    width:100%;

    padding:15px;

    border-radius:10px;

    text-decoration:none;

    font-weight:600;

    transition:.3s;

    margin-top:15px;

}

.btn-login{

    background:#f97316;
    color:white;

}

.btn-login:hover{

    transform:translateY(-3px);
    box-shadow:0 0 20px rgba(249,115,22,.5);

}

.btn-cadastro{

    border:2px solid #f97316;
    color:#f97316;

}

.btn-cadastro:hover{

    background:#f97316;
    color:white;
    transform:translateY(-3px);

}

footer{

    margin-top:30px;
    font-size:.8rem;
    color:#94a3b8;

}

@keyframes fade{

    from{

        opacity:0;
        transform:translateY(30px);

    }

    to{

        opacity:1;
        transform:translateY(0);

    }

}

</style>

</head>

<body>

<div class="welcome-box">

    <div class="logo">🏐</div>

    <h1>Haikyuu!! Manager</h1>

    <p>
        Gerencie jogadores, equipes e estatísticas de forma simples,
        rápida e organizada.
    </p>

    <a href="login.php" class="btn btn-login">
        Fazer Login
    </a>

    <a href="cadastro.php" class="btn btn-cadastro">
        Criar Conta
    </a>

    <footer>
        Haikyuu!! Manager • Sistema de gerenciamento de equipes
    </footer>

</div>

</body>
</html>