<?php
session_start();
require 'conexao.php';

$email = trim($_POST['email']);
$senha = trim($_POST['password']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../pages/login.php?erro=email_invalido');
    exit;
}

$checkEmail = $pdo->prepare("SELECT id, senha, nome, admin FROM usuarios WHERE email = :email");
$checkEmail->bindParam(':email', $email);
$checkEmail->execute();

$usuario = $checkEmail->fetch(PDO::FETCH_ASSOC);

if (password_verify($senha, $usuario['senha'])) {
    $_SESSION['usuario_id']   = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $email;
    $_SESSION['admin'] = $usuario['admin'];
    header('Location: ../index.php');
    exit;
} else {
    header('Location: ../pages/login.php?erro=credenciais');
    exit;
}
;

?>