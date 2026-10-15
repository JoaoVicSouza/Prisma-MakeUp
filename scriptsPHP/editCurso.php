<?php
require_once __DIR__ . '/adminAuth.php';
requireAdmin('../index.php');
require 'conexao.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome = trim($_POST['nome'] ?? '');
$produtoId = filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT);
$nivel = trim($_POST['nivel'] ?? '');
$cargaH = filter_input(INPUT_POST, 'cargaH', FILTER_VALIDATE_INT);
$objetivo = trim($_POST['objetivo'] ?? '');
$conteudo = trim($_POST['conteudo'] ?? '');
$publicoAlvo = trim($_POST['PublicoAlvo'] ?? '');
if (
    !$id || $nome === '' || mb_strlen($nome) > 150 ||
    $produtoId === false || $produtoId === null || $produtoId < 1 ||
    $nivel === '' || mb_strlen($nivel) > 50 ||
    $cargaH === false || $cargaH === null || $cargaH < 0 ||
    mb_strlen($objetivo) > 255 || mb_strlen($conteudo) > 300 || mb_strlen($publicoAlvo) > 100
) {
    header('Location: ../pages/CRUD/mod.php?tipo=curso&id=' . (int) $id . '&erro=dados');
    exit;
}

$stmtCurso = $pdo->prepare('SELECT id FROM cursos WHERE id = :id');
$stmtCurso->execute([':id' => $id]);

if ($stmtCurso->fetchColumn() === false) {
    header('Location: ../pages/CRUD/admin.php');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE cursos SET nome=:nome, id_produto=:produto_id, nivel=:nivel,
        cargaH=:cargaH, preco=0, objetivo=:objetivo, conteudo=:conteudo, PublicoAlvo=:publico_alvo
        WHERE id=:id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':produto_id', $produtoId, PDO::PARAM_INT);
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':nivel', $nivel);
    $stmt->bindValue(':cargaH', $cargaH, PDO::PARAM_INT);
    $stmt->bindValue(':objetivo', $objetivo === '' ? null : $objetivo, $objetivo === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':conteudo', $conteudo === '' ? null : $conteudo, $conteudo === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':publico_alvo', $publicoAlvo === '' ? null : $publicoAlvo, $publicoAlvo === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->execute();
    header('Location: ../pages/CRUD/admin.php');
} catch (PDOException $e) {
    error_log('Erro ao editar curso: ' . $e->getMessage());
    header('Location: ../pages/CRUD/mod.php?tipo=curso&id=' . $id . '&erro=servidor');
}
exit;
