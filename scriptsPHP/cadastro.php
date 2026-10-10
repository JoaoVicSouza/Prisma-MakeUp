<?php 
require 'conexao.php';

$nome  = trim($_POST['name']);
$email = trim($_POST['email']);
$senha = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../pages/login.php?erro=email_invalido');
    exit;
}


$check = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
$check->bindParam(':email', $email);
$check->execute();

if ($check->fetch()) {
    header('Location: ../pages/login.php?erro=email_existente');
    exit;
}


try{
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);

    $stmt->execute();
    header('Location: ../pages/login.php?sucesso=cadastro');
    exit;
} catch(PDOException $erro){
    header('Location: ../pages/login.php?erro=servidor');
    exit;
}
?>