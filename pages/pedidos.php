<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioId = filter_var($_SESSION['usuario_id'] ?? null, FILTER_VALIDATE_INT);
if (!$usuarioId || $usuarioId < 1) {
    header('Location: login.php?erro=login_necessario');
    exit;
}

if (!isset($_SESSION['csrf_cancelar_pedido'])) {
    $_SESSION['csrf_cancelar_pedido'] = bin2hex(random_bytes(32));
}

require '../scriptsPHP/conexao.php';

$pedidos = [];
$itensPorPedido = [];
$erroConsulta = false;
$mensagensCancelamento = [
    'sucesso' => ['success', 'Pedido cancelado com sucesso. Os produtos foram devolvidos ao estoque.'],
    'nao_pendente' => ['warning', 'Somente pedidos pendentes podem ser cancelados.'],
    'indisponivel' => ['warning', 'Pedido não encontrado. Atualize a página e tente novamente.'],
    'servidor' => ['danger', 'Não foi possível cancelar o pedido agora. Tente novamente mais tarde.'],
];
$mensagemCancelamento = null;
$resultadoCancelamento = $_GET['cancelamento'] ?? '';
if (is_string($resultadoCancelamento) && isset($mensagensCancelamento[$resultadoCancelamento])) {
    $mensagemCancelamento = $mensagensCancelamento[$resultadoCancelamento];
}

try {
    $stmtPedidos = $pdo->prepare(
        'SELECT id, dataHora, statusPag, statusPedido, valorTotal, endereco
         FROM pedidos
         WHERE usuario_id = :usuario_id
         ORDER BY dataHora DESC, id DESC'
    );
    $stmtPedidos->execute([':usuario_id' => $usuarioId]);
    $pedidos = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);

    if ($pedidos !== []) {
        $stmtItens = $pdo->prepare(
            'SELECT i.pedido_id, i.quantidade, i.precoUnidade,
                    COALESCE(p.nome, \'Produto indisponível\') AS item_nome,
                    \'Produto\' AS item_tipo
             FROM itenspedidos i
             INNER JOIN pedidos pe ON pe.id = i.pedido_id
             LEFT JOIN produtos p ON p.id = i.produto_id
             WHERE pe.usuario_id = :usuario_id
             ORDER BY i.pedido_id DESC, item_nome ASC'
        );
        $stmtItens->execute([':usuario_id' => $usuarioId]);

        foreach ($stmtItens->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $itensPorPedido[(int) $item['pedido_id']][] = $item;
        }
    }
} catch (PDOException $erro) {
    error_log('Erro ao consultar pedidos do usuário: ' . $erro->getMessage());
    $erroConsulta = true;
}

$classesStatus = [
    'pendente' => 'text-bg-warning',
    'processando' => 'text-bg-info',
    'enviado' => 'text-bg-primary',
    'entregue' => 'text-bg-success',
    'pago' => 'text-bg-success',
    'recusado' => 'text-bg-danger',
    'reembolsado' => 'text-bg-secondary',
    'cancelado' => 'text-bg-secondary',
];

function escapar(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

function classeStatus(string $status, array $classesStatus): string
{
    return $classesStatus[mb_strtolower($status)] ?? 'text-bg-secondary';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisma Makeup - Meus pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <style>
        body {
            min-height: 100vh;
            background: var(--bege);
        }
        main { padding-top: 130px; padding-bottom: 100px; }
        .orders-panel {
            max-width: 960px;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .12);
        }
        .order-card { border: 1px solid color-mix(in srgb, var(--roxo) 35%, transparent); }
        .order-total { color: var(--roxo); }
    </style>
</head>
<body>
    <?php include '../components/header.php'; ?>

    <main class="container">
        <section class="orders-panel mx-auto p-3 p-md-5" aria-labelledby="page-title">
            <div class="mb-4">
                <h1 id="page-title" class="h3 fw-bold mb-1">Meus pedidos</h1>
                <p class="text-secondary mb-0">Acompanhe suas compras e veja os itens de cada pedido.</p>
            </div>

            <?php if ($mensagemCancelamento !== null): ?>
                <div class="alert alert-<?= escapar($mensagemCancelamento[0]) ?>" role="status">
                    <?= escapar($mensagemCancelamento[1]) ?>
                </div>
            <?php endif; ?>

            <?php if ($erroConsulta): ?>
                <div class="alert alert-danger" role="alert">
                    Não foi possível carregar seus pedidos agora. Tente novamente mais tarde.
                </div>
            <?php elseif ($pedidos === []): ?>
                <div class="text-center py-5">
                    <i class="bi bi-bag-x display-4 text-secondary" aria-hidden="true"></i>
                    <h2 class="h5 fw-bold mt-3">Você ainda não fez pedidos</h2>
                    <p class="text-secondary">Quando fizer uma compra, ela aparecerá aqui.</p>
                    <a class="btn btn-primary rounded-pill px-4" href="produtos.php">Ver produtos</a>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($pedidos as $pedido): ?>
                        <?php
                        $pedidoId = (int) $pedido['id'];
                        $endereco = json_decode((string) ($pedido['endereco'] ?? ''), true);
                        $enderecoPartes = is_array($endereco)
                            ? array_filter([
                                $endereco['rua'] ?? '',
                                $endereco['numero'] ?? '',
                                $endereco['complemento'] ?? '',
                                $endereco['cidade'] ?? '',
                                $endereco['estado'] ?? '',
                                $endereco['cep'] ?? '',
                            ], static fn($parte): bool => is_string($parte) && $parte !== '')
                            : [];
                        ?>
                        <article class="card order-card rounded-3">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                    <div>
                                        <h2 class="h5 fw-bold mb-1">Pedido #<?= $pedidoId ?></h2>
                                        <p class="text-secondary small mb-2">
                                            Feito em
                                            <?php
                                            $dataPedido = strtotime((string) $pedido['dataHora']);
                                            echo $dataPedido === false
                                                ? 'data indisponível'
                                                : date('d/m/Y', $dataPedido) . ' às ' . date('H:i', $dataPedido);
                                            ?>
                                        </p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge <?= escapar(classeStatus((string) $pedido['statusPedido'], $classesStatus)) ?>">
                                                <?= escapar((string) $pedido['statusPedido']) ?>
                                            </span>
                                            <span class="badge <?= escapar(classeStatus((string) $pedido['statusPag'], $classesStatus)) ?>">
                                                Pagamento: <?= escapar((string) $pedido['statusPag']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-md-end">
                                        <span class="small text-secondary d-block">Total do pedido</span>
                                        <strong class="fs-5 order-total">
                                            R$ <?= number_format(((int) $pedido['valorTotal']) / 100, 2, ',', '.') ?>
                                        </strong>
                                    </div>
                                </div>

                                <hr>

                                <h3 class="h6 fw-bold">Itens do pedido</h3>
                                <?php if (!empty($itensPorPedido[$pedidoId])): ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($itensPorPedido[$pedidoId] as $item): ?>
                                            <?php
                                            $nomeProduto = $item['item_nome'];
                                            $precoUnitario = (float) $item['precoUnidade'];
                                            ?>
                                            <li class="list-group-item px-0 d-flex justify-content-between gap-3">
                                                <span>
                                                    <span class="badge text-bg-light me-1"><?= escapar((string) $item['item_tipo']) ?></span>
                                                    <?= escapar((string) $nomeProduto) ?>
                                                    <span class="text-secondary">× <?= (int) $item['quantidade'] ?></span>
                                                </span>
                                                <span class="text-nowrap">
                                                    R$ <?= number_format($precoUnitario * (int) $item['quantidade'], 2, ',', '.') ?>
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-secondary small mb-0">Não há itens disponíveis para este pedido.</p>
                                <?php endif; ?>

                                <?php if ($enderecoPartes !== []): ?>
                                    <p class="small text-secondary mt-3 mb-0">
                                        <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>
                                        Entrega: <?= escapar(implode(', ', $enderecoPartes)) ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (mb_strtolower((string) $pedido['statusPedido']) === 'pendente'): ?>
                                    <form class="mt-3 text-md-end" method="post" action="../scriptsPHP/cancelarPedido.php"
                                        onsubmit="return confirm('Tem certeza de que deseja cancelar este pedido?');">
                                        <input type="hidden" name="pedido_id" value="<?= $pedidoId ?>">
                                        <input type="hidden" name="csrf_token" value="<?= escapar($_SESSION['csrf_cancelar_pedido']) ?>">
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Cancelar pedido
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="perfil.php" class="link-secondary text-decoration-none">
                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Voltar ao perfil
                </a>
            </div>
        </section>
    </main>

    <?php include '../components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/scriptGeral.js"></script>
</body>
</html>