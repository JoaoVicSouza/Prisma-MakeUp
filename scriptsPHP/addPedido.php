<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirecionarCheckout(string $erro): void
{
    header('Location: ../pages/compra.php?erro=' . urlencode($erro));
    exit;
}

function campoPost(string $nome): string
{
    $valor = $_POST[$nome] ?? '';
    return is_string($valor) ? trim($valor) : '';
}

function precoEmReais(string $preco): float
{
    if (preg_match('/\A(\d+)(?:[.,](\d{1,2}))?\z/', trim($preco), $partes) !== 1) {
        throw new UnexpectedValueException('Preço inválido cadastrado para um produto.');
    }

    $reais = (int) $partes[1];
    $centavos = isset($partes[2]) ? (int) str_pad($partes[2], 2, '0') : 0;

    if ($reais > intdiv(2147483647 - $centavos, 100)) {
        throw new UnexpectedValueException('Preço do produto excede o limite suportado pelo banco.');
    }

    return $reais + ($centavos / 100);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método não permitido.');
}

$usuarioId = filter_var($_SESSION['usuario_id'] ?? null, FILTER_VALIDATE_INT);
if (!$usuarioId || $usuarioId < 1) {
    header('Location: ../pages/login.php?erro=login_necessario');
    exit;
}

$nome = campoPost('fullName');
$telefone = campoPost('phone');
$cep = preg_replace('/\D/', '', campoPost('cep'));
$rua = campoPost('street');
$numero = campoPost('number');
$complemento = campoPost('complement');
$cidade = campoPost('city');
$estado = strtoupper(campoPost('state'));
$pagamento = campoPost('payment');

if (
    $nome === '' || mb_strlen($nome) > 100 ||
    $telefone === '' || mb_strlen($telefone) > 20 ||
    strlen($cep) !== 8 ||
    $rua === '' || mb_strlen($rua) > 150 ||
    $numero === '' || mb_strlen($numero) > 20 ||
    mb_strlen($complemento) > 100 ||
    $cidade === '' || mb_strlen($cidade) > 100 ||
    preg_match('/\A[A-Z]{2}\z/', $estado) !== 1 ||
    !in_array($pagamento, ['pix', 'boleto'], true)
) {
    redirecionarCheckout('dados');
}

try {
    $carrinhoEnviado = $_POST['cart'] ?? '';
    if (!is_string($carrinhoEnviado)) {
        redirecionarCheckout('carrinho');
    }
    $itensEnviados = json_decode($carrinhoEnviado, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $erro) {
    redirecionarCheckout('carrinho');
}

if (!is_array($itensEnviados) || $itensEnviados === []) {
    redirecionarCheckout('carrinho');
}

$quantidades = [];
foreach ($itensEnviados as $item) {
    if (!is_array($item)) {
        redirecionarCheckout('carrinho');
    }

    $produtoId = filter_var($item['id'] ?? null, FILTER_VALIDATE_INT);
    $quantidade = filter_var($item['quantidade'] ?? null, FILTER_VALIDATE_INT);
    if (!$produtoId || $produtoId < 1 || !$quantidade || $quantidade < 1 || $quantidade > 1000) {
        redirecionarCheckout('carrinho');
    }

    $quantidades[$produtoId] = ($quantidades[$produtoId] ?? 0) + $quantidade;
    if ($quantidades[$produtoId] > 1000) {
        redirecionarCheckout('carrinho');
    }
}

require 'conexao.php';

try {
    $pdo->beginTransaction();

    $placeholders = implode(',', array_fill(0, count($quantidades), '?'));
    $stmtProdutos = $pdo->prepare(
        "SELECT id, nome, preco, estoque FROM produtos WHERE id IN ($placeholders) ORDER BY id FOR UPDATE"
    );
    $stmtProdutos->execute(array_keys($quantidades));
    $produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

    if (count($produtos) !== count($quantidades)) {
        throw new DomainException('Um ou mais produtos do carrinho não existem mais.');
    }

    $itens = [];
    $totalCentavos = 0;
    foreach ($produtos as $produto) {
        $produtoId = (int) $produto['id'];
        $quantidade = $quantidades[$produtoId];
        if ((int) $produto['estoque'] < $quantidade) {
            throw new DomainException('Estoque insuficiente para um ou mais produtos.');
        }

        $precoUnitario = precoEmReais((string) $produto['preco']);
        $totalItem = (int) round($precoUnitario * 100) * $quantidade;
        if ($totalItem > 2147483647 - $totalCentavos) {
            throw new DomainException('O total do pedido excede o limite suportado.');
        }

        $totalCentavos += $totalItem;
        $itens[] = [
            'id' => $produtoId,
            'quantidade' => $quantidade,
            'preco' => $precoUnitario,
        ];
    }

    $endereco = json_encode([
        'cep' => $cep,
        'rua' => $rua,
        'numero' => $numero,
        'complemento' => $complemento,
        'cidade' => $cidade,
        'estado' => $estado,
        'pagamento' => $pagamento,
    ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

    $stmtPedido = $pdo->prepare(
        "INSERT INTO pedidos (dataHora, statusPag, statusPedido, telefoneDest, nomeDest, valorTotal, usuario_id, endereco)
         VALUES (NOW(), 'Pendente', 'Pendente', :telefone, :nome, :total, :usuario_id, :endereco)"
    );
    $stmtPedido->execute([
        ':telefone' => $telefone,
        ':nome' => $nome,
        ':total' => $totalCentavos,
        ':usuario_id' => $usuarioId,
        ':endereco' => $endereco,
    ]);
    $pedidoId = (int) $pdo->lastInsertId();

    $stmtItem = $pdo->prepare(
        'INSERT INTO itenspedidos (pedido_id, produto_id, quantidade, precoUnidade)
         VALUES (:pedido_id, :produto_id, :quantidade, :preco)'
    );
    $stmtEstoque = $pdo->prepare(
        'UPDATE produtos SET estoque = estoque - :quantidade WHERE id = :id AND estoque >= :quantidade'
    );

    foreach ($itens as $item) {
        $stmtItem->execute([
            ':pedido_id' => $pedidoId,
            ':produto_id' => $item['id'],
            ':quantidade' => $item['quantidade'],
            ':preco' => $item['preco'],
        ]);
        $stmtEstoque->execute([
            ':quantidade' => $item['quantidade'],
            ':id' => $item['id'],
        ]);
        if ($stmtEstoque->rowCount() !== 1) {
            throw new DomainException('O estoque de um produto foi alterado durante a compra.');
        }
    }

    $pdo->commit();
    $_SESSION['pedido_criado'] = $pedidoId;
    header('Location: ../pages/produtos.php');
    exit;
} catch (DomainException $erro) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    redirecionarCheckout('estoque');
} catch (Throwable $erro) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Erro ao registrar pedido: ' . $erro->getMessage());
    redirecionarCheckout('servidor');
}
