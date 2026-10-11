<?php 
require 'conexao.php';

$categoria  = trim($_POST['category'] ?? '');
$nome       = trim($_POST['name'] ?? '');
$descricao  = $_POST['description'] ?? null;
$conteudo   = ($_POST['conteudo'] ?? '') . ' ' . ($_POST['conteudo_unidade'] ?? '');
$preco      = str_replace(',', '.', trim($_POST['price'] ?? ''));
$estoque    = $_POST['stock'] ?? 0;
$indicacao  = $_POST['indicacao'] ?? null;
$cuidados   = $_POST['cuidados'] ?? null;
$validade   = $_POST['validade'] ?? '';
$informacoes = $_POST['informacoes'] ?? null;

function redirecionarErroProduto(string $erro): void
{
    header('Location: ../pages/CRUD/cadProd.php?erro=' . urlencode($erro));
    exit;
}

if (!isset($_FILES['imagem'])) {
    redirecionarErroProduto('imagem');
}

$erroUpload = $_FILES['imagem']['error'];
if ($erroUpload !== UPLOAD_ERR_OK) {
    if ($erroUpload === UPLOAD_ERR_INI_SIZE || $erroUpload === UPLOAD_ERR_FORM_SIZE) {
        redirecionarErroProduto('imagem');
    }
    error_log('Falha ao receber imagem do produto. Código de upload: ' . $erroUpload);
    redirecionarErroProduto('servidor');
}

if ($_FILES['imagem']['size'] > 5 * 1024 * 1024) {
    redirecionarErroProduto('imagem');
}

$tiposImagem = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$tipoImagem = $finfo->file($_FILES['imagem']['tmp_name']);

if (!isset($tiposImagem[$tipoImagem])) {
    redirecionarErroProduto('imagem');
}

$check = $pdo->prepare("SELECT id FROM produtos WHERE nome = :nome");
$check->execute([':nome' => $nome]);

if ($check->fetch()) {
    redirecionarErroProduto('produto_existente');
}

$diretorioImagens = __DIR__ . '/../assets/produtos';
if (!is_dir($diretorioImagens) && !mkdir($diretorioImagens, 0755, true) && !is_dir($diretorioImagens)) {
    error_log('Não foi possível criar o diretório de imagens de produtos.');
    redirecionarErroProduto('servidor');
}

$nomeImagem = bin2hex(random_bytes(16)) . '.' . $tiposImagem[$tipoImagem];
$caminhoImagem = 'assets/produtos/' . $nomeImagem;
$arquivoImagem = $diretorioImagens . '/' . $nomeImagem;

if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $arquivoImagem)) {
    error_log('Não foi possível salvar a imagem enviada para o produto.');
    redirecionarErroProduto('servidor');
}

try{
    $sql = "INSERT INTO produtos (categoria, nome, descricao, conteudo, preco, estoque, indicacao, cuidados, validade, informacoes, imagem)
            VALUES (:categoria, :nome, :descricao, :conteudo, :preco, :estoque, :indicacao, :cuidados, :validade, :informacoes, :imagem)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':categoria' => $categoria,
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':conteudo' => $conteudo,
        ':preco' => $preco,
        ':estoque' => $estoque,
        ':indicacao' => $indicacao,
        ':cuidados' => $cuidados,
        ':validade' => $validade,
        ':informacoes' => $informacoes,
        ':imagem' => $caminhoImagem,
    ]);
    header('Location: ../pages/CRUD/cadProd.php?sucesso=cadastro');
    exit;
} catch(PDOException $erro){
    if (is_file($arquivoImagem) && !unlink($arquivoImagem)) {
        error_log('Não foi possível remover uma imagem após falha ao cadastrar o produto.');
    }
    error_log('Erro ao cadastrar produto: ' . $erro->getMessage());
    header('Location: ../pages/CRUD/cadProd.php?erro=servidor');
    exit;
}


?>