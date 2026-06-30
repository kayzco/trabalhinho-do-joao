<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
require_once "config.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Haikyuu!!</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f172a;
            color: #f8fafc;
            margin: 0;
            padding: 0;
        }

        /* Topo do Painel */
        header {
            background: #1e293b;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #f97316;
        }

        header h1 {
            margin: 0;
            color: #f97316;
            font-size: 1.8rem;
        }

        .user-info a {
            color: #ef4444;
            text-decoration: none;
            font-weight: bold;
            margin-left: 15px;
        }

        /* Menu de Abas */
        .tabs-menu {
            display: flex;
            background: #1e293b;
            padding: 10px 20px 0 20px;
            gap: 10px;
        }

        .tab-btn {
            background: #334155;
            color: #94a3b8;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: bold;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .tab-btn:hover {
            background: #475569;
            color: white;
        }

        .tab-btn.active {
            background: #f97316;
            color: white;
        }

        /* Conteúdo das Abas */
        .container {
            padding: 30px;
        }

        .tab-content {
            display: none; /* Esconde todas por padrão */
            background: #1e293b;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .tab-content.active {
            display: block; /* Só mostra a aba ativa */
        }

        h2 {
            color: #f97316;
            margin-top: 0;
            border-bottom: 1px solid #334155;
            padding-bottom: 10px;
        }

        /* Estilos da Tabela do Inventário */
        .tabela-inventario {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #1e293b;
        }
        
        .tabela-inventario th, .tabela-inventario td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #334155;
        }
        
        .tabela-inventario th {
            background-color: #334155;
            color: #f97316;
        }
        
        .tabela-inventario tr:hover {
            background-color: #475569;
        }
        
        .btn-excluir {
            color: #ef4444;
            text-decoration: none;
            font-weight: bold;
        }
        
        .btn-excluir:hover { 
            text-decoration: underline; 
        }

        /* Estilos do Formulário */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #94a3b8;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 6px;
            color: white;
            box-sizing: border-box;
        }
        .btn-submit {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-submit:hover { background: #1d4ed8; }
    </style>
</head>
<body>

<header>
    <h1>🏐 Karasuno</h1>
    <div class="user-info">
        <span>Bem-vinda, <strong><?php echo $_SESSION['nome']; ?></strong>!</span>
        <a href="logout.php">Sair</a>
    </div>
</header>

<div class="tabs-menu">
    <button class="tab-btn active" onclick="openTab('inventario')">🎒 Inventário</button>
    <button class="tab-btn" onclick="openTab('cadastro')">📝 Cadastrar Jogador</button>
    <button class="tab-btn" onclick="openTab('explicacao')">🏐 Sobre o Projeto</button>
    <button class="tab-btn" onclick="openTab('perfil')">⚙️ Meu Perfil</button>
</div>

<div class="container">

    <div id="inventario" class="tab-content active">
        <h2>🎒 Inventário de Personagens</h2>
        <p>Estes são os jogadores cadastrados no sistema:</p>

        <?php
        try {
            // Consulta limpa relacionando a coluna id_posicao do personagem com o id da tabela posicao
            $stmt = $pdo->query("SELECT personagem.*, posicao.nome AS nome_posicao 
                                 FROM personagem, posicao 
                                 WHERE personagem.id_posicao = posicao.id 
                                 ORDER BY personagem.id DESC");
            $personagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($personagens) > 0): 
        ?>
            <table class="tabela-inventario">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Jogador</th>
                        <th>Posição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($personagens as $p): ?>
                        <tr>
                            <td>#<?php echo $p['id']; ?></td>
                            <td><strong><?php echo $p['nome']; ?></strong></td>
                            <td><?php echo $p['nome_posicao']; ?></td>
                            <td>
                                <a href="excluir-personagem.php?id=<?php echo $p['id']; ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja apagar esse jogador?')">❌ Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>
            <p style="color: #94a3b8; font-style: italic; margin-top: 20px;">Nenhum jogador encontrado no banco de dados. Vá na aba "Cadastrar Jogador" para adicionar o primeiro!</p>
        <?php 
            endif; 
        } catch (PDOException $e) {
            echo "<p style='color: #ef4444;'>Erro ao carregar inventário: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>

    <div id="cadastro" class="tab-content">
        <h2>📝 Cadastrar Novo Jogador</h2>
        <form action="salvar-personagem.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nome do Jogador:</label>
                <input type="text" name="nome" required placeholder="Ex: Shoyo Hinata">
            </div>
            <div class="form-group">
                <label>Posição:</label>
                <select name="posicao">
                    <option value="1">Central</option>
                    <option value="2">Levantador</option>
                    <option value="3">Ponteiro</option>
                    <option value="4">Oposto</option>
                    <option value="5">Líbero</option>
                </select>
            </div>
            <button type="submit" class="btn-submit">Salvar Jogador</button>
        </form>
    </div>

    <div id="explicacao" class="tab-content">
        <h2>🏐 Sobre o Sistema Haikyuu!!</h2>
        <p>Este sistema é um CRUD (Create, Read, Update, Delete) desenvolvido para gerenciar informações de times e jogadores de Vôlei baseados no anime Haikyuu!!.</p>
        <p>Desenvolvido como projeto escolar/acadêmico para aplicar conceitos de PHP, Programação Orientada a Objetos (POO) e Bancos de Dados.</p>
    </div>

    <div id="perfil" class="tab-content">
        <h2>⚙️ Personalização do Perfil</h2>
        <p>Gerencie as configurações da sua conta de administrador.</p>
        <div class="form-group">
            <label>Seu Nome:</label>
            <input type="text" value="<?php echo htmlspecialchars($_SESSION['nome']); ?>" disabled>
        </div>
        <p style="color: #94a3b8; font-size: 0.9rem;">* Recursos de alteração de senha e avatar em breve!</p>
    </div>

</div>

<script>
function openTab(tabId) {
    // Esconde todas as abas
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.classList.remove('active'));

    // Desativa todos os botões
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(button => button.classList.remove('active'));

    // Mostra a aba clicada e ativa o botão dela
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}
</script>

</body>
</html>