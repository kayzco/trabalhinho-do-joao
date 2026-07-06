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

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: dashboard.php");
    exit;
}

$repo = new PersonagemRepository($pdo);
$personagem = $repo->buscarPorId($id, $_SESSION['id']);

if (!$personagem) {
    echo "Jogador não encontrado ou não pertence a você.";
    exit;
}

try {
    $times = $pdo->query("SELECT id, nome FROM time ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
    $posicoes = $pdo->query("SELECT id, nome FROM posicao ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
    $funcoes = $pdo->query("SELECT id, nome FROM funcao ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
    $tags = $pdo->query("SELECT id, nome FROM tag ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

    $stmtJogadorTags = $pdo->prepare("SELECT id_tag FROM personagem_tag WHERE id_personagem = ?");
    $stmtJogadorTags->execute([$id]);
    $tagsDoJogador = $stmtJogadorTags->fetchAll(PDO::FETCH_COLUMN); // Cria um array simples só com os IDs das tags dele
} catch (PDOException $e) {
    die("Erro ao carregar dados auxiliares do banco: " . $e->getMessage());
}

$erro_atual = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>✏️ Editar Jogador - Haikyuu!!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #f8fafc;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .box {
            background: #1e293b;
            padding: 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        }

        h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 25px;
            color: #f97316; /* Laranja Haikyuu */
            font-size: 1.8rem;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #cbd5e1;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            box-sizing: border-box;
            border: 1px solid #475569;
            border-radius: 6px;
            background: #334155;
            color: white;
            font-size: 15px;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #f97316;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        input[type="checkbox"] {
            margin-top: 0;
        }

        input[type="file"] {
            background: transparent;
            border: none;
            padding: 5px 0;
        }

        .row {
            display: flex;
            gap: 15px;
        }

        .col {
            flex: 1;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
        }

        .btn-submit {
            background: #f97316;
            color: white;
        }

        .btn-submit:hover {
            background: #ea580c;
        }

        .btn-cancel {
            background: #475569;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: #334155;
            color: white;
        }

        .error-msg {
            background: #ef4444;
            color: white;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 25px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>✏️ Editar Jogador</h2>

    <?php if (!empty($erro_atual)): ?>
        <div class="error-msg">
            <?php 
                if ($erro_atual == 'campos_vazios') echo "Preencha todos os campos obrigatórios!";
                elseif ($erro_atual == 'upload') echo "Erro ao salvar a foto no servidor!";
                elseif ($erro_atual == 'extensao') echo "Formato inválido! Use apenas JPG, JPEG, PNG ou WEBP.";
                elseif ($erro_atual == 'tamanho') echo "A imagem deve ter no máximo 2MB.";
                else echo "Erro ao atualizar os dados.";
            ?>
        </div>
    <?php endif; ?>

    <form action="atualizar-personagem.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $personagem['id']; ?>">

        <label for="nome">Nome do Jogador</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($personagem['nome']); ?>" required>

        <div class="row">
            <div class="col">
                <label for="idade">Idade</label>
                <input type="number" id="idade" name="idade" value="<?php echo htmlspecialchars($personagem['idade']); ?>" required>
            </div>
            <div class="col">
                <label for="numero">Número da Camisa</label>
                <input type="number" id="numero" name="numero" value="<?php echo htmlspecialchars($personagem['numero']); ?>" required>
            </div>
        </div>

        <label for="altura">Altura (em metros)</label>
        <input type="number" id="altura" name="altura" step="0.01" value="<?php echo htmlspecialchars($personagem['altura']); ?>" required>

        <label for="id_time">Time</label>
        <select id="id_time" name="id_time" required>
            <option value="">-- Selecione o Time --</option>
            <?php foreach ($times as $t): ?>
                <option value="<?php echo $t['id']; ?>" <?php echo ($personagem['id_time'] == $t['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($t['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="row">
            <div class="col">
                <label for="id_posicao">Posição</label>
                <select id="id_posicao" name="id_posicao" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach ($posicoes as $p): ?>
                        <option value="<?php echo $p['id']; ?>" <?php echo ($personagem['id_posicao'] == $p['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($p['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label for="id_funcao">Função</label>
                <select id="id_funcao" name="id_funcao" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach ($funcoes as $f): ?>
                        <option value="<?php echo $f['id']; ?>" <?php echo ($personagem['id_funcao'] == $f['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($f['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <label for="descricao">Descrição / Detalhes</label>
        <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($personagem['descricao']); ?></textarea>

        <label>Tags do Jogador</label>
        <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:10px;">
            <?php foreach ($tags as $tag): ?>
                <label style="display:flex; align-items:center; gap:6px; background:#334155; padding:8px 12px; border-radius:8px; margin-top:0; cursor:pointer;">
                    <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>" style="width:auto; margin-top:0;" <?php echo in_array($tag['id'], $tagsDoJogador) ? 'checked' : ''; ?>>
                    <?php echo htmlspecialchars($tag['nome']); ?>
                </label>
            <?php endforeach; ?>
        </div> 

        <label for="imagem">Alterar Foto (Deixe em branco para manter a atual)</label>
        <input type="file" id="imagem" name="imagem" accept="image/*">

        <div class="actions">
            <a href="dashboard.php" class="btn btn-cancel">Cancelar</a>
            <button type="submit" class="btn btn-submit">Salvar Alterações</button>
        </div>
    </form>
</div>

</body>
</html>