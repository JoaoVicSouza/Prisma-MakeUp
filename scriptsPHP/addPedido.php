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
    header('Location: ../pages/login.php?erro=login_necessario&origem=checkout');
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

$nomeValido = preg_match('/\\A[\\p{L}\\p{M}]+(?:[ \'-][\\p{L}\\p{M}]+)*\\z/u', $nome) === 1
    && preg_match('/\\s/u', $nome) === 1;
$telefoneDigitos = preg_replace('/\\D/', '', $telefone);
if (in_array(strlen($telefoneDigitos), [12, 13], true) && str_starts_with($telefoneDigitos, '55')) {
    $telefoneDigitos = substr($telefoneDigitos, 2);
}
$dddsValidos = [
    '11','12','13','14','15','16','17','18','19',
    '21','22','24','27','28',
    '31','32','33','34','35','37','38',
    '41','42','43','44','45','46','47','48','49',
    '51','53','54','55',
    '61','62','63','64','65','66','67','68','69',
    '71','73','74','75','77','79',
    '81','82','83','84','85','86','87','88','89',
    '91','92','93','94','95','96','97','98','99'
];
$ddd = substr($telefoneDigitos, 0, 2);
$numeroLocal = substr($telefoneDigitos, 2);
$telefoneValido = in_array(strlen($telefoneDigitos), [10, 11], true)
    && in_array($ddd, $dddsValidos, true)
    && (
        (strlen($numeroLocal) === 8 && preg_match('/\\A[2-5][0-9]{7}\\z/', $numeroLocal) === 1)
        || (strlen($numeroLocal) === 9 && preg_match('/\\A9[0-9]{8}\\z/', $numeroLocal) === 1)
    );

$erroDados = null;
if (!$nomeValido || mb_strlen($nome) > 100) {
    $erroDados = 'nome';
} elseif (!$telefoneValido || mb_strlen($telefone) > 20) {
    $erroDados = 'telefone';
} elseif (strlen($cep) !== 8) {
    $erroDados = 'cep';
} elseif ($rua === '' || mb_strlen($rua) > 150) {
    $erroDados = 'rua';
} elseif ($numero === '' || mb_strlen($numero) > 20) {
    $erroDados = 'numero';
} elseif (mb_strlen($complemento) > 100) {
    $erroDados = 'complemento';
} elseif ($cidade === '' || mb_strlen($cidade) > 100) {
    $erroDados = 'cidade';
} elseif (preg_match('/\A[A-Z]{2}\z/', $estado) !== 1) {
    $erroDados = 'estado';
} elseif (!in_array($pagamento, ['pix', 'boleto'], true)) {
    $erroDados = 'pagamento';
}

if ($erroDados !== null) {
    redirecionarCheckout('dados_' . $erroDados);
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
$itensPorVariacao = [];
foreach ($itensEnviados as $item) {
    if (!is_array($item)) {
        redirecionarCheckout('carrinho');
    }

    $produtoId = filter_var($item['id'] ?? null, FILTER_VALIDATE_INT);
    $quantidade = filter_var($item['quantidade'] ?? null, FILTER_VALIDATE_INT);
    if (!$produtoId || $produtoId < 1 || !$quantidade || $quantidade < 1 || $quantidade > 1000) {
        redirecionarCheckout('carrinho');
    }

    $variacaoId = null;
    if (array_key_exists('variacaoId', $item) && $item['variacaoId'] !== null && $item['variacaoId'] !== '') {
        $variacaoId = filter_var($item['variacaoId'], FILTER_VALIDATE_INT);
        if (!$variacaoId || $variacaoId < 1) {
            redirecionarCheckout('carrinho');
        }
    }

    $quantidades[$produtoId] = ($quantidades[$produtoId] ?? 0) + $quantidade;
    if ($quantidades[$produtoId] > 1000) {
        redirecionarCheckout('carrinho');
    }
    $chaveVariacao = $variacaoId ?? 0;
    $itensPorVariacao[$produtoId][$chaveVariacao] =
        ($itensPorVariacao[$produtoId][$chaveVariacao] ?? 0) + $quantidade;
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

    $idsVariacoes = [];
    foreach ($itensPorVariacao as $itensProduto) {
        foreach (array_keys($itensProduto) as $variacaoId) {
            if ($variacaoId !== 0) {
                $idsVariacoes[] = (int) $variacaoId;
            }
        }
    }

    $variacoesPorId = [];
    if ($idsVariacoes !== []) {
        $placeholdersVariacoes = implode(',', array_fill(0, count($idsVariacoes), '?'));
        $stmtVariacoes = $pdo->prepare(
            "SELECT id, produto_id, rotulo
             FROM produto_variacoes
             WHERE id IN ($placeholdersVariacoes)
             FOR UPDATE"
        );
        $stmtVariacoes->execute($idsVariacoes);
        foreach ($stmtVariacoes->fetchAll(PDO::FETCH_ASSOC) as $variacao) {
            $variacoesPorId[(int) $variacao['id']] = $variacao;
        }
        if (count($variacoesPorId) !== count(array_unique($idsVariacoes))) {
            throw new DomainException('Uma ou mais opções do carrinho não existem mais.');
        }
    }

    $itens = [];
    $totalCentavos = 0;
    foreach ($produtos as $produto) {
        $produtoId = (int) $produto['id'];
        $quantidade = $quantidades[$produtoId];
        if ((int) $produto['estoque'] < $quantidade) {
            throw new DomainException('Estoque insuficiente para um ou mais produtos.');
        }

        foreach ($itensPorVariacao[$produtoId] as $chaveVariacao => $quantidadeVariacao) {
            $variacaoNome = null;
            if ((int) $chaveVariacao > 0) {
                $variacao = $variacoesPorId[(int) $chaveVariacao] ?? null;
                if (!$variacao || (int) $variacao['produto_id'] !== $produtoId) {
                    throw new DomainException('Uma opção selecionada não pertence ao produto.');
                }
                $variacaoNome = $variacao['rotulo'];
            }

            $precoUnitario = precoEmReais((string) $produto['preco']);
            $totalItem = (int) round($precoUnitario * 100) * $quantidadeVariacao;
            if ($totalItem > 2147483647 - $totalCentavos) {
                throw new DomainException('O total do pedido excede o limite suportado.');
            }

            $totalCentavos += $totalItem;
            $itens[] = [
                'id' => $produtoId,
                'variacao_nome' => $variacaoNome,
                'quantidade' => $quantidadeVariacao,
                'preco' => $precoUnitario,
            ];
        }
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
        'INSERT INTO itenspedidos (pedido_id, produto_id, variacao_nome, quantidade, precoUnidade)
         VALUES (:pedido_id, :produto_id, :variacao_nome, :quantidade, :preco)'
    );
    $stmtEstoque = $pdo->prepare(
        'UPDATE produtos SET estoque = estoque - :quantidade WHERE id = :id AND estoque >= :quantidade'
    );

    foreach ($itens as $item) {
        $stmtItem->execute([
            ':pedido_id' => $pedidoId,
            ':produto_id' => $item['id'],
            ':variacao_nome' => $item['variacao_nome'],
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
    header('Location: ../pages/compra.php');
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
