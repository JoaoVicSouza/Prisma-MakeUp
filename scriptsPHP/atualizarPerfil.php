<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../pages/login.php?erro=acesso_negado');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método não permitido.');
}

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$nome = is_string($nome) ? trim($nome) : '';
$email = is_string($email) ? trim($email) : '';

$nomeValido = preg_match('/\A[\p{L}\p{M}]+(?:[ \'-][\p{L}\p{M}]+)*\z/u', $nome) === 1
    && preg_match('/\s/u', $nome) === 1;

if (!$nomeValido || mb_strlen($nome) > 100) {
    header('Location: ../pages/perfil.php?erro=nome');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    header('Location: ../pages/perfil.php?erro=email');
    exit;
}

require 'conexao.php';

try {
    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email AND id <> :id LIMIT 1');
    $stmt->execute([
        ':email' => $email,
        ':id' => (int) $_SESSION['usuario_id'],
    ]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        header('Location: ../pages/perfil.php?erro=email_existente');
        exit;
    }

    $stmt = $pdo->prepare('UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id');
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':id' => (int) $_SESSION['usuario_id'],
    ]);

    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_email'] = $email;

    header('Location: ../pages/perfil.php?sucesso=perfil');
    exit;
} catch (PDOException $erro) {
    error_log('Erro ao atualizar perfil: ' . $erro->getMessage());
    header('Location: ../pages/perfil.php?erro=servidor');
    exit;
}
?>