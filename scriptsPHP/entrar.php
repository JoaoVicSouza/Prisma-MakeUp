<?php
session_start();
require 'conexao.php';

$email = $_POST['email'];
$senha = $_POST['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../pages/login.php?erro=email_invalido');
    exit;
}    

$checkEmail = $pdo->prepare("SELECT id, senha, nome FROM usuarios WHERE email = :email");
$checkEmail->bindParam(':email', $email);
$checkEmail->execute();

$usuario = $checkEmail->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($senha, $usuario['senha'])) {
    $_SESSION['usuario_id']   = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    header('Location: ../index.php');
    exit;
} else {
    header('Location: ../pages/login.php?erro=credenciais');
    exit;
}
;

?>