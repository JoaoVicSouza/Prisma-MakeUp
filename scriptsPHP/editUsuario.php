<?php
require 'conexao.php';

$id    = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$admin = isset($_POST['admin']) ? 1 : 0;

if (!$id || $nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../pages/CRUD/admin.php');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE usuarios SET nome=:nome, email=:email, admin=:admin WHERE id=:id");
    $stmt->execute([':id' => $id, ':nome' => $nome, ':email' => $email]);
    header('Location: ../pages/CRUD/admin.php');
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
exit;
