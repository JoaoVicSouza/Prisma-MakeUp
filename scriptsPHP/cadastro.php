<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método não permitido.');
}

$nome = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['password'] ?? '';

$nome = is_string($nome) ? trim($nome) : '';
$email = is_string($email) ? trim($email) : '';
$senha = is_string($senha) ? $senha : '';
$origemCheckout = ($_POST['origem'] ?? '') === 'checkout';
$sufixoOrigem = $origemCheckout ? '&origem=checkout' : '';

$nomeValido = preg_match('/\A[\p{L}\p{M}]+(?:[ \'-][\p{L}\p{M}]+)*\z/u', $nome) === 1
    && preg_match('/\s/u', $nome) === 1;

if (!$nomeValido || mb_strlen($nome) > 100) {
    header('Location: ../pages/login.php?aba=cadastro&erro=nome_invalido' . $sufixoOrigem);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 255) {
    header('Location: ../pages/login.php?aba=cadastro&erro=email_invalido' . $sufixoOrigem);
    exit;
}

if (strlen($senha) < 8) {
    header('Location: ../pages/login.php?aba=cadastro&erro=senha_invalida' . $sufixoOrigem);
    exit;
}

try {
    $consulta = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email LIMIT 1');
    $consulta->execute([':email' => $email]);
    if ($consulta->fetch(PDO::FETCH_ASSOC)) {
        header('Location: ../pages/login.php?aba=cadastro&erro=email_existente' . $sufixoOrigem);
        exit;
    }

    $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)');
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => password_hash($senha, PASSWORD_DEFAULT),
    ]);

    header('Location: ../pages/login.php?sucesso=cadastro' . $sufixoOrigem);
    exit;
} catch (PDOException $erro) {
    if ($erro->getCode() === '23000') {
        header('Location: ../pages/login.php?aba=cadastro&erro=email_existente' . $sufixoOrigem);
        exit;
    }

    error_log('Erro ao cadastrar usuário: ' . $erro->getMessage());
    header('Location: ../pages/login.php?aba=cadastro&erro=servidor' . $sufixoOrigem);
    exit;
}
?>