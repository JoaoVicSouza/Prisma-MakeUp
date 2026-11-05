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

$stmtVariacoesAtuais = $pdo->prepare(
    'SELECT id, rotulo, imagem FROM produto_variacoes WHERE produto_id = :produto_id'
);
$stmtVariacoesAtuais->execute([':produto_id' => $id]);
$variacoesAtuais = $stmtVariacoesAtuais->fetchAll(PDO::FETCH_ASSOC);
$variacoesPorId = [];
foreach ($variacoesAtuais as $variacaoAtual) {
    $variacoesPorId[(int) $variacaoAtual['id']] = $variacaoAtual;
}

$variacoesPostadas = $_POST['variacoes'] ?? [];
if (!is_array($variacoesPostadas) || count($variacoesPostadas) > 4) {
    header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=variacoes');
    exit;
}

$variacoesValidadas = [];
$idsVariacoesMantidas = [];
$tiposImagem = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
];
$finfo = new finfo(FILEINFO_MIME_TYPE);
foreach ($variacoesPostadas as $indice => $variacaoPostada) {
    if (!is_array($variacaoPostada) || !is_string($variacaoPostada['rotulo'] ?? null)) {
        header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=variacoes');
        exit;
    }

    $rotulo = trim($variacaoPostada['rotulo']);
    $idVariacao = null;
    if (($variacaoPostada['id'] ?? '') !== '') {
        $idVariacao = filter_var($variacaoPostada['id'], FILTER_VALIDATE_INT);
        if (!$idVariacao || !isset($variacoesPorId[$idVariacao]) || isset($idsVariacoesMantidas[$idVariacao])) {
            header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=variacoes');
            exit;
        }
        $idsVariacoesMantidas[$idVariacao] = true;
    }

    $erroUploadVariacao = $_FILES['variacoes']['error'][$indice]['imagem'] ?? UPLOAD_ERR_NO_FILE;
    $temporarioVariacao = $_FILES['variacoes']['tmp_name'][$indice]['imagem'] ?? '';
    $tamanhoVariacao = $_FILES['variacoes']['size'][$indice]['imagem'] ?? 0;
    if ($rotulo === '' || mb_strlen($rotulo) > 80) {
        header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=variacoes');
        exit;
    }

    $imagemVariacao = $idVariacao !== null ? $variacoesPorId[$idVariacao]['imagem'] : null;
    if ($erroUploadVariacao !== UPLOAD_ERR_NO_FILE) {
        if (
            $erroUploadVariacao !== UPLOAD_ERR_OK ||
            !is_string($temporarioVariacao) ||
            !is_uploaded_file($temporarioVariacao) ||
            $tamanhoVariacao > 5 * 1024 * 1024
        ) {
            header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=variacoes');
            exit;
        }

        $tipoVariacao = $finfo->file($temporarioVariacao);
        if (!isset($tiposImagem[$tipoVariacao])) {
            header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=variacoes');
            exit;
        }
    } elseif ($idVariacao === null) {
        header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=variacoes');
        exit;
    }

    $variacoesValidadas[] = [
        'id' => $idVariacao,
        'rotulo' => $rotulo,
        'imagem' => $imagemVariacao,
        'temporario' => $erroUploadVariacao === UPLOAD_ERR_NO_FILE ? null : $temporarioVariacao,
        'extensao' => $erroUploadVariacao === UPLOAD_ERR_NO_FILE ? null : $tiposImagem[$tipoVariacao],
    ];
}

$caminhoImagem = $imagemAtual;
$arquivosNovos = [];
$imagensAntigasRemovidas = [];
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
    $arquivosNovos[] = $arquivoNovaImagem;
}

foreach ($variacoesValidadas as $indice => $variacao) {
    if ($variacao['temporario'] === null) {
        continue;
    }

    $nomeImagemVariacao = bin2hex(random_bytes(16)) . '.' . $variacao['extensao'];
    $arquivoImagemVariacao = __DIR__ . '/../assets/produtos/' . $nomeImagemVariacao;
    if (!move_uploaded_file($variacao['temporario'], $arquivoImagemVariacao)) {
        foreach ($arquivosNovos as $arquivoNovo) {
            if (is_file($arquivoNovo) && !unlink($arquivoNovo)) {
                error_log('Não foi possível remover uma imagem após falha ao atualizar opções do produto.');
            }
        }
        header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=servidor');
        exit;
    }
    $arquivosNovos[] = $arquivoImagemVariacao;
    $variacoesValidadas[$indice]['imagem'] = 'assets/produtos/' . $nomeImagemVariacao;
    if ($variacao['id'] !== null) {
        $imagensAntigasRemovidas[] = $variacoesPorId[$variacao['id']]['imagem'];
    }
}

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("UPDATE produtos SET nome=:nome, categoria=:categoria, preco=:preco, estoque=:estoque,
        conteudo=:conteudo, validade=:validade, descricao=:descricao, imagem=:imagem, indicacao=:indicacao,
        cuidados=:cuidados, informacoes=:informacoes WHERE id=:id");
    $stmt->execute([
        ':id' => $id, ':nome' => $nome, ':categoria' => $categoria,
        ':preco' => $preco, ':estoque' => $estoque, ':conteudo' => $conteudo,
        ':validade' => $validade, ':descricao' => $descricao, ':imagem' => $caminhoImagem, ':indicacao' => $indicacao,
        ':cuidados' => $cuidados, ':informacoes' => $informacoes,
    ]);

    $idsMantidos = array_keys($idsVariacoesMantidas);
    $idsRemovidos = array_diff(array_keys($variacoesPorId), $idsMantidos);
    if ($idsRemovidos !== []) {
        $placeholders = implode(',', array_fill(0, count($idsRemovidos), '?'));
        $stmtExcluir = $pdo->prepare(
            "DELETE FROM produto_variacoes WHERE produto_id = ? AND id IN ($placeholders)"
        );
        $stmtExcluir->execute(array_merge([$id], $idsRemovidos));
        foreach ($idsRemovidos as $idRemovido) {
            $imagensAntigasRemovidas[] = $variacoesPorId[$idRemovido]['imagem'];
        }
    }

    $stmtAtualizarVariacao = $pdo->prepare(
        'UPDATE produto_variacoes SET rotulo = :rotulo, imagem = :imagem, ordem = :ordem
         WHERE id = :id AND produto_id = :produto_id'
    );
    $stmtInserirVariacao = $pdo->prepare(
        'INSERT INTO produto_variacoes (produto_id, rotulo, imagem, ordem)
         VALUES (:produto_id, :rotulo, :imagem, :ordem)'
    );
    foreach ($variacoesValidadas as $indice => $variacao) {
        $parametros = [
            ':produto_id' => $id,
            ':rotulo' => $variacao['rotulo'],
            ':imagem' => $variacao['imagem'],
            ':ordem' => $indice + 1,
        ];
        if ($variacao['id'] !== null) {
            $stmtAtualizarVariacao->execute($parametros + [':id' => $variacao['id']]);
        } else {
            $stmtInserirVariacao->execute($parametros);
        }
    }
    $pdo->commit();

    if ($caminhoImagem !== $imagemAtual) {
        $imagensAntigasRemovidas[] = $imagemAtual;
    }
    foreach ($imagensAntigasRemovidas as $imagemAntiga) {
        if (
            is_string($imagemAntiga) &&
            preg_match('~\Aassets/produtos/[a-f0-9]{32}\.(?:jpg|png|webp)\z~', $imagemAntiga) === 1
        ) {
            $arquivoImagemAnterior = __DIR__ . '/../' . $imagemAntiga;
            if (is_file($arquivoImagemAnterior) && !unlink($arquivoImagemAnterior)) {
                error_log('Não foi possível remover uma imagem anterior do produto.');
            }
        }
    }
    header('Location: ../pages/CRUD/admin.php');
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    foreach ($arquivosNovos as $arquivoNovo) {
        if (is_file($arquivoNovo) && !unlink($arquivoNovo)) {
            error_log('Não foi possível remover uma imagem nova após falha na edição do produto.');
        }
    }
    error_log('Erro ao editar produto: ' . $e->getMessage());
    header('Location: ../pages/CRUD/mod.php?tipo=produto&id=' . $id . '&erro=servidor');
}
exit;
