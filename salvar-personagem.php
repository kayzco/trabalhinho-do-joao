<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

require_once "config.php";
require_once "personagem.php";
require_once "personagemrepository.php";

use App\Entity\Personagem;
use App\Repository\PersonagemRepository;

$id_usuario = $_SESSION['id'];

// Pegando e limpando os dados do formulário
$nome       = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$idade      = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT);
$numero     = filter_input(INPUT_POST, 'numero', FILTER_VALIDATE_INT);
$altura     = filter_input(INPUT_POST, 'altura', FILTER_VALIDATE_FLOAT);
$id_time    = filter_input(INPUT_POST, 'id_time', FILTER_VALIDATE_INT);
$id_posicao = filter_input(INPUT_POST, 'id_posicao', FILTER_VALIDATE_INT);
$id_funcao  = filter_input(INPUT_POST, 'id_funcao', FILTER_VALIDATE_INT);
$descricao  = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

// Validação se os campos obrigatórios estão preenchidos
if (!$nome || !$idade || !$numero || !$altura || !$id_time || !$id_posicao || !$id_funcao) {
    header("Location: dashboard.php?aba=cadastro&erro=campos_vazios");
    exit;
}

$nomeImagem = null;

// Sistema de upload de imagem
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
    $pastaUpload = "uploads/";

    if (!is_dir($pastaUpload)) {
        mkdir($pastaUpload, 0755, true);
    }

    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extensao, $extensoesPermitidas)) {
        header("Location: dashboard.php?aba=cadastro&erro=extensao");
        exit;
    }

    if ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
        header("Location: dashboard.php?aba=cadastro&erro=tamanho");
        exit;
    }

    $nomeImagem = md5(uniqid(rand(), true)) . "." . $extensao;
    $caminhoCompleto = $pastaUpload . $nomeImagem;

    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
        header("Location: dashboard.php?aba=cadastro&erro=upload");
        exit;
    }
}

// Captura as tags selecionadas no formulário (Relacionamento N:N)
// Se nenhuma tag for marcada, inicializa como um array vazio []
$tags_selecionadas = $_POST['tags'] ?? [];

// ✨ ABRE O TRY AQUI PARA O CATCH LÁ DE BAIXO FUNCIONAR!
try {

    // Criando o objeto do Personagem usando as variáveis certas na ordem exata do construtor
    $personagem = new Personagem(
        null, 
        $nome, 
        $idade, 
        $altura, 
        $numero, 
        $descricao, 
        $id_time, 
        $id_funcao, 
        $id_posicao, 
        $nomeImagem,
        $id_usuario,        // ID do utilizador logado passado diretamente
        $tags_selecionadas  // Envia o array de IDs das tags (N:N) para a Entidade
    );

    $personagemRepo = new PersonagemRepository($pdo);

    // Salva no banco de dados (o repositório vai inserir o jogador e vincular as tags)
    if ($personagemRepo->salvar($personagem)) {
        header("Location: dashboard.php?aba=inventario&sucesso=cadastrado");
        exit;
    } else {
        header("Location: dashboard.php?aba=cadastro&erro=banco");
        exit;
    }

} // FECHA O TRY E COMEÇA O CATCH
catch (\Exception $e) {
    // Se a idade ou altura violar as regras de negócio da Entidade, captura o erro e mostra no ecrã
    die("Erro de validação na Entidade: " . $e->getMessage());
}
?>