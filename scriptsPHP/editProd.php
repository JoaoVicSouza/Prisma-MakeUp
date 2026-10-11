<?php
require 'conexao.php';

$id        = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome      = trim($_POST['nome'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$preco     = trim($_POST['preco'] ?? '');
$estoque   = filter_input(INPUT_POST, 'estoque', FILTER_VALIDATE_INT);
$conteudo  = trim($_POST['conteudo'] ?? '');
$validade  = trim($_POST['validade'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$indicacao = trim($_POST['indicacao'] ?? '');
$cuidados  = trim($_POST['cuidados'] ?? '');
$informacoes = trim($_POST['informacoes'] ?? '');

if (!$id || $nome === '' || $preco === '') {
    header('Location: ../pages/CRUD/admin.php');
    exit;
}

$stmtImagemAtual = $pdo->prepare('SELECT imagem FROM produtos WHERE id = :id');
$stmtImagemAtual->execute([':id' => $id]);
$imagemAtual = $stmtImagemAtual->fetchColumn();

if ($imagemAtual === false) {
    header('Location: ../pages/CRUD/admin.php');
    exit;
}

$caminhoImagem = $imagemAtual;
$arquivoNovaImagem = null;
$upload = $_FILES['imagem'] ?? null;

if ($upload !== null && $upload['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($upload['error'] !== UPLOAD_ERR_OK) {
        if ($upload['error'] === UPLOAD_ERR_INI_SIZE || $upload['error'] === UPLOAD_ERR_FORM_SIZE) {
            header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=imagem');
        } else {
            error_log('Falha ao receber nova imagem do produto. Código de upload: ' . $upload['error']);
            header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=servidor');
        }
        exit;
    }

    $tamanhoMaximo = 5 * 1024 * 1024;
    $tiposImagem = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipoImagem = $finfo->file($upload['tmp_name']);

    if ($upload['size'] > $tamanhoMaximo || !isset($tiposImagem[$tipoImagem])) {
        header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=imagem');
        exit;
    }

    $diretorioImagens = __DIR__ . '/../assets/produtos';
    if (!is_dir($diretorioImagens) && !mkdir($diretorioImagens, 0755, true) && !is_dir($diretorioImagens)) {
        error_log('Não foi possível criar o diretório de imagens de produtos.');
        header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=servidor');
        exit;
    }

    $nomeImagem = bin2hex(random_bytes(16)) . '.' . $tiposImagem[$tipoImagem];
    $caminhoImagem = 'assets/produtos/' . $nomeImagem;
    $arquivoNovaImagem = $diretorioImagens . '/' . $nomeImagem;

    if (!move_uploaded_file($upload['tmp_name'], $arquivoNovaImagem)) {
        error_log('Não foi possível salvar a nova imagem do produto.');
        header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=servidor');
        exit;
    }
}

try {
    $stmt = $pdo->prepare("UPDATE produtos SET nome=:nome, categoria=:categoria, preco=:preco, estoque=:estoque,
        conteudo=:conteudo, validade=:validade, descricao=:descricao, imagem=:imagem, indicacao=:indicacao,
        cuidados=:cuidados, informacoes=:informacoes WHERE id=:id");
    $stmt->execute([
        ':id' => $id, ':nome' => $nome, ':categoria' => $categoria,
        ':preco' => $preco, ':estoque' => $estoque, ':conteudo' => $conteudo,
        ':validade' => $validade, ':descricao' => $descricao, ':imagem' => $caminhoImagem, ':indicacao' => $indicacao,
        ':cuidados' => $cuidados, ':informacoes' => $informacoes,
    ]);
    if (
        $arquivoNovaImagem !== null &&
        is_string($imagemAtual) &&
        preg_match('~\Aassets/produtos/[a-f0-9]{32}\.(?:jpg|png|webp)\z~', $imagemAtual) === 1
    ) {
        $arquivoImagemAnterior = __DIR__ . '/../' . $imagemAtual;
        if (is_file($arquivoImagemAnterior) && !unlink($arquivoImagemAnterior)) {
            error_log('Não foi possível remover a imagem anterior do produto.');
        }
    }
    header('Location: ../pages/CRUD/admin.php');
} catch (PDOException $e) {
    if ($arquivoNovaImagem !== null && is_file($arquivoNovaImagem) && !unlink($arquivoNovaImagem)) {
        error_log('Não foi possível remover a nova imagem após falha na edição do produto.');
    }
    error_log('Erro ao editar produto: ' . $e->getMessage());
    header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=servidor');
}
exit;
