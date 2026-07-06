<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
require_once "config.php";
require_once "personagem.php";
require_once "personagemrepository.php";
use App\Repository\PersonagemRepository;

$aba_atual = $_GET['aba'] ?? 'inventario';
$time_filtrado = $_GET['time'] ?? 'todos';

$stmtUser = $pdo->prepare("SELECT id_time, descricao FROM usuario WHERE id = ?");
$stmtUser->execute([$_SESSION['id']]);
$dadosUsuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

$meu_time = $dadosUsuario['id_time'] ?? 1;
$_SESSION['descricao'] = $dadosUsuario['descricao'] ?? 'Nenhuma descrição cadastrada.';

$cor_tema = "#f97316"; 
$nome_meu_time = "Karasuno";
$emoji_time = "🦅";

if ($meu_time == 2) {
    $cor_tema = "#ef4444"; 
    $nome_meu_time = "Nekoma";
    $emoji_time = "🐈";
} elseif ($meu_time == 3) {
    $cor_tema = "#0d9488"; 
    $nome_meu_time = "Aoba Johsai";
    $emoji_time = "🌱";
} elseif ($meu_time == 6) {
    $cor_tema = "#86198f"; 
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
            transition: 0.25s;
        }

        .user-info a:hover {
            color: #ffffff;
        }

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
            vertical-align: middle;
        }
        
        .tabela-inventario th {
            background-color: #334155;
            color: <?php echo $cor_tema; ?>; /* Cor dinâmica */
        }
        
        .tabela-inventario tr {
            transition: background-color 0.25s ease;
        }

        .tabela-inventario tr:hover { 
            background-color: #475569;
            transform: scale(1.01);
        }
        
        .btn-excluir {
            color: #ef4444;
            text-decoration: none;
            font-weight: bold;
        }
        
        .btn-excluir:hover { 
            text-decoration: underline; 
        }

        .btn-filtro {
            background: #334155;
            color: #f8fafc;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: bold;
            transition: 0.2s ease;
        }
        .btn-filtro:hover {
    background: #475569;
    transform: translateY(-2px);
}

        .btn-filtro.active-filter {
            background: <?php echo $cor_tema; ?>; 
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
            background: <?php echo $cor_tema; ?>; 
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
            $personagemRepo = new PersonagemRepository($pdo);
            
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
        <p>Use o formulário completo para cadastrar jogador com imagem, descrição, time, posição e função.</p>
        <a href="form-personagem.php" class="btn-submit" style="display:inline-block; text-decoration:none;">
            Ir para o formulário de cadastro
        </a>
    </div>

<div id="explicacao" class="tab-content <?php echo ($aba_atual == 'explicacao') ? 'active' : ''; ?>">
        <h2> Sobre o trabalho! </h2>

        <p>Bem-vindo ao nosso centro de comando técnico! Se você chegou até aqui, parabéns, você acaba de entrar nos bastidores do sistema que me custou algumas noites de sono e muitos neurônios, mas tá funcionando (amém!). Este site é um <strong>CRUD</strong> completinho feito em PHP puro para gerenciar os jogadores e times do universo de <em>Haikyuu!!</em>. </p>

        <h3>🛠️ Como funciona os Bastidores?</h3>
        <ul>
            <li><strong>📝 O Cadastro:</strong> Sabe quando o Ukai tá analisando as fichas dos novos membros da Karasuno? É tipo isso! Na nossa tela de cadastro, você joga os dados reais do atleta (Nome, Número da Camisa, Idade, Altura, Posição, Time, Função e as Tags dele). O sistema limpa tudo pra não quebrar e joga direto no banco de dados MySQL</li>
            <li><strong>🎒 O Inventário:</strong> É uma tabela que só vai te mostrar os personagens que <em>você</em> cadastrou (nada de um usuário bagunçar a quadra do outro!). Dá pra filtrar tudo por time rapidinho, e se você fez alguma burrada ou o jogador mudou de status, os botões de <strong>Editar</strong> e <strong>Excluir</strong> estão ali pra te salvar. </li>
            <li><strong>🎨 O Universo de Haikyuu na Interface:</strong> Assim que você faz o login, a interface muda de cor sozinha! Fica Laranja se você for da <em>Karasuno</em>, Vermelho se for da <em>Nekoma</em>, Turquesa da <em>Aoba Johsai</em> e Roxo da <em>Shiratorizawa</em>. O painel veste a sua camisa!</li>
            <li><strong>👤 O Painel do Técnico (Meu Perfil):</strong> Aqui é onde você gerencia seu crachá oficial de comando! Além de poder mudar sua biografia e notas táticas, criamos uma integração total com o banco de dados que faz um <code>COUNT</code> em tempo real de quantos atletas estão sob seu comando. E tem mais: o sistema calcula automaticamente a sua porcentagem de "Alinhamento com o Clube", medindo quantos dos seus jogadores cadastrados jogam no mesmo time que você torce. É o seu relatório de olheiro profissional atualizado em um clique!</li>
        </ul>

        <h3> O Anime Aplicado na Programação (POO)</h3>
        <ul>
            <li>A classe <code>Personagem</code> (Entidade) funciona como a ficha técnica e médica real do atleta. Ela cuida das regras de negócio e protege o próprio estado. Se a idade ou altura forem inválidas pro vôlei escolar, ela joga uma Exception na hora!</li>
            <li>A classe <code>PersonagemRepository</code> funciona como o próprio olheiro ou empresário do time. Ela é a única que tem permissão de pisar na quadra (o banco de dados) para buscar, salvar, atualizar ou mandar um jogador pro banco (excluir) usando PDO.</li>
        </ul>

        <hr style="border-color: #334155; margin: 25px 0;">

        <h3>✍️ Quem Fez Isso Acontecer? (Créditos)</h3>
        <p>🚀 <strong>Desenvolvido por:</strong> Kaylane e Anna !!!</p>
        <p>🤝 <strong>Apoio Moral e Técnico:</strong> O computador mesmo, que ficou assistindo eu com serissímas dificuldades com o github. Ah, e também um colega meu de Realengo que é programador e vai pra Tokyo  </p>
        <blockquote style="background: #0f172a; padding: 15px; border-left: 4px solid <?php echo $cor_tema ?? '#f97316'; ?>; margin-top: 20px; font-style: italic; border-radius: 4px;">
            <strong>⚠️ NOTA DA DESENVOLVEDORA:</strong> Eu me senti no dever de honrar a minha tradição de todo ano fazer um trabalho sobre Haikyuu, é o meu anime conforto. RECOMENDO! <strong>Fly! 🦅</strong>
        </blockquote>

        <div style="margin-top: 30px; text-align: center; font-family: 'Poppins', sans-serif;">
            <p style="font-size: 1.2rem; font-weight: 600; color: #f97316; letter-spacing: 1px; text-transform: uppercase; margin: 0;">
                "A vida é um tédio se você não se desafiar a si mesmo".
            </p>
            <p style="font-size: 0.9rem; color: #94a3b8; font-style: italic; margin-top: 5px;">— Yuu Nishinoya (Haikyuu!!)</p>
        </div>
    </div>

    <div id="perfil" class="tab-content <?php echo ($aba_atual == 'perfil') ? 'active' : ''; ?>">
        <h2>👤 Painel do Técnico</h2>
        <p style="color: #94a3b8; margin-bottom: 25px;">Gerencia as tuas credenciais de comando e acompanha o rendimento da tua equipa técnica.</p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;">
            
            <div style="background: #1e293b; padding: 25px; border-radius: 12px; border: 1px solid #334155;">
                <h3 style="margin-bottom: 20px; color: #fff; display: flex; align-items: center; gap: 10px;">
                    📝 Atualizar Cadastro
                </h3>
                
                <form action="atualizar-perfil.php" method="POST" enctype="multipart/form-data">
                    
                    <label style="color: #94a3b8; font-size: 0.9rem; display: block; margin-bottom: 8px;">Foto de Perfil (Avatar)</label>
                    <input type="file" name="foto_perfil" accept="image/*" 
                           style="width: 100%; padding: 8px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; margin-bottom: 15px; font-size: 0.85rem;">

                    <label style="color: #94a3b8; font-size: 0.9rem; display: block; margin-bottom: 8px;">Nome do Técnico</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($_SESSION['nome']); ?>" required 
                           style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; margin-bottom: 15px;">

                    <label style="color: #94a3b8; font-size: 0.9rem; display: block; margin-bottom: 8px;">Clube de Coração (Muda a cor do site!)</label>
                    <select name="id_time" required style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; margin-bottom: 15px;">
                        <option value="1" <?php echo ($_SESSION['id_time'] == 1) ? 'selected' : ''; ?>>Karasuno (Laranja)</option>
                        <option value="2" <?php echo ($_SESSION['id_time'] == 2) ? 'selected' : ''; ?>>Nekoma (Vermelho)</option>
                        <option value="3" <?php echo ($_SESSION['id_time'] == 3) ? 'selected' : ''; ?>>Aoba Johsai (Turquesa)</option>
                        <option value="4" <?php echo ($_SESSION['id_time'] == 4) ? 'selected' : ''; ?>>Shiratorizawa (Roxo)</option>
                    </select>

                    <label style="color: #94a3b8; font-size: 0.9rem; display: block; margin-bottom: 8px;">Biografia / Notas de Estratégia</label>
                    <textarea name="descricao" rows="4" style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; margin-bottom: 20px; resize: none;"><?php echo htmlspecialchars($_SESSION['descricao'] ?? ''); ?></textarea>

                    <button type="submit" style="background: <?php echo $cor_tema ?? '#f97316'; ?>; color: #fff; border: none; padding: 12px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; width: 100%; transition: opacity 0.2s;">
                        Salvar Alterações
                    </button>
                </form>
            </div>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                
                <div style="background: #1e293b; padding: 25px; border-radius: 12px; border: 1px solid #334155; position: relative; overflow: hidden;">
                    <div style="position: absolute; right: -15px; bottom: -15px; font-size: 6rem; opacity: 0.05; color: #fff; pointer-events: none;">🏐</div>
                    
                    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 15px;">
                        <?php 
                            $foto = !empty($_SESSION['foto_perfil']) ? 'uploads/' . $_SESSION['foto_perfil'] : 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($_SESSION['nome']); 
                        ?>
                        <img src="<?php echo $foto; ?>" alt="Foto do Técnico" 
                             style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 3px solid <?php echo $cor_tema ?? '#f97316'; ?>;">
                        
                        <div>
                            <h3 style="color: #fff; margin: 0;"><?php echo htmlspecialchars($_SESSION['nome']); ?></h3>
                            <p style="color: <?php echo $cor_tema ?? '#f97316'; ?>; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin: 2px 0 0 0;">
                                <?php 
                                    if ($_SESSION['id_time'] == 1) echo "VOE (Karasuno)";
                                    elseif ($_SESSION['id_time'] == 2) echo "CONECTAR (Nekoma)";
                                    elseif ($_SESSION['id_time'] == 3) echo "DOMINE A QUADRA (Aoba Johsai)";
                                    elseif ($_SESSION['id_time'] == 4) echo "FORÇA IRRESISTÍVEL (Shiratorizawa)";
                                    else echo "Olheiro Independente";
                                ?>
                            </p>
                        </div>
                    </div>
                    
                    <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.5; font-style: italic;">
                        "<?php echo !empty($_SESSION['descricao']) ? htmlspecialchars($_SESSION['descricao']) : 'Nenhuma nota de estratégia anotada ainda...'; ?>"
                    </p>
                </div>

                <div style="background: #1e293b; padding: 25px; border-radius: 12px; border: 1px solid #334155;">
                    <h4 style="color: #fff; margin-bottom: 20px;">Relatório de Olheiro</h4>
                    
                    <?php
                        $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM personagem WHERE id_usuario = ?");
                        $stmtCount->execute([$_SESSION['id']]);
                        $totalJogadores = $stmtCount->fetchColumn();

                        $stmtTimeCount = $pdo->prepare("SELECT COUNT(*) FROM personagem WHERE id_usuario = ? AND id_time = ?");
                        $stmtTimeCount->execute([$_SESSION['id'], $_SESSION['id_time']]);
                        $jogadoresMesmoTime = $stmtTimeCount->fetchColumn();

                        // Calcula aq uma barra de progresso baseada nos registos
                        $progresso = ($totalJogadores > 0) ? min(($jogadoresMesmoTime / $totalJogadores) * 100, 100) : 0;
                    ?>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; background: #0f172a; padding: 12px; border-radius: 8px;">
                        <span style="color: #94a3b8; font-size: 0.9rem;">Jogadores Registados:</span>
                        <span style="color: #fff; font-weight: bold; font-size: 1.1rem;"><?php echo $totalJogadores; ?></span>
                    </div>

                    <div style="margin-top: 15px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #94a3b8; margin-bottom: 5px;">
                            <span>Alinhamento com o Clube:</span>
                            <span style="color: <?php echo $cor_tema ?? '#f97316'; ?>; font-weight: 600;"><?php echo round($progresso); ?>%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #0f172a; border-radius: 4px; overflow: hidden;">
                            <div style="width: <?php echo $progresso; ?>%; height: 100%; background: <?php echo $cor_tema ?? '#f97316'; ?>; border-radius: 4px; transition: width 0.5s;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

</body>
</html>