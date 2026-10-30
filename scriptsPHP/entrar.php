<?php
session_start();
require 'conexao.php';

$email = trim($_POST['email'] ?? '');
$senha = $_POST['password'] ?? '';
if (!is_string($senha)) {
    $senha = '';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../pages/login.php?erro=email_invalido');
    exit;
}    

$checkEmail = $pdo->prepare("SELECT id, senha, nome, admin FROM usuarios WHERE email = :email");
$checkEmail->bindParam(':email', $email);
$checkEmail->execute();

$usuario = $checkEmail->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($senha, $usuario['senha'])) {
    session_regenerate_id(true);
    $_SESSION['usuario_id']   = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $email;
    $_SESSION['admin'] = $usuario['admin'];
    $origem = $_POST['origem'] ?? 'menu';
    if ($origem === 'checkout') {
        header('Location: ../pages/compra.php');
        exit;
    }

    $_SESSION['limpar_carrinho_apos_login'] = true;
    header('Location: ../index.php');
    exit;
} else {
    header('Location: ../pages/login.php?erro=credenciais');
    exit;
}
;

?>