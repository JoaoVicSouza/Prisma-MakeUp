<?php
require_once __DIR__ . '/adminAuth.php';
requireAdmin('../index.php');
require 'conexao.php';

function redirecionarErroCurso(string $erro): void
{
    header('Location: ../pages/CRUD/cadCurso.php?erro=' . urlencode($erro));
    exit;
}

$nome = trim($_POST['name'] ?? '');
$produtoId = filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT);
$nivel = trim($_POST['level'] ?? '');
$cargaH = filter_input(INPUT_POST, 'cargaH', FILTER_VALIDATE_INT);
$objetivo = trim($_POST['objetivo'] ?? '');
$conteudo = trim($_POST['conteudo'] ?? '');
$publicoAlvo = trim($_POST['PublicoAlvo'] ?? '');
if (
    $nome === '' || mb_strlen($nome) > 150 ||
    $nivel === '' || mb_strlen($nivel) > 50 ||
    $produtoId === false || $produtoId === null || $produtoId < 1 ||
    $cargaH === false || $cargaH === null || $cargaH < 0 ||
    mb_strlen($objetivo) > 255 ||
    mb_strlen($conteudo) > 300 ||
    mb_strlen($publicoAlvo) > 100
) {
    redirecionarErroCurso('dados');
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO cursos (nome, nivel, cargaH, preco, objetivo, conteudo, PublicoAlvo, id_produto)
         VALUES (:nome, :nivel, :cargaH, 0, :objetivo, :conteudo, :publico_alvo, :produto_id)'
    );
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':nivel', $nivel);
    $stmt->bindValue(':cargaH', $cargaH, PDO::PARAM_INT);
    $stmt->bindValue(':objetivo', $objetivo === '' ? null : $objetivo, $objetivo === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':conteudo', $conteudo === '' ? null : $conteudo, $conteudo === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':publico_alvo', $publicoAlvo === '' ? null : $publicoAlvo, $publicoAlvo === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':produto_id', $produtoId, PDO::PARAM_INT);
    $stmt->execute();
    header('Location: ../pages/CRUD/cadCurso.php?sucesso=cadastro');
    exit;
} catch (PDOException $erro) {
    error_log('Erro ao cadastrar curso: ' . $erro->getMessage());
    redirecionarErroCurso('servidor');
}