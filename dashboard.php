<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
require_once "config.php";

// ✨ IMPORTANTE: Incluindo as classes necessárias para o repositório funcionar no inventário
require_once "personagem.php";
require_once "personagemrepository.php";
use App\Repository\PersonagemRepository;

// Captura qual aba está ativa (se não passar nada, o padrão é 'inventario')
$aba_atual = $_GET['aba'] ?? 'inventario';

// Captura qual time está filtrado (se não passar nada, o padrão é 'todos')
$time_filtrado = $_GET['time'] ?? 'todos';

// --- SISTEMA DE CORES DINÂMICO ---
// Se por acaso não tiver o id_time na sessão, ele usa o 1 (Karasuno) como padrão
$meu_time = $_SESSION['id_time'] ?? 1;

$cor_tema = "#f97316"; 
$nome_meu_time = "Karasuno";
$emoji_time = "🦅";

if ($meu_time == 2) {
    $cor_tema = "#ef4444"; // Nekoma
    $nome_meu_time = "Nekoma";
    $emoji_time = "🐈";
} elseif ($meu_time == 3) {
    $cor_tema = "#0d9488"; // Aoba Johsai
    $nome_meu_time = "Aoba Johsai";
    $emoji_time = "🌱";
} elseif ($meu_time == 6) {
    $cor_tema = "#86198f"; // Shiratorizawa
    $nome_meu_time = "Shiratorizawa";
    $emoji_time = "👑";
}
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
            border-bottom: 3px solid <?php echo $cor_tema; ?>; /* Cor dinâmica */
        }

        header h1 {
            margin: 0;
            color: <?php echo $cor_tema; ?>; /* Cor dinâmica */
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

        .tab-link {
            background: #334155;
            color: #94a3b8;
            text-decoration: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: bold;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .tab-link:hover {
            background: #475569;
            color: white;
        }

        .tab-link.active {
            background: <?php echo $cor_tema; ?>; /* Cor dinâmica */
            color: white;
        }

        /* Conteúdo das Abas */
        .container {
            padding: 30px;
        }

        .tab-content {
            display: none; 
            background: #1e293b;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .tab-content.active {
            display: block; 
        }

        h2 {
            color: <?php echo $cor_tema; ?>; /* Cor dinâmica */
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
            color: <?php echo $cor_tema; ?>; /* Cor dinâmica */
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

        /* Estilos do Filtro de Times */
        .btn-filtro {
            background: #334155;
            color: #f8fafc;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .btn-filtro.active-filter {
            background: <?php echo $cor_tema; ?>; /* Cor dinâmica */
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
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 6px;
            color: white;
            box-sizing: border-box;
        }
        .btn-submit {
            background: <?php echo $cor_tema; ?>; /* Cor dinâmica */
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-submit:hover { opacity: 0.9; }
    </style>
</head>
<body>

<header>
    <h1><?php echo $emoji_time . " " . $nome_meu_time; ?></h1>
    <div class="user-info">
        <span>Bem-vinda, <strong><?php echo $_SESSION['nome']; ?></strong>!</span>
        <a href="logout.php">Sair</a>
    </div>
</header>

<div class="tabs-menu">
    <a href="dashboard.php?aba=inventario" class="tab-link <?php echo ($aba_atual == 'inventario') ? 'active' : ''; ?>">🎒 Inventário</a>
    <a href="dashboard.php?aba=cadastro" class="tab-link <?php echo ($aba_atual == 'cadastro') ? 'active' : ''; ?>">📝 Cadastrar Jogador</a>
    <a href="dashboard.php?aba=explicacao" class="tab-link <?php echo ($aba_atual == 'explicacao') ? 'active' : ''; ?>">🏐 Sobre o Projeto</a>
    <a href="dashboard.php?aba=perfil" class="tab-link <?php echo ($aba_atual == 'perfil') ? 'active' : ''; ?>">⚙️ Meu Perfil</a>
</div>

<div class="container">

    <div id="inventario" class="tab-content <?php echo ($aba_atual == 'inventario') ? 'active' : ''; ?>">
        <h2>🎒 Inventário de Personagens</h2>
        
        <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="dashboard.php?aba=inventario&time=todos" class="btn-filtro <?php echo ($time_filtrado == 'todos') ? 'active-filter' : ''; ?>">🏐 Todos</a>
            <a href="dashboard.php?aba=inventario&time=1" class="btn-filtro <?php echo ($time_filtrado == '1') ? 'active-filter' : ''; ?>">🦅 Karasuno</a>
            <a href="dashboard.php?aba=inventario&time=2" class="btn-filtro <?php echo ($time_filtrado == '2') ? 'active-filter' : ''; ?>">🐈 Nekoma</a>
            <a href="dashboard.php?aba=inventario&time=3" class="btn-filtro <?php echo ($time_filtrado == '3') ? 'active-filter' : ''; ?>">🌱 Aoba Johsai</a>
            <a href="dashboard.php?aba=inventario&time=4" class="btn-filtro <?php echo ($time_filtrado == '4') ? 'active-filter' : ''; ?>">🛡️ Datekou</a>
            <a href="dashboard.php?aba=inventario&time=5" class="btn-filtro <?php echo ($time_filtrado == '5') ? 'active-filter' : ''; ?>">🦉 Fukurodani</a>
            <a href="dashboard.php?aba=inventario&time=6" class="btn-filtro <?php echo ($time_filtrado == '6') ? 'active-filter' : ''; ?>">🦅 Shiratorizawa</a>
            <a href="dashboard.php?aba=inventario&time=7" class="btn-filtro <?php echo ($time_filtrado == '7') ? 'active-filter' : ''; ?>">🥳 Johzenji</a>
            <a href="dashboard.php?aba=inventario&time=8" class="btn-filtro <?php echo ($time_filtrado == '8') ? 'active-filter' : ''; ?>">🌊 Tokonami</a>
            <a href="dashboard.php?aba=inventario&time=9" class="btn-filtro <?php echo ($time_filtrado == '9') ? 'active-filter' : ''; ?>">🏐 Wakutani Sul</a>
            <a href="dashboard.php?aba=inventario&time=10" class="btn-filtro <?php echo ($time_filtrado == '10') ? 'active-filter' : ''; ?>">🏐 Ougiminami</a>
            <a href="dashboard.php?aba=inventario&time=11" class="btn-filtro <?php echo ($time_filtrado == '11') ? 'active-filter' : ''; ?>">🗼 Kakugawa</a>
            <a href="dashboard.php?aba=inventario&time=12" class="btn-filtro <?php echo ($time_filtrado == '12') ? 'active-filter' : ''; ?>">🏐 Ubugawa</a>
            <a href="dashboard.php?aba=inventario&time=13" class="btn-filtro <?php echo ($time_filtrado == '13') ? 'active-filter' : ''; ?>">🏐 Shinzen</a>
            <a href="dashboard.php?aba=inventario&time=14" class="btn-filtro <?php echo ($time_filtrado == '14') ? 'active-filter' : ''; ?>">🐍 Nohebi</a>
            <a href="dashboard.php?aba=inventario&time=15" class="btn-filtro <?php echo ($time_filtrado == '15') ? 'active-filter' : ''; ?>">🦊 Itachiyama</a>
            <a href="dashboard.php?aba=inventario&time=16" class="btn-filtro <?php echo ($time_filtrado == '16') ? 'active-filter' : ''; ?>">🦊 Inarizaki</a>
            <a href="dashboard.php?aba=inventario&time=17" class="btn-filtro <?php echo ($time_filtrado == '17') ? 'active-filter' : ''; ?>">🏐 Tsubakihara</a>
            <a href="dashboard.php?aba=inventario&time=18" class="btn-filtro <?php echo ($time_filtrado == '18') ? 'active-filter' : ''; ?>">🏐 Niiyama</a>
            <a href="dashboard.php?aba=inventario&time=19" class="btn-filtro <?php echo ($time_filtrado == '19') ? 'active-filter' : ''; ?>">🏐 Hakusuikan</a>
            <a href="dashboard.php?aba=inventario&time=20" class="btn-filtro <?php echo ($time_filtrado == '20') ? 'active-filter' : ''; ?>">🎒 Yukigaoka</a>
            <a href="dashboard.php?aba=inventario&time=21" class="btn-filtro <?php echo ($time_filtrado == '21') ? 'active-filter' : ''; ?>">🏫 Fundamental</a>
            <a href="dashboard.php?aba=inventario&time=22" class="btn-filtro <?php echo ($time_filtrado == '22') ? 'active-filter' : ''; ?>">❓ Outros</a>
        </div>

        <p>Estes são os jogadores cadastrados no seu inventário:</p>

        <?php
        try {
            // ✨ INSTÂNCIA DA POO: Criamos o repositório utilizando o arquivo que alteramos
            $personagemRepo = new PersonagemRepository($pdo);
            
            // ✨ CHAMADA PROTETORA: Passamos o ID da sessão e o filtro de time para buscar a lista segura
            $personagens = $personagemRepo->listarTodosPorUsuario($_SESSION['id'], $time_filtrado);
            
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
                                <a href="editar-personagem.php?id=<?php echo $p['id']; ?>" style="color: #3b82f6; font-weight: bold; text-decoration: none; margin-right: 15px;">✏️ Editar</a>
                                <a href="excluir-personagem.php?id=<?php echo $p['id']; ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja apagar esse jogador?')">❌ Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>
            <p style="color: #94a3b8; font-style: italic; margin-top: 20px;">Nenhum jogador encontrado para esta categoria.</p>
        <?php 
            endif; 
        } catch (Exception $e) {
            echo "<p style='color: #ef4444;'>Erro ao carregar inventário: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>

    <div id="cadastro" class="tab-content <?php echo ($aba_atual == 'cadastro') ? 'active' : ''; ?>">
        <h2>📝 Cadastrar Novo Jogador</h2>
        <form action="salvar-personagem.php" method="POST">
            <div class="form-group">
                <label>Nome do Jogador:</label>
                <input type="text" name="nome" required placeholder="Ex: Shoyo Hinata">
            </div>
            <input type="hidden" name="idade" value="16">
            <input type="hidden" name="numero" value="10">
            <input type="hidden" name="altura" value="1.64">
            <input type="hidden" name="id_time" value="<?php echo $meu_time; ?>">
            <input type="hidden" name="id_funcao" value="1">
            
            <div class="form-group">
                <label>Posição:</label>
                <select name="id_posicao">
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

    <div id="explicacao" class="tab-content <?php echo ($aba_atual == 'explicacao') ? 'active' : ''; ?>">
        <h2>🏐 Sobre o Sistema Haikyuu!!</h2>
        <p>Este sistema é um CRUD desenvolvido para gerenciar informações de times e jogadores de Vôlei baseados no anime Haikyuu!!.</p>
        <p>Desenvolvido como projeto escolar/acadêmico para aplicar conceitos de PHP, Programação Orientada a Objetos (POO) e Bancos de Dados.</p>
    </div>

    <div id="perfil" class="tab-content <?php echo ($aba_atual == 'perfil') ? 'active' : ''; ?>">
        <h2>⚙️ Meu Perfil & Meu Personagem</h2>
        
        <div style="display: flex; gap: 30px; flex-wrap: wrap; margin-top: 20px;">
            
            <div style="flex: 1; min-width: 300px;">
                <div class="form-group">
                    <label>Seu Nome de Usuário:</label>
                    <input type="text" value="<?php echo $_SESSION['nome']; ?>" disabled style="background: #334155; color: #cbd5e1;">
                </div>
                <div class="form-group">
                    <label>Sua Descrição:</label>
                    <textarea rows="4" disabled style="background: #334155; color: #cbd5e1; resize: none;"><?php echo $_SESSION['descricao'] ?? 'Nenhuma descrição cadastrada.'; ?></textarea>
                </div>
                <p style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">* Os dados acima foram configurados na criação da sua conta.</p>
            </div>

            <div style="flex: 1; min-width: 280px; background: #0f172a; padding: 25px; border-radius: 12px; border: 2px dashed <?php echo $cor_tema; ?>; text-align: center;">
                <h3 style="margin-top: 0; color: white;">🧍 Seu Avatar do Time</h3>
                
                <div style="font-size: 5rem; line-height: 1; margin: 15px 0;">🧍‍♂️</div>
                
                <p style="margin: 8px 0; font-size: 1.1rem;">
                    Pertence ao time: <strong style="color: <?php echo $cor_tema; ?>;"><?php echo $nome_meu_time; ?></strong>
                </p>
                
                <div style="background: #1e293b; padding: 10px; border-radius: 6px; margin-top: 15px; border: 1px solid #334155;">
                    <p style="margin: 5px 0; font-size: 0.9rem; color: #94a3b8;">
                        Uniforme equipado:
                    </p>
                    <span style="display: inline-block; padding: 5px 12px; background: <?php echo $cor_tema; ?>; color: white; border-radius: 20px; font-weight: bold; font-size: 0.85rem;">
                        <?php 
                            if ($meu_time == 1) echo "Oficial Preto e Laranja";
                            if ($meu_time == 2) echo "Clássico Vermelho Escuro";
                            if ($meu_time == 3) echo "Turquesa e Branco Alvo";
                            if ($meu_time == 6) echo "Branco e Roxo Imperial";
                        ?>
                    </span>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>