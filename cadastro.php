<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Criar Conta - Haikyuu!! Manager</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#0f172a,#1e293b,#312e81);
    padding:40px;
}

body::before{
    content:"";
    position:fixed;
    width:450px;
    height:450px;
    background:#f97316;
    filter:blur(170px);
    opacity:.15;
    top:-120px;
    left:-120px;
}

body::after{
    content:"";
    position:fixed;
    width:450px;
    height:450px;
    background:#2563eb;
    filter:blur(170px);
    opacity:.15;
    bottom:-120px;
    right:-120px;
}

.form-box{

    position:relative;
    z-index:2;

    width:100%;
    max-width:470px;

    background:rgba(30,41,59,.78);

    backdrop-filter:blur(15px);

    border-radius:20px;

    padding:40px;

    border:1px solid rgba(255,255,255,.08);

    box-shadow:0 20px 40px rgba(0,0,0,.35);

    animation:fade .7s ease;

}

h2{

    color:#f97316;
    text-align:center;
    margin-bottom:8px;

}

.subtitulo{

    text-align:center;
    color:#94a3b8;
    margin-bottom:30px;
    font-size:14px;

}

.field{

    margin-bottom:18px;

}

label{

    display:block;
    margin-bottom:8px;
    color:#cbd5e1;
    font-size:14px;

}

input,
textarea,
select{

    width:100%;

    padding:13px;

    border-radius:8px;

    border:1px solid #475569;

    background:#0f172a;

    color:white;

    transition:.3s;

    resize:none;

}

input::placeholder,
textarea::placeholder{

    color:#94a3b8;

}

input:focus,
textarea:focus,
select:focus{

    outline:none;

    border-color:#f97316;

    box-shadow:0 0 12px rgba(249,115,22,.35);

}

.btn-salvar{

    width:100%;

    padding:14px;

    border:none;

    border-radius:8px;

    background:#f97316;

    color:white;

    font-size:15px;

    font-weight:600;

    cursor:pointer;

    transition:.3s;

    margin-top:10px;

}

.btn-salvar:hover{

    background:#ea580c;

    transform:translateY(-2px);

    box-shadow:0 10px 20px rgba(249,115,22,.35);

}

.alert-erro{

    background:#dc2626;

    color:white;

    padding:12px;

    border-radius:8px;

    text-align:center;

    margin-bottom:20px;

}

.voltar{

    display:block;

    text-align:center;

    margin-top:25px;

    color:#f97316;

    text-decoration:none;

    transition:.3s;

}

.voltar:hover{

    color:white;

}

@keyframes fade{

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

<div class="form-box">

<h2>🏐 Criar Conta</h2>

<p class="subtitulo">
Cadastre-se para começar sua jornada no Haikyuu!! Manager.
</p>

<?php if (isset($_GET['erro']) && $_GET['erro'] == 'email_existe'): ?>
<div class="alert-erro">
Esse e-mail já está cadastrado.
</div>
<?php endif; ?>

<?php if (isset($_GET['erro']) && $_GET['erro'] == 'campos_vazios'): ?>
<div class="alert-erro">
Preencha todos os campos obrigatórios.
</div>
<?php endif; ?>

<form action="salvarusuario.php" method="POST">

<div class="field">
<label>Nome</label>
<input
type="text"
name="nome"
placeholder="Digite seu nome completo"
required>
</div>

<div class="field">
<label>E-mail</label>
<input
type="email"
name="email"
placeholder="Digite seu e-mail"
required>
</div>

<div class="field">
<label>Senha</label>
<input
type="password"
name="senha"
placeholder="Crie uma senha"
required>
</div>

<div class="field">
<label>Descrição</label>
<textarea
name="descricao"
rows="4"
placeholder="Conte um pouco sobre você..."></textarea>
</div>

<div class="field">
<label>Escolha seu time</label>
<select name="id_time">
<option value="1">🦅 Karasuno</option>
<option value="2">🐈 Nekoma</option>
<option value="3">🌱 Aoba Johsai</option>
<option value="6">👑 Shiratorizawa</option>
</select>
</div>

<button type="submit" class="btn-salvar">
Cadastrar Conta
</button>

</form>

<a href="index.php" class="voltar">
← Voltar para a página inicial
</a>

</div>

</body>
</html>