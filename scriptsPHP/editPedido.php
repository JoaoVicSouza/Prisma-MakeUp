<?php
require_once 'adminAuth.php';
requireAdmin('../index.php');
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método não permitido.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$statusPedido = $_POST['statusPedido'] ?? '';
$statusPag = $_POST['statusPag'] ?? '';

$statusPedidoValidos = ['Pendente', 'Processando', 'Enviado', 'Entregue', 'Cancelado'];
$statusPagamentoValidos = ['Pendente', 'Pago', 'Recusado', 'Reembolsado', 'Cancelado'];

if (
    !$id || $id < 1 ||
    !is_string($statusPedido) || !in_array($statusPedido, $statusPedidoValidos, true) ||
    !is_string($statusPag) || !in_array($statusPag, $statusPagamentoValidos, true)
) {
    header('Location: ../pages/CRUD/admin.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'UPDATE pedidos SET statusPedido = :status_pedido, statusPag = :status_pag WHERE id = :id'
    );
    $stmt->execute([
        ':status_pedido' => $statusPedido,
        ':status_pag' => $statusPag,
        ':id' => $id,
    ]);
    header('Location: ../pages/CRUD/admin.php');
    exit;
} catch (PDOException $erro) {
    error_log('Erro ao editar pedido: ' . $erro->getMessage());
    header('Location: ../pages/CRUD/mod.php?tipo=pedido&id=' . $id . '&erro=servidor');
    exit;
}
