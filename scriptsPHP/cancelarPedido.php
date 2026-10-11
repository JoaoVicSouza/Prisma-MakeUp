<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirecionarPedidos(string $resultado): void
{
    header('Location: ../pages/pedidos.php?cancelamento=' . urlencode($resultado));
    exit;
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

$csrfEnviado = $_POST['csrf_token'] ?? null;
$csrfSessao = $_SESSION['csrf_cancelar_pedido'] ?? null;
if (
    !is_string($csrfEnviado) ||
    !is_string($csrfSessao) ||
    !hash_equals($csrfSessao, $csrfEnviado)
) {
    redirecionarPedidos('servidor');
}

$pedidoId = filter_input(INPUT_POST, 'pedido_id', FILTER_VALIDATE_INT);
if (!$pedidoId || $pedidoId < 1) {
    redirecionarPedidos('indisponivel');
}

require 'conexao.php';

try {
    $pdo->beginTransaction();

    $stmtPedido = $pdo->prepare(
        'SELECT statusPedido, statusPag
         FROM pedidos
         WHERE id = :pedido_id AND usuario_id = :usuario_id
         FOR UPDATE'
    );
    $stmtPedido->execute([
        ':pedido_id' => $pedidoId,
        ':usuario_id' => $usuarioId,
    ]);
    $pedido = $stmtPedido->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        $pdo->rollBack();
        redirecionarPedidos('indisponivel');
    }

    if (mb_strtolower((string) $pedido['statusPedido']) !== 'pendente') {
        $pdo->rollBack();
        redirecionarPedidos('nao_pendente');
    }

    $stmtItens = $pdo->prepare(
        'SELECT produto_id, SUM(quantidade) AS quantidade
         FROM itenspedidos
         WHERE pedido_id = :pedido_id
         GROUP BY produto_id'
    );
    $stmtItens->execute([':pedido_id' => $pedidoId]);
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
    if ($itens === []) {
        throw new RuntimeException('O pedido não possui itens para devolver ao estoque.');
    }

    $stmtEstoque = $pdo->prepare(
        'UPDATE produtos SET estoque = estoque + :quantidade WHERE id = :produto_id'
    );
    foreach ($itens as $item) {
        $stmtEstoque->execute([
            ':quantidade' => (int) $item['quantidade'],
            ':produto_id' => (int) $item['produto_id'],
        ]);
        if ($stmtEstoque->rowCount() !== 1) {
            throw new RuntimeException('Não foi possível repor o estoque de um item do pedido.');
        }
    }

    $statusPagamento = mb_strtolower((string) $pedido['statusPag']) === 'pendente'
        ? 'Cancelado'
        : (string) $pedido['statusPag'];
    $stmtCancelar = $pdo->prepare(
        'UPDATE pedidos
         SET statusPedido = :status_pedido, statusPag = :status_pag
         WHERE id = :pedido_id AND usuario_id = :usuario_id'
    );
    $stmtCancelar->execute([
        ':status_pedido' => 'Cancelado',
        ':status_pag' => $statusPagamento,
        ':pedido_id' => $pedidoId,
        ':usuario_id' => $usuarioId,
    ]);

    $pdo->commit();
    redirecionarPedidos('sucesso');
} catch (Throwable $erro) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Erro ao cancelar pedido: ' . $erro->getMessage());
    redirecionarPedidos('servidor');
}
